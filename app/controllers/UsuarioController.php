<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';

class UsuarioController extends Controller
{
    public function altauser()
    {
        Session::start();

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        /*
        if ($rol !== 'rh') {
            header("Location: " . BASE_URL . "dashboard");
            exit();
        }
        */

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('altauser', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio
        ]);
    }

    public function verempleados()
    {
        Session::start();

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $empleados = Usuario::listaempleados();

        $this->view('verempleados', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleados' => $empleados
        ]);
    }

    public function registrar()
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nombre = $_POST['name'];
            $usuario = $_POST['user'];
            $password = password_hash($_POST['psswrd'], PASSWORD_BCRYPT);
            $edificio = $_POST['edificio'];
            $area = $_POST['area'];
            $vacaciones = $_POST['vacaciones'];
            $creador = Session::get('name');

            $resultado = Usuario::registrar($id, $nombre, $edificio, $area, $vacaciones, $usuario, $password, $creador);

            if ($resultado) {
                Session::set('message', "Usuario agregado correctamente");
            } else {
                Session::set('error_message', "Error al registrar usuario.");
            }

            header("Location: " . BASE_URL . "usuario/altauser");
            exit();
        }

    }
    public function generarReporte()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empleado'];

            $userInfo = Usuario::pushUserID($id);
            if ($userInfo) {
                Usuario::generarReporte($userInfo);
            }
        }
    }

    public static function generarInforme()
    {
        Session::start();
        try {
            Usuario::generarExcel();
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        }
    }
    public function pantallaCarga()
    {
        Session::start();

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        if (
            isset($_FILES['archivo_dat']) &&
            $_FILES['archivo_dat']['error'] == 0 &&
            strtolower(pathinfo($_FILES['archivo_dat']['name'], PATHINFO_EXTENSION)) === 'dat'
        ) {
            $archivoTmp = $_FILES['archivo_dat']['tmp_name'];
            $nombreOriginal = basename($_FILES['archivo_dat']['name']);

            $rutaDestino = dirname(__DIR__, 2) . '/public/uploads/' . $nombreOriginal;
            move_uploaded_file($archivoTmp, $rutaDestino);

            Session::set('archivo_subido', $nombreOriginal);
        } else {
            Session::set('error_message', 'No se seleccionó un archivo .dat válido.');
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $message = Session::get('message');
        Session::remove('message');

        $this->view('cargandodat', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'error_message' => $error_message,
            'message' => $message,
            'archivo' => Session::get('archivo_subido')
        ]);
    }

    public static function subirAsistencias()
    {
        Session::start();
        $archivo = Session::get('archivo_subido');
        $name = Session::get('name');
    
        if (!$archivo) {
            Session::set('error_message', 'Archivo no especificado.');
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
            return;
        }
    
        $ruta = dirname(__DIR__, 2) . '/public/uploads/' . $archivo;
        $resultado = Usuario::procesarAsistencias($ruta);
    
        if ($resultado) {
            Session::set('message', 'Archivo procesado exitosamente.');
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        } else {
            Session::set('error_message', 'Error al procesar el archivo.');
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        }
    }
}
?>
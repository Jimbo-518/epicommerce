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

    public static function generarInforme(){
        
    }

    public static function subirAsistencias(){
        Session::start();
        $archivo = $_GET['archivo'] ?? '';
        $name = Session::get('name');
    
        if (!$archivo) {
            Session::set('error_message', 'Archivo no especificado.');
            echo json_encode(['redirect' => BASE_URL . 'importfile']);
            return;
        }
    
        $ruta = dirname(__DIR__, 2) . '/public/uploads/' . $archivo;
        $resultado = Usuario::procesarAsistencias($ruta);
    
        if ($resultado) {
            Session::set('message', 'Archivo procesado exitosamente.');
            echo json_encode(['redirect' => BASE_URL . 'importfile']);
        } else {
            Session::set('error_message', 'Error al procesar el archivo.');
            echo json_encode(['redirect' => BASE_URL . 'importfile']);
        }
        
    }
}
?>
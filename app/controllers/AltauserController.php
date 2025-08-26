<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';
require_once '../app/models/Edificios.php';
require_once '../app/models/Departamentos.php';

class AltauserController extends Controller
{
    public function index()
    {
        Session::start();
        $this->verificarPermiso('altauser');

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $edificioslist = Edificios::listaEdificios();
        $areaslist = Departamentos::listaAreas();

        $permisos = Session::get('permisos');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('altauser', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'edificioslist' => $edificioslist,
            'areaslist' => $areaslist,
            'permisos' => $permisos
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

            //datos para id_depto_edificio
            $edificio = $_POST['edificio'];
            $area = $_POST['area'];

            $vacaciones = $_POST['vacaciones'];
            $fecha_ingreso = $_POST['fecha_ingreso'];

            $id_depto_edificio = Edificios::obtenerIdDeptoEdificio($edificio, $area);

            $resultado = Usuario::registrar($id, $nombre, $id_depto_edificio, $usuario, $password, $vacaciones, $fecha_ingreso);

            if ($resultado) {
                Session::set('message', "Usuario agregado correctamente");
            } else {
                Session::set('error_message', "Error al registrar usuario.");
            }

            header("Location: " . BASE_URL . "usuario/altauser");
            exit();
        }

    }
}
?>
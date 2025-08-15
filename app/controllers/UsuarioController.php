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
}
?>
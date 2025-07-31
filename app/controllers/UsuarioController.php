<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';

class UsuarioController extends Controller {
    public function altauser() {
        Session::start();

        $rol = Session::get('rol');
        $name = Session::get('name');

        if ($rol !== 'gestor' && $rol !== 'administrador') {
            header("Location: " . BASE_URL . "dashboard");
            exit();
        }

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('altauser', [
            'error_message' => $error_message,
            'name' => $name,
            'rol' => $rol
        ]);
    }

    public function registrar() {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['name'];
            $usuario = $_POST['user'];
            $password = password_hash($_POST['psswrd'], PASSWORD_BCRYPT);
            $rol = $_POST['rol'];
            $creador = Session::get('name');

            $resultado = Usuario::registrar($nombre, $usuario, $password, $rol, $creador);

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
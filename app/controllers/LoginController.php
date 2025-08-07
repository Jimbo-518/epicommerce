<?php
require_once '../app/models/Usuario.php';
require_once '../app/core/Session.php';

class LoginController extends Controller {
    public function index() {
        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('login', ['error_message' => $error_message]);
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $usuario = Usuario::verificarCredenciales($username, $password);
                   
            if ($usuario) {
                Session::start();
                Session::set('name', $usuario['nombre']);
                Session::set('edificio', $usuario['edificio']);
                Session::set('area', $usuario['area']);
                
                Session::set('usuario', $usuario['usuario']);
                Session::set('vacaciones', $usuario['vacaciones']);
                Session::set('creador', $usuario['creador']);
                
                header("Location: " . BASE_URL . "dashboard");
                exit();
            } else {
                Session::set('error_message', "Usuario o contraseña incorrectos.");
                header("Location: " . BASE_URL . "login");
                exit();
            }
        }
    }

    public function logout() {
        Session::destroy();
        header("Location: " . BASE_URL . "login");
        exit();
    }
}
?>
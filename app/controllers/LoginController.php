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

            $resultado = Usuario::verificarCredenciales($username, $password);
                   
            if ($resultado) {
                $usuario = $resultado['usuario'];
                $info_adicional = $resultado['info_adicional'];
                $permisos = $resultado['permisos'];

                Session::start();
                Session::set('id_empleado', $usuario['id_empleado']);
                Session::set('name', $usuario['nombre']);
                Session::set('usuario', $usuario['usuario']);
                Session::set('vacaciones', $usuario['vacaciones']);

                Session::set('edificio', $info_adicional['edificio']);
                Session::set('area', $info_adicional['departamento']);

                Session::set('permisos', $permisos);
                
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
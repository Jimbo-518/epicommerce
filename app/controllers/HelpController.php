<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';
require_once '../app/models/Usuario.php';
require_once '../app/models/Movimientos.php';

class HelpController extends Controller
{
    public function index()
    {
        Session::start();

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $message = Session::get('message');
        Session::remove('message');

        $this->view('help', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'error_message' => $error_message,
            'message' => $message,
        ]);
    }

    public function deleteBD()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['user'] ?? '';
            $password = $_POST['psswrd'] ?? '';
            $name = Session::get('name');

            $resultado = Usuario::verificarCredenciales($username, $password);
            $usuario = $resultado['usuario'];

            if ($usuario && password_verify($password, $usuario['password'])) {
                if ($usuario && $usuario['Rol'] === 'gestor' && password_verify($password, $usuario['Password'])) {
                    Inventario::vaciarBaseDeDatos();
                    Movimientos::deleteBD($name);
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Base de datos vaciada correctamente.'];
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'No autorizado.'];
                }
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Credenciales incorrectas'];
            }
            header("Location: " . BASE_URL . "help");
            exit;
        }
    }
    public function devolucionPrenda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['user'] ?? '';
            $password = $_POST['psswrd'] ?? '';
            $upc = $_POST['upc'] ?? '';
            $marbete = $_POST['mar'] ?? '';
            $name = Session::get('name');

            $resultado = Usuario::verificarCredenciales($username, $password);
            $usuario = $resultado['usuario'];

            if ($usuario && password_verify($password, $usuario['password'])) {
                if ($usuario && $usuario['Rol'] === 'gestor' && password_verify($password, $usuario['Password'])) {
                    Inventario::devolverPrenda($upc, $marbete);
                    Movimientos::devPrenda($name, $marbete, $upc);
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'La prenda ha sido devuelta'];
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'No autorizado.'];
                }
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Credenciales incorrectas'];
            }
            header("Location: " . BASE_URL . "help");
            exit;
        } 
    }

    public function errorPrenda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['user'] ?? '';
            $password = $_POST['psswrd'] ?? '';
            $upc = $_POST['upc'] ?? '';
            $marbete = $_POST['mar'] ?? '';
            $upcC = $_POST['upcC'] ?? '';
            $marbeteC = $_POST['marC'] ?? '';
            $name = Session::get('name');
            $cantidadDESC = 1;

            $resultado = Usuario::verificarCredenciales($username, $password);
            $usuario = $resultado['usuario'];

            $inventario = new Inventario();

            if ($usuario && password_verify($password, $usuario['password'])) {
                if ($usuario && $usuario['Rol'] === 'gestor' && password_verify($password, $usuario['Password'])) {
                    $resultado = $inventario->descontarPrenda($marbete, $upc, $cantidadDESC);
                    Inventario::devolverPrenda($upcC, $marbeteC);
                    Movimientos::changePrenda($name, $marbete, $upc, $marbeteC, $upcC);
                    $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Se ha hecho la corrección'];
                } else {
                    $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'No autorizado.'];
                }
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Credenciales incorrectas'];
            }
            header("Location: " . BASE_URL . "help");
            exit;
        } 
    }
}
?>
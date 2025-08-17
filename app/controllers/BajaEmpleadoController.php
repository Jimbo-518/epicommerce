<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';

class BajaEmpleadoController extends Controller
{
    public function index()
    {
        Session::start();

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $id = $_GET['id'];
        $empleado = Usuario::pushUserID($id);

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('bajaEmpleado', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleado' => $empleado
        ]);
    }

    public function bajaUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empleado'];
            if (Usuario::eliminarEmpleado($id)) {
                header("Location: " . BASE_URL . "verempleados/verempleados");
                exit();
            }
        }
    }
}
?>
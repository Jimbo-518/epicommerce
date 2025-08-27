<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';

class BajaEmpleadoController extends Controller
{
    public function index()
    {
        Session::start();
        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }
        $this->verificarPermiso('bajaEmpleado');

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');
        $permisos = Session::get('permisos');

        $id = $_GET['id'];
        $resultado = Usuario::pushUserID($id);
        $empleadodata = $resultado['usuario'];
        $info_adicional = $resultado['info_adicional'];

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('bajaEmpleado', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleado' => $empleadodata,
            'info_adicional' => $info_adicional,
            'permisos' => $permisos
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
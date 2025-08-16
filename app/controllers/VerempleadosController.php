<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';
require_once '../app/models/Asistencias.php';

class VerempleadosController extends Controller
{
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

    public function generarReporte()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empleado'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];

            $userInfo = Usuario::pushUserID($id);

            if ($userInfo) {
                Asistencias::generarReporte($userInfo, $fecha_inicio, $fecha_fin);
            }
        }
    }

    public function bajaUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empleado'];
            if (Usuario::eliminarEmpleado($id)) {
                header("Location: " . BASE_URL . "verempleados/verempleados");
                exit();
            }
        } else {
            $id = $_GET['id'];
            $empleado = Usuario::pushUserID($id);
            $this->view('bajaEmpleado', ['empleado' => $empleado]);
        }
    }
}
?>
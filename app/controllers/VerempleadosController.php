<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';
require_once '../app/models/Asistencias.php';

class VerempleadosController extends Controller
{
    public function index()
    {
        Session::start();
        $this->verificarPermiso('verempleados');

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');
        
        $permisos = Session::get('permisos');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $empleados = Usuario::listaempleados();

        $this->view('verempleados', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleados' => $empleados,
            'permisos' => $permisos
        ]);
    }

    public function generarReporte()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empleado'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];

            $resultado = Usuario::pushUserID($id);
            $userInfo = $resultado['usuario'];
            $info_adicional = $resultado['info_adicional'];

            if ($userInfo) {
                Asistencias::generarReporte($userInfo, $info_adicional, $fecha_inicio, $fecha_fin);
            }
        }
    }
}
?>
<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';
require_once '../app/models/Edificios.php';
require_once '../app/models/Departamentos.php';

class EditarEmpleadoController extends Controller
{
    public function index()
    {
        Session::start();

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $id = $_GET['id'];
        $resultado = Usuario::pushUserID($id);
        $empleadodata = $resultado['usuario'];
        $info_adicional = $resultado['info_adicional'];
        $edificioslist = Edificios::listaEdificios();
        $areaslist = Departamentos::listaAreas();

        $permisos = Session::get('permisos');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('editarEmpleado', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleadodata' => $empleadodata,
            'edificioslist' => $edificioslist,
            'areaslist' => $areaslist,
            'info_adicional' => $info_adicional,
            'permisos' => $permisos
        ]);
    }

    public function editarUsuario()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id_empleado'];
        $nombre = $_POST['nombre'];
        $edificio_nombre = $_POST['edificio'];
        $area_nombre = $_POST['area'];
        $vacaciones = $_POST['vacaciones'];

        // Paso 1: Obtener el ID del departamento y el edificio
        $id_depto_edificio = Edificios::obtenerIdDeptoEdificio($edificio_nombre, $area_nombre);

        if ($id_depto_edificio) {
            // Paso 2: Si el ID existe, actualizar el empleado
            $resultado = Usuario::actualizarEmpleado($id, $nombre, $id_depto_edificio, $vacaciones);
            
            if ($resultado) {
                header("Location: " . BASE_URL . "verempleados/verempleados");
                exit();
            } else {
                // Manejar error si la actualización falla
            }
        } else {
            // Manejar error si no se encuentra el ID
        }
    }
}
}
?>
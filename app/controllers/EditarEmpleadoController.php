<?php
require_once '../app/core/Session.php';
require_once '../app/models/Usuario.php';

class EditarEmpleadoController extends Controller
{
    public function editarempleado()
    {
        Session::start();

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $id = $_GET['id'];
        $empleadodata = Usuario::pushUserID($id);
        $edificioslist = Usuario::listaEdificios();
        $areaslist = Usuario::listaAreas();

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $this->view('editarEmpleado', [
            'error_message' => $error_message,
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleadodata' => $empleadodata,
            'edificioslist' => $edificioslist,
            'areaslist' => $areaslist
        ]);
    }

    public function editarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_empleado'];
            $nombre = $_POST['nombre'];
            $edificio = $_POST['edificio'];
            $area = $_POST['area'];
            $vacaciones = $_POST['vacaciones'];

            $resultado = Usuario::actualizarEmpleado($id, $nombre, $edificio, $area, $vacaciones);
            if ($resultado) {
                header("Location: " . BASE_URL . "verempleados/verempleados");
                exit();
            }
        }
    }
}
?>
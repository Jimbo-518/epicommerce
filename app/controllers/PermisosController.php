<?php
require_once '../app/core/Session.php';
require_once '../app/models/Departamentos.php';
require_once '../app/models/Paginas.php';

class PermisosController extends Controller
{
    public function index()
    {
        Session::start();

        if (isset($_POST['id_depto'])) {
            $id_depto = $_POST['id_depto'];
        }

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $paginaslist = Paginas::obtenerPaginas();
        $datos_depto = Departamentos::datosDepto($id_depto);
        $permisos_depto = Paginas::obtenerPaginasPorDepto($id_depto);

        $this->view('permisos', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'paginaslist' => $paginaslist,
            'datos_depto' => $datos_depto,
            'permisos_depto' => $permisos_depto
        ]);
    }

    public function guardar_permisos()
    {
    Session::start();

    if (!Session::get('usuario')) {
        header("Location: " . BASE_URL . "login");
        exit();
    }

    if (!isset($_POST['id_depto'])) {
        header("Location: " . BASE_URL . "departamentos");
        exit();
    }

    $id_depto = $_POST['id_depto'];
    $permisos_seleccionados = isset($_POST['permisos']) ? $_POST['permisos'] : [];

    Paginas::actualizarPermisos($id_depto, $permisos_seleccionados);

    header("Location: " . BASE_URL . "deptosyedificios");
    exit();
    }
}
?>
<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';
require_once '../app/models/Movimientos.php';
require_once '../app/models/Edificios.php';
require_once '../app/models/Departamentos.php';

class DeptosyedificiosController extends Controller
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

        $edificioslist = Edificios::listaEdificios();
        $areaslist = Departamentos::listaAreas();
        $relacioneslist = Edificios::obtenerRelacionesDeptoEdificio();

        // Obtener modelos únicos
        $modelos = Inventario::obtenerModelos();

        $this->view('deptosyedificios', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'modelos' => $modelos,
            'edificioslist' => $edificioslist,
            'areaslist' => $areaslist,
            'relacioneslist' => $relacioneslist
        ]);
    }
}
?>
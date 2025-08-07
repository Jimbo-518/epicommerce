<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';

class DashboardController extends Controller {
    public function index() {
        Session::start();

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        error_log("Usuario en sesión: " . ($area ?: 'No definido'));
        error_log("Nombre en sesión: " . ($name ?: 'No definido'));

        $inventario = Inventario::obtenerEstadisticasPorMarca();

        $this->view('dashboard', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'inventario' => $inventario
        ]);
    }
}
?>
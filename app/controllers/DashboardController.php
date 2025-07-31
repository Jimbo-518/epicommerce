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

        $rol = Session::get('rol');
        $name = Session::get('name');

        error_log("Usuario en sesión: " . ($rol ?: 'No definido'));
        error_log("Nombre en sesión: " . ($name ?: 'No definido'));

        $inventario = Inventario::obtenerEstadisticasPorMarca();

        $this->view('dashboard', [
            'rol' => $rol,
            'name' => $name,
            'inventario' => $inventario
        ]);
    }
}
?>
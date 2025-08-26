<?php
require_once '../app/core/Session.php';
require_once '../app/models/Justificante.php';

class VerjustificantesController extends Controller
{
    public function index()
    {
        Session::start();
        $this->verificarPermiso('verjustificantes');

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');
        
        $permisos = Session::get('permisos');

        $justificantes = Justificante::listarTodos();

        $this->view('verjustificantes', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'justificantes' => $justificantes,
            'permisos' => $permisos
        ]);
    }
}
?>
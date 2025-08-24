<?php
require_once '../app/core/Session.php';
require_once '../app/models/Justificante.php';

class VerjustificantesController extends Controller
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

        $justificantes = Justificante::listarTodos();

        $this->view('verjustificantes', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'justificantes' => $justificantes
        ]);
    }
}
?>
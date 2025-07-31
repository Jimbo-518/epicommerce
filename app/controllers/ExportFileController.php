<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';
require_once '../app/models/Movimientos.php';

class ExportFileController extends Controller
{
    public function index()
    {
        Session::start();

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $rol = Session::get('rol');
        $name = Session::get('name');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $message = Session::get('message');
        Session::remove('message');

        $this->view('exportfile', [
            'rol' => $rol,
            'name' => $name,
            'error_message' => $error_message,
            'message' => $message
        ]);
    }

    public function descargar(){
        Session::start();
        $base = $_POST['marca'] ?? '';
        $name = Session::get('name');

        if (!$base) {
            Session::set('error_message', 'No se ha seleccionado marca' . $base);
            echo json_encode(['redirect' => BASE_URL . 'exportfile']);
            return;
        }

        try {
            Movimientos::descarga($name);
            Inventario::descargarBaseExcel($base);
            echo json_encode(['redirect' => BASE_URL . 'exportfile']);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            echo json_encode(['redirect' => BASE_URL . 'exportfile']);
        }
    }
}
?>
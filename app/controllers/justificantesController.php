<?php
require_once '../app/core/Session.php';
require_once '../app/models/Justificante.php';
require_once '../app/models/Usuario.php';

class JustificantesController extends Controller
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

        $empleados = Usuario::listaempleados();

        $this->view('justificantes', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'empleados' => $empleados
        ]);
    }

    public function store()
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_empleado = $_POST['id_empleado'];
            $fecha = $_POST['fecha'];
            $descripcion = $_POST['descripcion'];
            $auditor = Session::get('name');

            $nombreArchivo = null;
            if (!empty($_FILES['evidencia']['name']) && $_FILES['evidencia']['error'] === UPLOAD_ERR_OK) {
                $directorio = dirname(__DIR__, 2) . '/storage/uploads/justificantes/';
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }

                $original = basename($_FILES['evidencia']['name']);
                $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
                $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($original, PATHINFO_FILENAME));
                $nombreArchivo = time() . '_' . $base . ($ext ? ".{$ext}" : '');

                move_uploaded_file($_FILES['evidencia']['tmp_name'], $directorio . $nombreArchivo);
            }

            Justificante::crear($id_empleado, $fecha, $descripcion, $auditor, $nombreArchivo);

            header("Location: " . BASE_URL . "justificantes");
            exit();
        }
    }
}
?>
<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';
require_once '../app/models/Asistencias.php';
require_once '../app/models/Paginas.php';

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
        $permisos = Session::get('permisos');

        $widgets = Session::get('widgets');
        
        error_log("Usuario en sesión: " . ($area ?: 'No definido'));
        error_log("Nombre en sesión: " . ($name ?: 'No definido'));

        $inventario = Inventario::obtenerEstadisticasPorMarca();

        $this->view('dashboard', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'inventario' => $inventario,
            'permisos' => $permisos,
            'widgets' => $widgets
        ]);
    }

     public static function generarInforme()
    {
        $dateInit = $_POST['fecha_inicio'];
        $dateFin = $_POST['fecha_fin'];
        Session::start();
        try {
            Asistencias::generarExcel($dateInit ,$dateFin);
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        }
    }

    public function pantallaCarga()
    {
        Session::start();

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        if (
            isset($_FILES['archivo_dat']) &&
            $_FILES['archivo_dat']['error'] == 0 &&
            strtolower(pathinfo($_FILES['archivo_dat']['name'], PATHINFO_EXTENSION)) === 'dat'
        ) {
            $archivoTmp = $_FILES['archivo_dat']['tmp_name'];
            $nombreOriginal = basename($_FILES['archivo_dat']['name']);

            $rutaDestino = dirname(__DIR__, 2) . '/public/uploads/' . $nombreOriginal;
            move_uploaded_file($archivoTmp, $rutaDestino);

            Session::set('archivo_subido', $nombreOriginal);
        } else {
            Session::set('error_message', 'No se seleccionó un archivo .dat válido.');
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $message = Session::get('message');
        Session::remove('message');

        $this->view('cargandodat', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'error_message' => $error_message,
            'message' => $message,
            'archivo' => Session::get('archivo_subido')
        ]);
    }

    public static function subirAsistencias()
    {
        Session::start();
        $archivo = Session::get('archivo_subido');
        $name = Session::get('name');
    
        if (!$archivo) {
            Session::set('error_message', 'Archivo no especificado.');
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
            return;
        }
    
        $ruta = dirname(__DIR__, 2) . '/public/uploads/' . $archivo;
        $resultado = Asistencias::procesarAsistencias($ruta);
    
        if ($resultado) {
            Session::set('message', 'Archivo procesado exitosamente.');
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        } else {
            Session::set('error_message', 'Error al procesar el archivo.');
            echo json_encode(['redirect' => BASE_URL . 'dashboard']);
        }
    }
}
?>
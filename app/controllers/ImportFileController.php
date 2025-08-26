<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';
require_once '../app/models/Movimientos.php';

class ImportFileController extends Controller
{
    public function index()
    {
        Session::start();
        $this->verificarPermiso('importfile');

        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $area = Session::get('area');
        $name = Session::get('name');
        $edificio = Session::get('edificio');
        
        $permisos = Session::get('permisos');

        $error_message = Session::get('error_message');
        Session::remove('error_message');

        $message = Session::get('message');
        Session::remove('message');

        $this->view('importfile', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'error_message' => $error_message,
            'message' => $message,
            'permisos' => $permisos
        ]);
    }

    public function cargando() {
        Session::start();
    
        if (!Session::get('usuario')) {
            header("Location: " . BASE_URL . "login");
            exit();
        }
    
        if (isset($_FILES['archivo_excel']) && $_FILES['archivo_excel']['error'] == 0) {
            $archivoTmp = $_FILES['archivo_excel']['tmp_name'];
            $nombreOriginal = basename($_FILES['archivo_excel']['name']);
    
            $rutaDestino = dirname(__DIR__, 2) . '/public/uploads/' . $nombreOriginal;
            move_uploaded_file($archivoTmp, $rutaDestino);
    
            Session::set('archivo_subido', $nombreOriginal);
        } else {
            Session::set('error_message', 'No se seleccionó un archivo válido.');
        }
    
        $rol = Session::get('rol');
        $name = Session::get('name');
    
        $error_message = Session::get('error_message');
        Session::remove('error_message');
    
        $message = Session::get('message');
        Session::remove('message');
    
        $this->view('cargando', [
            'rol' => $rol,
            'name' => $name,
            'error_message' => $error_message,
            'message' => $message,
            'archivo' => Session::get('archivo_subido')
        ]);
    }    
    
    public function procesarArchivo() {
        Session::start();
        $archivo = $_GET['archivo'] ?? '';
        $name = Session::get('name');
    
        if (!$archivo) {
            Session::set('error_message', 'Archivo no especificado.');
            echo json_encode(['redirect' => BASE_URL . 'importfile']);
            return;
        }
    
        $ruta = dirname(__DIR__, 2) . '/public/uploads/' . $archivo;
        $resultado = Inventario::procesarArchivoExcel($ruta);
    
        if ($resultado) {
            Movimientos::carga($name);
            Session::set('message', 'Archivo procesado exitosamente.');
            echo json_encode(['redirect' => BASE_URL . 'importfile']);
        } else {
            Session::set('error_message', 'Error al procesar el archivo.');
            echo json_encode(['redirect' => BASE_URL . 'importfile']);
        }
    }
}
?>
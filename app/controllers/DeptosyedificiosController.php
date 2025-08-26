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

    public function nuevoedificio()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $edificio = $_POST['edificio'];
            $ubicacion = $_POST['ubicacion'];

            if (empty($edificio) || empty($ubicacion)) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }

            $resultado = Edificios::insertarEdificio($edificio, $ubicacion);

            if ($resultado) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            } else {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "deptosyedificios");
            exit();
        }
    }

    public function nuevodepto(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $depto = $_POST['depto'];

            if (empty($depto)) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            
            }
            $resultado = Departamentos::insertarDepartamento($depto);
            if ($resultado) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            } else {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }
        }
    }

    public function nuevarelacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $departamento = $_POST['departamento'];
            $edificio = $_POST['edificio'];

            if (empty($departamento) || empty($edificio)) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }

            $resultado = Edificios::insertarRelacionDeptoEdificio($departamento, $edificio);

            if ($resultado) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            } else {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "deptosyedificios");
            exit();
        }
    }

    public function eliminarelacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_depto_edificio = $_POST['id_depto_edificio'];

            if (empty($id_depto_edificio)) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }

            $resultado = Edificios::eliminarRelacionDeptoEdificio($id_depto_edificio);

            if ($resultado) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            } else {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "deptosyedificios");
            exit();
        }
    }

    public function eliminarDepto()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_depto = $_POST['id_depto'];

            if (empty($id_depto)) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }

            $resultado = Departamentos::eliminarDepartamento($id_depto);

            if ($resultado) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            } else {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "deptosyedificios");
            exit();
        }
    }

    public function eliminarEdificio()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_edificio = $_POST['id_edificio'];

            if (empty($id_edificio)) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }

            $resultado = Edificios::eliminarEdificio($id_edificio);

            if ($resultado) {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            } else {
                header("Location: " . BASE_URL . "deptosyedificios");
                exit();
            }
        } else {
            header("Location: " . BASE_URL . "deptosyedificios");
            exit();
        }
    }
}
?>
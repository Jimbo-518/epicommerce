<?php
require_once '../app/core/Session.php';
require_once '../app/models/Inventario.php';
require_once '../app/models/Movimientos.php';

class BuscadorController extends Controller
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

        // Obtener modelos únicos
        $modelos = Inventario::obtenerModelos();

        $this->view('buscador', [
            'area' => $area,
            'name' => $name,
            'edificio' => $edificio,
            'modelos' => $modelos,
        ]);
    }

    public function buscar()
    {
        Session::start();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $modelo = trim($_POST["model"]);
            $prendas = Inventario::buscarPrendaPorModelo($modelo);

            $this->view('buscador', [
                'prendas' => $prendas,
                'modelo' => $modelo
            ]);
        } else {
            header("Location: " . BASE_URL . "buscador");
        }
    }

    public function descontar()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $marbete = trim($_POST["marbete"]);
            $upc = trim($_POST["upc"]);
            $name = Session::get('name');

            $cantidadDESC = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
            if ($cantidadDESC <= 0) {
                echo "Cantidad inválida.";
                exit;
            }

            $inventario = new Inventario();
            $cantidadData = $inventario->obtenerCantidad($marbete, $upc);

            if ($cantidadData) {
                $cantidad = $cantidadData['Cantidad'];
                if ($cantidad > 0) {
                    $resultado = $inventario->descontarPrenda($marbete, $upc, $cantidadDESC);
                    if ($resultado) {
                        Movimientos::descuento($marbete, $upc, $cantidadDESC, $name);
                        echo "✅ Descuento aplicado correctamente.";
                    } else {
                        echo "❌ Error al actualizar.";
                    }
                } else {
                    echo "⚠️ No se puede descontar más, la cantidad es 0.";
                }
            } else {
                echo "❌ Registro no encontrado.";
            }
        }
    }
}
?>
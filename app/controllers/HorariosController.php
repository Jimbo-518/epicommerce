<?php
// Carga los modelos
require_once __DIR__ . '/../models/Horarios.php';
require_once __DIR__ . '/../models/Edificios.php';
require_once __DIR__ . '/../models/Usuario.php';

class HorariosController
{
    public function gestionar()
    {
        $permisos = [];
        if (isset($_SESSION['usuario_id'])) {
            $permisos = Usuario::getPermisos($_SESSION['usuario_id']);
        }

        $idEdificio = $_GET['edificio'] ?? 1;
        $edificio = Edificios::getById($idEdificio);

        $id_depto_edificio = $_POST['id_depto_edificio']
            ?? $_GET['id_depto_edificio']
            ?? null;

        if (!$id_depto_edificio) {
            header("Location: " . BASE_URL . "deptosyedificios");
            exit;
        }

        $dias = Horario::getDias();
        $horarios = Horario::getHorariosByDeptoEdificio($id_depto_edificio);

        require __DIR__ . '/../views/horariosGestionar.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_depto_edificio = $_POST['id_depto_edificio'] ?? null;
            $id_dia = $_POST['id_dia'] ?? null;
            $entrada = $_POST['entrada'] ?? null;
            $salida = $_POST['salida'] ?? null;

            if ($id_depto_edificio && $id_dia && $entrada && $salida) {
                $rango_inicio = $_POST['rango_inicio'] ?? null;
                $rango_fin = $_POST['rango_fin'] ?? null;

                $rango_descanso = null;
                if ($rango_inicio && $rango_fin) {
                    $rango_descanso = $rango_inicio . '-' . $rango_fin;
                }

                $data = [
                    'id_depto_edificio' => $id_depto_edificio,
                    'id_dia' => $id_dia,
                    'entrada' => $entrada,
                    'tolerancia' => $_POST['tolerancia'] ?? 0,
                    'salida' => $salida,
                    'comida' => $_POST['comida'] ?? null,
                    'descanso' => $_POST['descanso'] ?? null,
                    'rango_descanso' => $rango_descanso,
                ];

                Horario::insertar($data);
            }
        }

        header("Location: " . BASE_URL . "horarios/gestionar?id_depto_edificio=" . urlencode($_POST['id_depto_edificio']));
        exit;
    }

    public function eliminar()
    {
        if (isset($_POST['id_horario'])) {
            Horario::eliminar($_POST['id_horario']);
        }

        header("Location: " . BASE_URL . "horarios/gestionar?id_depto_edificio=" . urlencode($_POST['id_depto_edificio']));
        exit;
    }
}
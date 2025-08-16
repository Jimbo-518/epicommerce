<?php
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Usuario
{
    public static function verificarCredenciales($username, $password)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public static function listaempleados()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados");
        $stmt->execute();
        $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($usuario) {
            return $usuario;
        }
        return false;
    }

    public static function registrar($id, $nombre, $edificio, $area, $vacaciones, $usuario, $password, $creador)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO empleados (id_empleado, nombre, edificio, area, vacaciones, usuario, password, creador) 
            VALUES (:id_empleado, :nombre, :edificio, :area, :vacaciones, :usuario, :password, :creador)
        ");
        return $stmt->execute([
            'id_empleado' => $id,
            'nombre' => $nombre,
            'edificio' => $edificio,
            'area' => $area,
            'vacaciones' => $vacaciones,
            'usuario' => $usuario,
            'password' => $password,
            'creador' => $creador
        ]);
    }

    public static function obtenerUsuarioPorNombre($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :Usuario LIMIT 1");
        $stmt->bindParam(':Usuario', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function pushUserID($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE id_empleado = :id_empleado LIMIT 1");
        $stmt->bindParam(':id_empleado', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function actualizarEmpleado($id, $nombre, $edificio, $area, $vacaciones)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE empleados SET nombre = :nombre, edificio = :edificio, area = :area, vacaciones = :vacaciones WHERE id_empleado = :id");
        return $stmt->execute([
            'nombre' => $nombre,
            'edificio' => $edificio,
            'area' => $area,
            'vacaciones' => $vacaciones,
            'id' => $id
        ]);
    }
    public static function eliminarEmpleado($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM empleados WHERE id_empleado = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function listaEdificios()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT edificio FROM empleados ORDER BY edificio ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function listaAreas()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT area FROM empleados ORDER BY area ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function obtenerAsistenciasPorEmpleado($id_empleado, $fecha_inicio, $fecha_fin)
{
    $db = Database::getConnection();
    $stmt = $db->prepare("
        SELECT fecha, entrada, entrada_comida, salida_comida, salida
        FROM asistencias
        WHERE id_empleado = :id_empleado
          AND fecha BETWEEN :fecha_inicio AND :fecha_fin
        ORDER BY fecha ASC
    ");
    $stmt->execute([
        'id_empleado' => $id_empleado,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
?>
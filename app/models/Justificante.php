<?php
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Justificante
{
    public static function crear($id_empleado, $fecha, $descripcion, $auditor, $evidencia)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO justificantes (id_empleado, fecha, descripcion, auditor, evidencia)
            VALUES (:id_empleado, :fecha, :descripcion, :auditor, :evidencia)
        ");
        return $stmt->execute([
            'id_empleado' => $id_empleado,
            'fecha' => $fecha,
            'descripcion' => $descripcion,
            'auditor' => $auditor,
            'evidencia' => $evidencia
        ]);
    }

    public static function listarTodos()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT j.*, e.nombre
            FROM justificantes j
            LEFT JOIN empleados e ON j.id_empleado = e.id_empleado
            ORDER BY j.id_justificante DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
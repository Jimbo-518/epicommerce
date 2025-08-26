<?php
require_once '../vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Departamentos
{
    public static function listaAreas()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM departamentos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertarDepartamento($depto){
        $db = Database::getConnection();
        $sql = "INSERT INTO departamentos (departamento) VALUES (:depto)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':depto', $depto);
        return $stmt->execute();
    }

    public static function eliminarDepartamento($id_depto){
        $db = Database::getConnection();
        $sql = "DELETE FROM departamentos WHERE id_depto = :id_depto";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_depto', $id_depto);
        return $stmt->execute();
    }
}
?>
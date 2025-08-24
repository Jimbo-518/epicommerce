<?php
require_once '../vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Departamentos
{
    public static function listaAreas()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT departamento FROM departamentos ORDER BY departamento ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>
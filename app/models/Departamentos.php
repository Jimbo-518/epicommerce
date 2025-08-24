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
}
?>
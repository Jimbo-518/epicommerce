<?php
require_once '../vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Edificios
{

    public static function listaEdificios()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM edificios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerIdDeptoEdificio($edificio_nombre, $area_nombre)
    {
        $db = Database::getConnection();

        $sql = "
        SELECT
            de.id_depto_edificio
        FROM
            departamento_edificio de
        JOIN
            departamentos d ON de.id_depto = d.id_depto
        JOIN
            edificios ed ON de.id_edificio = ed.id_edificio
        WHERE
            d.departamento = :area_nombre AND ed.edificio = :edificio_nombre;
    ";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':area_nombre', $area_nombre);
        $stmt->bindParam(':edificio_nombre', $edificio_nombre);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ? $resultado['id_depto_edificio'] : null;
    }

    public static function obtenerRelacionesDeptoEdificio()
    {
        $db = Database::getConnection();
        $sql = "
        SELECT
            de.id_depto_edificio,
            d.departamento,
            ed.edificio
        FROM
            departamento_edificio de
        JOIN
            departamentos d ON de.id_depto = d.id_depto
        JOIN
            edificios ed ON de.id_edificio = ed.id_edificio;
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
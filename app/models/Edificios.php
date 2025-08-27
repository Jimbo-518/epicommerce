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

    public static function insertarEdificio($edificio, $ubicacion)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO edificios (edificio, ubicacion) VALUES (:edificio, :ubicacion)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':edificio', $edificio);
        $stmt->bindParam(':ubicacion', $ubicacion);
        return $stmt->execute();
    }

    public static function insertarRelacionDeptoEdificio($id_depto, $id_edificio)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO departamento_edificio (id_depto, id_edificio) VALUES (:id_depto, :id_edificio)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_depto', $id_depto);
        $stmt->bindParam(':id_edificio', $id_edificio);
        return $stmt->execute();
    }

    public static function eliminarRelacionDeptoEdificio($id_depto_edificio)
    {
        $db = Database::getConnection();
        $sql = "DELETE FROM departamento_edificio WHERE id_depto_edificio = :id_depto_edificio";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_depto_edificio', $id_depto_edificio);
        return $stmt->execute();
    }

    public static function eliminarEdificio($id_edificio)
    {
        $db = Database::getConnection();
        $sql = "DELETE FROM edificios WHERE id_edificio = :id_edificio";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_edificio', $id_edificio);
        return $stmt->execute();
    }

    public static function getById($idEdificio)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM edificios WHERE id_edificio = :id");
        $stmt->execute(['id' => $idEdificio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
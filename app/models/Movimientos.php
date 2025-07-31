<?php
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Movimientos
{

    public static function descuento($marbete, $upc, $cantidadDESC, $name)
    {
        $db = Database::getConnection();
        date_default_timezone_set("America/Mexico_City");

        $fecha = date("F j, Y, g:i a");
        $descripcion = "Se descontaron " . $cantidadDESC . " prenda(s) del modelo coorrespondiente al upc: " . $upc . "";

        $stmt = $db->prepare("
        INSERT INTO cambios (NameUser, Fecha, Marbete, MarbeteAct, Descripcion)
        VALUES (:nameUser, :fecha, :marbete, :marbeteAct, :descripcion)
    ");
        $stmt->execute([
            ':nameUser' => $name,
            ':fecha' => $fecha,
            ':marbete' => $marbete,
            ':marbeteAct' => $marbete,
            ':descripcion' => $descripcion
        ]);
    }

    public static function carga($name)
    {
        $db = Database::getConnection();
        date_default_timezone_set("America/Mexico_City");

        $fecha = date("F j, Y, g:i a");
        $descripcion = "Se cargaron datos a la base";

        $stmt = $db->prepare("
        INSERT INTO cambios (NameUser, Fecha, Marbete, MarbeteAct, Descripcion)
        VALUES (:nameUser, :fecha, :marbete, :marbeteAct, :descripcion)
    ");
        $stmt->execute([
            ':nameUser' => $name,
            ':fecha' => $fecha,
            ':marbete' => $marbete = 0,
            ':marbeteAct' => $marbete,
            ':descripcion' => $descripcion
        ]);

    }

    public static function descarga($name)
    {
        $db = Database::getConnection();
        date_default_timezone_set("America/Mexico_City");

        $fecha = date("F j, Y, g:i a");
        $descripcion = "Se descargaron los datos de la base en formato xls";

        $stmt = $db->prepare("
        INSERT INTO cambios (NameUser, Fecha, Marbete, MarbeteAct, Descripcion)
        VALUES (:nameUser, :fecha, :marbete, :marbeteAct, :descripcion)
    ");
        $stmt->execute([
            ':nameUser' => $name,
            ':fecha' => $fecha,
            ':marbete' => $marbete = 0,
            ':marbeteAct' => $marbete,
            ':descripcion' => $descripcion
        ]);
    }

    public static function deleteBD($name)
    {
        $db = Database::getConnection();
        date_default_timezone_set("America/Mexico_City");

        $fecha = date("F j, Y, g:i a");
        $descripcion = "Se borró la base de datos";

        $stmt = $db->prepare("
        INSERT INTO cambios (NameUser, Fecha, Marbete, MarbeteAct, Descripcion)
        VALUES (:nameUser, :fecha, :marbete, :marbeteAct, :descripcion)
    ");
        $stmt->execute([
            ':nameUser' => $name,
            ':fecha' => $fecha,
            ':marbete' => $marbete = 0,
            ':marbeteAct' => $marbete,
            ':descripcion' => $descripcion
        ]);
    }

    public static function devPrenda($name, $marbete, $upc)
    {
        $db = Database::getConnection();
        date_default_timezone_set("America/Mexico_City");

        $fecha = date("F j, Y, g:i a");
        $descripcion = "Se devolvió 1 pieza del modelo coorrespondiente al upc: " . $upc;

        $stmt = $db->prepare("
        INSERT INTO cambios (NameUser, Fecha, Marbete, MarbeteAct, Descripcion)
        VALUES (:nameUser, :fecha, :marbete, :marbeteAct, :descripcion)
    ");
        $stmt->execute([
            ':nameUser' => $name,
            ':fecha' => $fecha,
            ':marbete' => $marbete,
            ':marbeteAct' => $marbete,
            ':descripcion' => $descripcion
        ]);
    }

    public static function changePrenda($name, $marbete, $upc, $marbeteC, $upcC)
    {
        $db = Database::getConnection();
        date_default_timezone_set("America/Mexico_City");

        $fecha = date("F j, Y, g:i a");
        $descripcion = "Se ha cambiado la pieza: ". $upc . " del marbete: ". $marbete . " por la pieza: ". $upcC . " del marbete: ". $marbeteC;

        $stmt = $db->prepare("
        INSERT INTO cambios (NameUser, Fecha, Marbete, MarbeteAct, Descripcion)
        VALUES (:nameUser, :fecha, :marbete, :marbeteAct, :descripcion)
    ");
        $stmt->execute([
            ':nameUser' => $name,
            ':fecha' => $fecha,
            ':marbete' => $marbete,
            ':marbeteAct' => $marbeteC,
            ':descripcion' => $descripcion
        ]);
    }
}
?>
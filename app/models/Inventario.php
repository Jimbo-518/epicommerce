<?php
require_once '../vendor/autoload.php';
require_once '../config/Database.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Inventario
{
    public static function obtenerEstadisticasPorMarca()
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT 
                p.Marca,
                COUNT(DISTINCT p.Modelo) AS total_modelos,  -- modelos únicos
                SUM(s.Cantidad) AS total_prendas,  -- cantidad de prendas en stock
                SUM(CASE WHEN s.Cantidad < 5 THEN 1 ELSE 0 END) AS por_acabarse
            FROM prenda p
            JOIN stock s ON p.UPC = s.UPC  -- Relación basada en parte para total de prendas
            GROUP BY p.Marca
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerModelos()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT Modelo FROM prenda");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPrendaPorModelo($modelo)
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT UPC, Parte, Descripción, Marca FROM prenda WHERE Parte LIKE :modelo");
        $stmt->execute(['modelo' => "%$modelo%"]);
        $prendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$prendas) {
            return [];
        }

        $upcs = array_column($prendas, 'UPC');
        $placeholders = implode(',', array_fill(0, count($upcs), '?'));

        $stmt = $db->prepare("SELECT Marbete, UPC, Cantidad FROM stock WHERE UPC IN ($placeholders)");
        $stmt->execute($upcs);
        $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($prendas as &$prenda) {
            $prenda['stock'] = [];
            foreach ($stock as $s) {
                if ($s['UPC'] == $prenda['UPC']) {
                    $prenda['stock'][] = $s;
                }
            }
        }

        return $prendas;
    }

    public static function procesarArchivoExcel($ruta)
    {
        $db = Database::getConnection();
        $table_stock = "stock";
        $table_prenda = "prenda";

        $rutaDestino = $ruta;

        try {
            $documento = IOFactory::load($rutaDestino);
            $hojaActual = $documento->getSheet(0);
            $numeroFilas = $hojaActual->getHighestDataRow();

            for ($indiceFila = 2; $indiceFila <= $numeroFilas; $indiceFila++) {
                $marbete = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
                $upc = $hojaActual->getCell(Coordinate::stringFromColumnIndex(2) . $indiceFila)->getValue();
                $cantidad = $hojaActual->getCell(Coordinate::stringFromColumnIndex(3) . $indiceFila)->getValue();
                $parte = $hojaActual->getCell(Coordinate::stringFromColumnIndex(4) . $indiceFila)->getValue();
                $modelo = $hojaActual->getCell(Coordinate::stringFromColumnIndex(5) . $indiceFila)->getValue();
                $descripcion = $hojaActual->getCell(Coordinate::stringFromColumnIndex(6) . $indiceFila)->getValue();
                $marca = $hojaActual->getCell(Coordinate::stringFromColumnIndex(7) . $indiceFila)->getValue();

                $stmt = $db->prepare("SELECT * FROM `$table_prenda` WHERE `UPC` = ?");
                $stmt->execute([$upc]);
                $prenda_existente = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$prenda_existente) {
                    $stmt = $db->prepare("INSERT INTO `$table_prenda` (`UPC`, `Parte`, `Modelo`, `Descripción`, `Marca`) 
                                        VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$upc, $parte, $modelo, $descripcion, $marca]);
                }

                $stmt = $db->prepare("SELECT * FROM `$table_stock` WHERE `UPC` = ? AND `Marbete` = ?");
                $stmt->execute([$upc, $marbete]);
                $registro = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($registro) {
                    $nuevaCantidad = $registro['Cantidad'] + $cantidad;
                    $stmt = $db->prepare("UPDATE `$table_stock` SET `Cantidad` = ? WHERE `UPC` = ? AND `Marbete` = ?");
                    $stmt->execute([$nuevaCantidad, $upc, $marbete]);
                } else {
                    $stmt = $db->prepare("INSERT INTO `$table_stock` (`Marbete`, `UPC`, `Cantidad`) VALUES (?, ?, ?)");
                    $stmt->execute([$marbete, $upc, $cantidad]);
                }
            }

            unlink($rutaDestino);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function descargarBaseExcel($base)
    {
        $db = Database::getConnection();
        $marcas = [];
        switch ($base) {
            case 'joes':
                $marcas = ['JOES', 'Hudson'];
                break;
            case 'true-religion':
                $marcas = ['True Religion'];
                break;
            default:
                throw new Exception("Base no válida.");
        }

        $columnasExcel = ['Marbete', 'UPC', 'Cantidad', 'Parte', 'Modelo', 'Descripción', 'Marca'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("COLEmx")->setTitle("BASE - $base");
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $colIndex = 'A';
        foreach ($columnasExcel as $titulo) {
            $sheet->setCellValue($colIndex . '1', $titulo);
            $colIndex++;
        }
        $sheet->getStyle('A1:' . chr(ord('A') + count($columnasExcel) - 1) . '1')->getFont()->setBold(true);

        $placeholders = implode(',', array_fill(0, count($marcas), '?'));
        $sql = "
        SELECT s.Marbete, s.UPC, s.Cantidad,
               p.Parte, p.Modelo, p.Descripción, p.Marca
        FROM stock s
        INNER JOIN prenda p ON s.UPC = p.UPC
        WHERE p.Marca IN ($placeholders)
    ";
        $stmt = $db->prepare($sql);
        $stmt->execute($marcas);

        // Llenar Excel
        $fila = 2;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $colIndex = 'A';
            foreach (['Marbete', 'UPC', 'Cantidad', 'Parte', 'Modelo', 'Descripción', 'Marca'] as $campo) {
                $sheet->setCellValue($colIndex . $fila, $row[$campo] ?? '');
                $colIndex++;
            }
            $fila++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="BDexport.xls"');
        header('Cache-Control: max-age=0');



        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public function obtenerCantidad($marbete, $upc)
    {
        $db = Database::getConnection();

        $sql = "SELECT Cantidad FROM stock WHERE Marbete = ? AND UPC = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$marbete, $upc]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado;
        } else {
            return false;
        }
    }

    public function descontarPrenda($marbete, $upc, $cantidadDESC): bool
    {
        $db = Database::getConnection();
        $sql = "SELECT Cantidad FROM stock WHERE Marbete = ? AND UPC = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$marbete, $upc]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['Cantidad'] >= $cantidadDESC) {
            $sql_update = "UPDATE stock SET Cantidad = Cantidad - ? WHERE Marbete = ? AND UPC = ?";
            $stmt_update = $db->prepare($sql_update);
            return $stmt_update->execute([$cantidadDESC, $marbete, $upc]);
        }

        return false;
    }

    public static function vaciarBaseDeDatos()
    {
        $db = Database::getConnection();

        $db->beginTransaction();
        try {
            $db->exec("DELETE FROM stock");
            $db->exec("DELETE FROM prenda");
            $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function devolverPrenda($upc, $marbete)
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("UPDATE stock SET Cantidad = Cantidad + 1 WHERE UPC = :upc AND Marbete = :marbete");
        $stmt->bindParam(':upc', $upc);
        $stmt->bindParam(':marbete', $marbete);
        return $stmt->execute();
    }
}
?>
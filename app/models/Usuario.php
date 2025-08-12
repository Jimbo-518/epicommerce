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

    public static function generarReporte($userInfo)
    {
        ob_start();
        $empleado = $userInfo;
        require __DIR__ . '/../views/reportes/empleado.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'Portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
         $dompdf->stream('empleado_' . $empleado['id_empleado'] . '.pdf', ['Attachment' => false]);
    }
/*
    public static function generarExcel(){
        $db = Database::getConnection();

        $columnasExcel = ['EMPLEADO','DÍAS TRABAJADOS','RETARDOS','FALTAS','JUSTIFICANTE'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("COLEmx")->setTitle("Informe de asistencias");
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
    }*/
}
?>
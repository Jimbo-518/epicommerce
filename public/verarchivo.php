<?php
require_once '../app/core/Session.php';
Session::start();

if (!Session::get('usuario')) {
    header("Location: login");
    exit();
}

if (!isset($_GET['file']) || empty($_GET['file'])) {
    die('Archivo no especificado.');
}

$nombreArchivo = basename($_GET['file']);

$proyectoRaiz = dirname(__DIR__);
$rutaArchivo = $proyectoRaiz . '/storage/uploads/justificantes/' . $nombreArchivo;

if (!file_exists($rutaArchivo)) {
    die('Archivo no encontrado.');
}

$ext = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));
$mime = match ($ext) {
    'pdf' => 'application/pdf',
    'jpg', 'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    default => 'application/octet-stream',
};

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
header('Content-Length: ' . filesize($rutaArchivo));
readfile($rutaArchivo);
exit;
<?php
require 'gestor_certificados.php';
session_start();

// Verificar autenticación
//if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
//    header('HTTP/1.0 403 Forbidden');
//    exit;
//}

if (!isset($_GET['id'])) {
    header('HTTP/1.0 400 Bad Request');
    exit;
}

$gestor = new GestorCertificados();
$cert = $gestor->getCertificadoAsociadoPorId($_GET['id']);

if (!$cert) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

$filepath = CERT_DIR . $cert['filename'];

if (!file_exists($filepath)) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

header('Content-Type: application/x-pem-file');
header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
?>
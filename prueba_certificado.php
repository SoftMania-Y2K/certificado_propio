<?php
require 'config.php';

try {
    // Conexión PDO con los datos de tu config
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el certificado del cliente si lo presentó
    $cert = $_SERVER['SSL_CLIENT_CERT'] ?? null;

    if (!$cert) {
        throw new Exception("No se recibió un certificado del cliente.");
    }

    // Calcular el fingerprint SHA-256
    $fingerprint = openssl_x509_fingerprint($cert, 'sha256');

    // Verificar si está en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM certificados_asociados WHERE fingerprint = ?");
    $stmt->execute([$fingerprint]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Certificado no autorizado.");
    }

    $certInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mostrar mensaje de éxito y datos
    echo "<h1>✅ Certificado válido</h1>";
    echo "<p><strong>Nombre asociado:</strong> " . htmlspecialchars($certInfo['nombre_asociado']) . "</p>";
    echo "<p><strong>Navegador:</strong> " . htmlspecialchars($certInfo['navegador']) . "</p>";
    echo "<p><strong>Fingerprint:</strong> " . htmlspecialchars($fingerprint) . "</p>";

} catch (Exception $e) {
    // Mostrar error en caso de que algo falle
    http_response_code(403);
    echo "<h1>❌ Acceso denegado</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gestor_certificados');

// Configuración de certificados
define('CERT_DIR', __DIR__ . '/certificados/');
define('CERT_DAYS_VALID', 365); // 1 año de validez
define('PRIVATE_KEY_BITS', 2048);
define('PRIVATE_KEY_TYPE', OPENSSL_KEYTYPE_RSA);

// Crear directorio si no existe
if (!file_exists(CERT_DIR)) {
    mkdir(CERT_DIR, 0700, true);
}
?>
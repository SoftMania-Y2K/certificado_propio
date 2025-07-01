<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generar'])) {
    try {
        $commonName = $_POST['common_name'] ?? 'localhost';
        $email = $_POST['email'] ?? 'admin@example.com';
        
        // Configuración del certificado
        $dn = [
            "countryName" => "US",
            "stateOrProvinceName" => "California",
            "localityName" => "San Francisco",
            "organizationName" => "Mi Empresa",
            "organizationalUnitName" => "Gestor de Certificados",
            "commonName" => $commonName,
            "emailAddress" => $email
        ];
        
        $config = [
            "config" => "C:/xampp/apache/conf/openssl.cnf",
            "private_key_bits" => PRIVATE_KEY_BITS,
            "private_key_type" => PRIVATE_KEY_TYPE,
            "digest_alg" => "sha256"
        ];
        
        // Generar clave privada
        $privkey = openssl_pkey_new($config);
        if ($privkey === false) {
            throw new Exception('Error al generar clave privada: ' . openssl_error_string());
        }
        
        // Generar CSR
        $csr = openssl_csr_new($dn, $privkey, $config);
        if ($csr === false) {
            throw new Exception('Error al generar CSR: ' . openssl_error_string());
        }
        
        // Autofirmar el certificado
        $cert = openssl_csr_sign($csr, null, $privkey, CERT_DAYS_VALID, $config);
        if ($cert === false) {
            throw new Exception('Error al firmar certificado: ' . openssl_error_string());
        }
        
        // Exportar a formato PEM
        if (!openssl_x509_export($cert, $certout)) {
            throw new Exception('Error al exportar certificado: ' . openssl_error_string());
        }
        
        if (!openssl_pkey_export($privkey, $pkeyout, null, $config)) {
            throw new Exception('Error al exportar clave privada: ' . openssl_error_string());
        }
        
        // Generar nombre de archivo único
        $filename = 'cert_' . md5(uniqid()) . '.pem';
        $filepath = CERT_DIR . $filename;
        
        // Guardar certificado y clave
        if (file_put_contents($filepath, $certout . $pkeyout) === false) {
            throw new Exception('Error al guardar archivo de certificado');
        }
        
        // Guardar en base de datos
        $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare("INSERT INTO certificados_disponibles 
                            (filename, common_name, email, fecha_creacion, fecha_vencimiento) 
                            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $filename,
            $commonName,
            $email,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s', strtotime('+' . CERT_DAYS_VALID . ' days'))
        ]);
        
        // Redireccionar de vuelta a admin.php con mensaje de éxito
        header('Location: admin.php?success=1');
        exit;
    } catch (Exception $e) {
        // Redireccionar con mensaje de error
        header('Location: admin.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Si alguien intenta acceder directamente, redirigir
    header('Location: admin.php');
    exit;
}
<?php
require 'config.php';

class GestorCertificados {
    private $db;
    
    public function __construct() {
        $this->db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    }
    
    // Obtener certificados disponibles
    public function getCertificadosDisponibles() {
        $stmt = $this->db->query("SELECT * FROM certificados_disponibles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener certificados asociados
    public function getCertificadosAsociados() {
        $stmt = $this->db->query("SELECT * FROM certificados_asociados");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCertificadoAsociadoPorId($id) {
    $stmt = $this->db->prepare("SELECT * FROM certificados_asociados WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    
    // Asociar certificado a un navegador/proyecto
   public function asociarCertificado($certId, $nombreAsociado, $navegador) {
    $this->db->beginTransaction();

    try {
        // Obtener certificado desde la tabla de disponibles
        $stmt = $this->db->prepare("SELECT * FROM certificados_disponibles WHERE id = ?");
        $stmt->execute([$certId]);
        $cert = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cert) {
            throw new Exception("Certificado no encontrado");
        }

        // Leer el archivo .crt o .pem para extraer el fingerprint
        $rutaCert = CERT_DIR . $cert['filename'];
        if (!file_exists($rutaCert)) {
            throw new Exception("Archivo de certificado no encontrado: " . $rutaCert);
        }

     $certPEM = file_get_contents(CERT_DIR . $cert['filename']);
    $fingerprint = openssl_x509_fingerprint($certPEM, 'sha256');


        // Mover a tabla de asociados con fingerprint
        $stmt = $this->db->prepare("INSERT INTO certificados_asociados 
            (filename, common_name, email, fecha_creacion, fecha_vencimiento, nombre_asociado, navegador, fecha_asociacion, fingerprint)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->execute([
            $cert['filename'],
            $cert['common_name'],
            $cert['email'],
            $cert['fecha_creacion'],
            $cert['fecha_vencimiento'],
            $nombreAsociado,
            $navegador,
            $fingerprint
        ]);

        // Eliminar de disponibles
        $stmt = $this->db->prepare("DELETE FROM certificados_disponibles WHERE id = ?");
        $stmt->execute([$certId]);

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        throw $e;
    }
}

    
    // Revocar certificado (volver a hacerlo disponible)
    public function revocarCertificado($certId) {
        $this->db->beginTransaction();
        
        try {
            // Obtener certificado asociado
            $stmt = $this->db->prepare("SELECT * FROM certificados_asociados WHERE id = ?");
            $stmt->execute([$certId]);
            $cert = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$cert) {
                throw new Exception("Certificado no encontrado");
            }
            
            // Mover a tabla de disponibles
            $stmt = $this->db->prepare("INSERT INTO certificados_disponibles 
                                      (filename, common_name, email, fecha_creacion, fecha_vencimiento)
                                      VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $cert['filename'],
                $cert['common_name'],
                $cert['email'],
                $cert['fecha_creacion'],
                $cert['fecha_vencimiento']
            ]);
            
            // Eliminar de asociados
            $stmt = $this->db->prepare("DELETE FROM certificados_asociados WHERE id = ?");
            $stmt->execute([$certId]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
?>
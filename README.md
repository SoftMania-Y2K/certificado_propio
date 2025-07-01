# Gestor de Certificados Digitales en PHP

Este proyecto permite **generar, visualizar, descargar y asociar certificados digitales** en formato `.pem` para su uso en navegadores o sistemas que requieran autenticación mediante certificados cliente. 

---

## ⚙️ Funcionalidades principales

- Generación de certificados digitales en formato PEM
- Visualización de certificados disponibles y asociados
- Asociación de certificados a navegadores o usuarios
- Descarga de certificados desde el navegador
- Prueba de funcionamiento de un certificado cliente
- Administración vía panel web (`admin.php`)
- Base de datos MySQL con trazabilidad

---

## 📂 Estructura del proyecto

- `admin.php`: panel principal de administración
- `config.php`: configuración de base de datos
- `generar_certificado.php`: genera un nuevo certificado
- `descargar_cert.php`: permite descargar un certificado por nombre
- `obtener_cert.php`: obtiene y muestra detalles del certificado
- `gestor_certificados.php`: lógica del backend para listar o asociar
- `prueba_certificado.php`: prueba técnica para verificar el uso del certificado
- `CERTIFICADOS 20250609 0859.sql`: script SQL para crear y poblar la base de datos

---

## 🛠️ Requisitos

- Servidor con **PHP 7.4+** y OpenSSL habilitado
- Servidor MySQL/MariaDB (recomendado MariaDB 10.x o superior)
- Apache, Nginx, o cualquier servidor HTTP compatible con PHP
- Acceso vía HTTPS si se van a usar certificados cliente

---

## 🧪 Instalación rápida

1. Cloná el repositorio:
   ```bash
   git clone https://github.com/tu_usuario/gestor_certificados.git
   cd gestor_certificados

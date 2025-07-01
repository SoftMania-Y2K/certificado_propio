<?php
require 'gestor_certificados.php';
session_start();

// Verificar autenticación
//if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
 //   header('Location: login.php');
 //   exit;
//}

$gestor = new GestorCertificados();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['asociar'])) {
        $gestor->asociarCertificado(
            $_POST['cert_id'],
            $_POST['nombre_asociado'],
            $_POST['navegador']
        );
    } elseif (isset($_POST['revocar'])) {
        $gestor->revocarCertificado($_POST['cert_id']);
    }
}

// Obtener listados
$disponibles = $gestor->getCertificadosDisponibles();
$asociados = $gestor->getCertificadosAsociados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Certificados</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { 
            padding: 6px 12px; 
            margin: 2px;
            border-radius: 4px; 
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            border: none;
        }
        .btn-descargar { background-color: #28a745; color: white; }
        .btn-revocar { background-color: #dc3545; color: white; }
        .btn-asociar { background-color: #007bff; color: white; }
        .btn-instalar { background-color: #6f42c1; color: white; }
        .instrucciones { 
            background-color: #f8f9fa; 
            padding: 15px; 
            border-radius: 5px; 
            margin-top: 30px;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-links { margin-bottom: 15px; }
        .tab-link { 
            padding: 10px 15px; 
            background: #e9ecef; 
            border: none; 
            cursor: pointer;
        }
        .tab-link.active { background: #007bff; color: white; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
         /* Tus estilos actuales... */
        .form-generar {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .form-generar label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        .form-generar input {
            padding: 8px;
            margin-bottom: 10px;
            width: 300px;
        }
        .btn-generar {
            background-color: #17a2b8;
            color: white;
        }
        .alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
    </style>
</head>
<body>
    <h1>Gestor de Certificados</h1>
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Certificado generado correctamente</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Error: <?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>
     <!-- Formulario para generar nuevos certificados -->
    <div class="form-generar">
        <h2>Generar Nuevo Certificado</h2>
        <form method="post" action="generar_certificado.php">
            <label for="common_name">Common Name (dominio):</label>
            <input type="text" id="common_name" name="common_name" value="localhost" required><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="admin@example.com" required><br>
            
            <button type="submit" name="generar" class="btn btn-generar">Generar Certificado</button>
        </form>
    </div>
    <h2>Certificados Disponibles</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Common Name</th>
            <th>Email</th>
            <th>Fecha Creación</th>
            <th>Fecha Vencimiento</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($disponibles as $cert): ?>
        <tr>
            <td><?= htmlspecialchars($cert['id']) ?></td>
            <td><?= htmlspecialchars($cert['common_name']) ?></td>
            <td><?= htmlspecialchars($cert['email']) ?></td>
            <td><?= htmlspecialchars($cert['fecha_creacion']) ?></td>
            <td><?= htmlspecialchars($cert['fecha_vencimiento']) ?></td>
            <td>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="cert_id" value="<?= $cert['id'] ?>">
                    <input type="text" name="nombre_asociado" placeholder="Nombre descriptivo" required>
                    <select name="navegador" required>
                        <option value="chrome">Chrome</option>
                        <option value="firefox">Firefox</option>
                        <option value="edge">Edge</option>
                        <option value="safari">Safari</option>
                    </select>
                    <button type="submit" name="asociar" class="btn btn-asociar">Asociar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Certificados Asociados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre Asociado</th>
            <th>Navegador</th>
            <th>Common Name</th>
            <th>Fecha Asociación</th>
            <th>Fecha Vencimiento</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($asociados as $cert): ?>
        <tr>
            <td><?= htmlspecialchars($cert['id']) ?></td>
            <td><?= htmlspecialchars($cert['nombre_asociado']) ?></td>
            <td><?= htmlspecialchars($cert['navegador']) ?></td>
            <td><?= htmlspecialchars($cert['common_name']) ?></td>
            <td><?= htmlspecialchars($cert['fecha_asociacion']) ?></td>
            <td><?= htmlspecialchars($cert['fecha_vencimiento']) ?></td>
            <td>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="cert_id" value="<?= $cert['id'] ?>">
                    <button type="submit" name="revocar" class="btn btn-revocar">Revocar</button>
                </form>
                <a href="descargar_cert.php?id=<?= $cert['id'] ?>" class="btn btn-descargar">Descargar</a>
                <button onclick="instalarCertificado(<?= $cert['id'] ?>)" class="btn btn-instalar">Instalar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Modal para instalación -->
    <div id="instalarModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3 id="modalTitulo">Instalando Certificado</h3>
            <div id="modalContenido">
                <p id="modalMensaje">Preparando la instalación...</p>
                <div id="instruccionesNavegador" style="display:none;">
                    <h4>Instrucciones para completar la instalación:</h4>
                    <ol id="pasosInstalacion"></ol>
                </div>
            </div>
        </div>
    </div>

    <div class="instrucciones">
        <h2>Instalación de Certificados</h2>
        
        <div class="tab-links">
            <button class="tab-link active" onclick="openTab(event, 'chrome')">Chrome/Edge</button>
            <button class="tab-link" onclick="openTab(event, 'firefox')">Firefox</button>
            <button class="tab-link" onclick="openTab(event, 'safari')">Safari</button>
            <button class="tab-link" onclick="openTab(event, 'automatica')">Instalación Automática</button>
        </div>
        
        <div id="chrome" class="tab-content active">
            <h3>Instalar en Chrome/Edge</h3>
            <ol>
                <li>Descarga el certificado haciendo clic en el botón "Descargar"</li>
                <li>Abre Chrome y ve a: <code>chrome://settings/certificates</code></li>
                <li>Haz clic en la pestaña "Autoridades"</li>
                <li>Haz clic en "Importar"</li>
                <li>Selecciona el archivo descargado</li>
                <li>Marca "Confiar en este certificado para identificar sitios web"</li>
                <li>Haz clic en "Aceptar"</li>
            </ol>
        </div>
        
        <div id="firefox" class="tab-content">
            <h3>Instalar en Firefox</h3>
            <ol>
                <li>Descarga el certificado</li>
                <li>Abre Firefox y ve a: <code>about:preferences#privacy</code></li>
                <li>Desplázate hacia abajo y haz clic en "Ver certificados"</li>
                <li>Haz clic en la pestaña "Autoridades"</li>
                <li>Haz clic en "Importar"</li>
                <li>Selecciona el archivo descargado</li>
                <li>Marca las casillas de confianza necesarias</li>
                <li>Haz clic en "Aceptar"</li>
            </ol>
        </div>
        
        <div id="safari" class="tab-content">
            <h3>Instalar en Safari</h3>
            <ol>
                <li>Descarga el certificado</li>
                <li>Abre el archivo descargado (se abrirá en Acceso a Llaveros)</li>
                <li>Selecciona el llavero "Sistema"</li>
                <li>Haz clic en "Añadir"</li>
                <li>Introduce tu contraseña de administrador si se solicita</li>
                <li>Ve a Preferencias del Sistema > Llavero de Acceso</li>
                <li>Localiza tu certificado y haz doble clic</li>
                <li>En "Confiar", selecciona "Confiar siempre"</li>
            </ol>
        </div>
        
        <div id="automatica" class="tab-content">
            <h3>Instalación Automática</h3>
            <p>Para la instalación automática:</p>
            <ol>
                <li>Haz clic en el botón "Instalar" junto al certificado que deseas instalar</li>
                <li>El sistema intentará instalar el certificado automáticamente</li>
                <li>Si falla la instalación automática, sigue las instrucciones manuales para tu navegador</li>
                <li>Es posible que necesites confirmar la instalación en un cuadro de diálogo del navegador</li>
            </ol>
            <p><strong>Nota:</strong> La instalación automática puede no funcionar en todos los navegadores o puede requerir configuración especial.</p>
        </div>
    </div>

    <script>
        // Función para abrir pestañas
        function openTab(evt, tabName) {
            var tabcontents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabcontents.length; i++) {
                tabcontents[i].classList.remove("active");
            }
            
            var tablinks = document.getElementsByClassName("tab-link");
            for (var i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        // Funciones para el modal
        var modal = document.getElementById("instalarModal");
        
        function cerrarModal() {
            modal.style.display = "none";
        }
        
        // Función para instalar certificado
        function instalarCertificado(certId) {
            modal.style.display = "block";
            document.getElementById("modalTitulo").textContent = "Instalando Certificado";
            document.getElementById("modalMensaje").textContent = "Preparando la instalación...";
            document.getElementById("instruccionesNavegador").style.display = "none";
            
            // Obtener el certificado
            fetch('obtener_cert.php?id=' + certId)
                .then(response => {
                    if (!response.ok) throw new Error('Error al obtener certificado');
                    return response.text();
                })
                .then(pem => {
                    document.getElementById("modalMensaje").textContent = "Intentando instalación automática...";
                    
                    // Intentar instalación automática
                    if (window.crypto && crypto.subtle && window.installCertificate) {
                        // Implementación hipotética - no existe en navegadores estándar
                        try {
                            window.installCertificate(pem);
                            document.getElementById("modalMensaje").textContent = "Instalación completada con éxito!";
                        } catch (e) {
                            mostrarInstruccionesManuales();
                        }
                    } else {
                        mostrarInstruccionesManuales();
                    }
                })
                .catch(error => {
                    document.getElementById("modalMensaje").textContent = "Error: " + error.message;
                    mostrarInstruccionesManuales();
                });
        }
        
        function mostrarInstruccionesManuales() {
            document.getElementById("modalMensaje").textContent = "La instalación automática no está disponible en este navegador.";
            document.getElementById("instruccionesNavegador").style.display = "block";
            
            var pasos = document.getElementById("pasosInstalacion");
            pasos.innerHTML = "";
            
            // Detectar navegador
            var navegador = "desconocido";
            var userAgent = navigator.userAgent;
            
            if (userAgent.indexOf("Chrome") > -1) {
                navegador = "chrome";
            } else if (userAgent.indexOf("Firefox") > -1) {
                navegador = "firefox";
            } else if (userAgent.indexOf("Safari") > -1) {
                navegador = "safari";
            } else if (userAgent.indexOf("Edge") > -1 || userAgent.indexOf("Edg") > -1) {
                navegador = "edge";
            }
            
            // Mostrar instrucciones según navegador
            var instrucciones = [];
            
            if (navegador === "chrome" || navegador === "edge") {
                instrucciones = [
                    "Descarga el certificado haciendo clic en 'Descargar'",
                    `Abre ${navegador === "chrome" ? "Chrome" : "Edge"} y ve a: chrome://settings/certificates`,
                    "Haz clic en la pestaña 'Autoridades'",
                    "Haz clic en 'Importar'",
                    "Selecciona el archivo descargado",
                    "Marca 'Confiar en este certificado para identificar sitios web'",
                    "Haz clic en 'Aceptar'"
                ];
            } else if (navegador === "firefox") {
                instrucciones = [
                    "Descarga el certificado",
                    "Abre Firefox y ve a: about:preferences#privacy",
                    "Desplázate hacia abajo y haz clic en 'Ver certificados'",
                    "Haz clic en la pestaña 'Autoridades'",
                    "Haz clic en 'Importar'",
                    "Selecciona el archivo descargado",
                    "Marca las casillas de confianza necesarias",
                    "Haz clic en 'Aceptar'"
                ];
            } else if (navegador === "safari") {
                instrucciones = [
                    "Descarga el certificado",
                    "Abre el archivo descargado (se abrirá en Acceso a Llaveros)",
                    "Selecciona el llavero 'Sistema'",
                    "Haz clic en 'Añadir'",
                    "Introduce tu contraseña de administrador si se solicita",
                    "Ve a Preferencias del Sistema > Llavero de Acceso",
                    "Localiza tu certificado y haz doble clic",
                    "En 'Confiar', selecciona 'Confiar siempre'"
                ];
            } else {
                instrucciones = [
                    "Descarga el certificado haciendo clic en 'Descargar'",
                    "Consulta la documentación de tu navegador para instalar certificados manualmente"
                ];
            }
            
            instrucciones.forEach(paso => {
                var li = document.createElement("li");
                li.textContent = paso;
                pasos.appendChild(li);
            });
        }
        
        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>
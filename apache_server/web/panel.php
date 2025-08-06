<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$usuario = $_GET['usuario'] ?? '';
$mensaje = $_GET['mensaje'] ?? '';
$emailMensaje = '';

if (!$usuario) {
    die("Usuario no especificado.");
}

$conn = new mysqli("mysql_server", "root", "toor", "insegura");
if ($conn->connect_error) {
    die("❌ Error de conexión a la base de datos.");
}

// Comprobar si usuario existe
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<h2 style='color:red; text-align:center;'>❌ Acceso denegado: el usuario <strong>" . htmlspecialchars($usuario) . "</strong> no existe.</h2><p style='text-align:center;'><a href='index.php'>Volver al login</a></p>");
}

$userData = $result->fetch_assoc();

$perfilGuardado = false;
$comentarioGuardado = false;

// Procesar formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_tipo = $_POST['form_tipo'] ?? '';

    if ($form_tipo === 'perfil' && isset($_POST['nombre'])) {
        $nuevoNombre = trim($_POST['nombre']);
        if ($nuevoNombre !== '') {
            $updateStmt = $conn->prepare("UPDATE usuarios SET nombre = ? WHERE usuario = ?");
            $updateStmt->bind_param("ss", $nuevoNombre, $usuario);
            if ($updateStmt->execute()) {
                $perfilGuardado = true;
                $userData['nombre'] = $nuevoNombre;
            } else {
                $mensaje = "Error al guardar perfil: " . $updateStmt->error;
            }
            $updateStmt->close();
        }
    } elseif ($form_tipo === 'comentario' && !empty(trim($_POST['comentario']))) {
        $comentario = $_POST['comentario'];
        $insertStmt = $conn->prepare("INSERT INTO comentarios (usuario, comentario) VALUES (?, ?)");
        $insertStmt->bind_param("ss", $usuario, $comentario);
        if ($insertStmt->execute()) {
            $comentarioGuardado = true;
        } else {
            $mensaje = "Error al guardar comentario: " . $insertStmt->error;
        }
        $insertStmt->close();
    } elseif (isset($_POST['enviar_email'])) {
        // Envío de email
        $to = trim($_POST['to'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $messageBody = trim($_POST['message'] ?? '');
        $from = "no-reply@masterciberseguridad.com";

        // Validación simple para evitar header injection
        if (filter_var($to, FILTER_VALIDATE_EMAIL) &&
            !preg_match("/[\r\n]/", $to) &&
            !preg_match("/[\r\n]/", $subject) &&
            $messageBody !== ''
        ) {
            $headers = "From: $from\r\nReply-To: $from\r\n";

            if (mail($to, $subject, $messageBody, $headers)) {
                $emailMensaje = "✔️ Email enviado correctamente a " . htmlspecialchars($to);
            } else {
                $emailMensaje = "❌ Error al enviar el email.";
                error_log("Error al enviar email a $to con asunto '$subject'");
            }
        } else {
            $emailMensaje = "❌ Datos de email inválidos o maliciosos detectados.";
        }
    }
}

// Obtener comentarios guardados (SIN ESCAPE)
$comentarios = [];
$comentariosResult = $conn->query("SELECT usuario, comentario, fecha FROM comentarios ORDER BY fecha DESC LIMIT 10");
if ($comentariosResult) {
    while ($row = $comentariosResult->fetch_assoc()) {
        $comentarios[] = "<strong>" . htmlspecialchars($row['usuario']) . "</strong> (" . htmlspecialchars($row['fecha']) . "): " . nl2br($row['comentario']);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel - Insegura Corp</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef1f5;
      margin: 0;
    }
    .header {
      background: #222;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .main {
      padding: 30px;
      max-width: 900px;
      margin: auto;
    }
    .card {
      background: white;
      padding: 25px;
      border-radius: 8px;
      margin-bottom: 25px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 { margin-top: 0; color: #333; }
    input, textarea, button {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    button {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    .mensaje {
      background: #e9ecef;
      padding: 10px;
      border-left: 5px solid #888;
      margin-bottom: 20px;
    }
    .logout {
      text-align: center;
      margin-top: 30px;
    }
    .logout a {
      color: #dc3545;
      text-decoration: none;
    }
    .comentario {
      background: #f9f9f9;
      padding: 10px;
      border-left: 3px solid #007bff;
      margin-top: 10px;
      white-space: pre-wrap;
    }
    .card.admin-flag {
      border-left: 5px solid crimson;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Bienvenido al panel, <?php echo htmlspecialchars($usuario); ?> <?php echo !empty($userData['nombre']) ? '(' . htmlspecialchars($userData['nombre']) . ')' : ''; ?></h1>
</div>

<div class="main">

  <?php if ($usuario === 'admin'): ?>
    <div class="card admin-flag">
      <h2>🎉 ¡Bienvenido Admin!</h2>
      <p><strong>Flag:</strong> CTF{admin_panel_access_granted}</p>
      <p>Has accedido como administrador. Aquí podrías tener privilegios especiales o paneles adicionales.</p>
    </div>
  <?php endif; ?>

  <?php if (!empty($mensaje)): ?>
    <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
  <?php endif; ?>

  <div class="card">
    <h2>📄 Editar perfil (CSRF vulnerable)</h2>
    <form method="POST" action="panel.php?usuario=<?php echo urlencode($usuario); ?>">
      <input type="hidden" name="form_tipo" value="perfil" />
      <label>Nombre para mostrar:</label>
      <input type="text" name="nombre" placeholder="Nuevo nombre" value="<?php echo htmlspecialchars($userData['nombre'] ?? ''); ?>" />
      <button type="submit">Guardar cambios</button>
    </form>
    <?php if ($perfilGuardado): ?>
      <p style="color:green; margin-top:10px;">✔️ Cambios guardados.</p>
    <?php endif; ?>
  </div>

  <div class="card">
    <h2>💬 Comentarios (XSS persistente)</h2>
    <form method="POST" action="panel.php?usuario=<?php echo urlencode($usuario); ?>">
      <input type="hidden" name="form_tipo" value="comentario" />
      <label>Deja un comentario público:</label>
      <textarea name="comentario" rows="3" placeholder="Escribe aquí..."></textarea>
      <button type="submit">Enviar</button>
    </form>
    <?php if ($comentarioGuardado): ?>
      <p style="color:green; margin-top:10px;">✔️ Comentario enviado.</p>
    <?php endif; ?>

    <div class="comentarios">
      <h3>Últimos comentarios:</h3>
      <?php if (count($comentarios) === 0): ?>
        <p>No hay comentarios aún.</p>
      <?php else: ?>
        <?php foreach ($comentarios as $c): ?>
          <div class="comentario"><?php echo $c; ?></div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <h2>📊 Estadísticas personales</h2>
    <ul>
      <li>Comentarios escritos: <?php echo count($comentarios); ?></li>
      <li>Sesiones iniciadas: <?php echo rand(1, 5); ?></li>
      <li>Último acceso: <?php echo date('Y-m-d H:i:s'); ?></li>
    </ul>
  </div>

  <div class="card">
    <h2>🧪 Pruebas de vulnerabilidad</h2>
    <p>Prueba XSS reflejado:</p>
    <code>panel.php?usuario=admin&mensaje=&lt;script&gt;alert(1)&lt;/script&gt;</code>
    <p>Prueba XSS almacenado enviando <code>&lt;script&gt;alert(1)&lt;/script&gt;</code> como comentario.</p>
  </div>

  <!-- Formulario para enviar email -->
  <div class="card">
    <h2>Enviar Email (vulnerable a header injection y sin validación)</h2>
    <?php if ($emailMensaje): ?>
      <p style="color: <?php echo (str_starts_with($emailMensaje, '✔️') ? 'green' : 'red'); ?>;">
        <?php echo htmlspecialchars($emailMensaje); ?>
      </p>
    <?php endif; ?>
    <form method="POST" action="panel.php?usuario=<?php echo urlencode($usuario); ?>">
      <input type="hidden" name="enviar_email" value="1" />
      <label>Para:</label>
      <input type="email" name="to" required>
      <label>Asunto:</label>
      <input type="text" name="subject" required>
      <label>Mensaje:</label>
      <textarea name="message" rows="5" required></textarea>
      <button type="submit">Enviar Email</button>
    </form>
  </div>

  <div class="logout">
    <a href="index.php">Cerrar sesión</a>
  </div>

</div>

</body>
</html>

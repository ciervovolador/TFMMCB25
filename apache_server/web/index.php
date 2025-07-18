<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"] ?? '';
    $clave = $_POST["clave"] ?? '';

    // Conexión vulnerable (sin sanitización)
    $conn = new mysqli("mysql_server", "root", "toor", "insegura");


    if ($conn->connect_error) {
        $mensaje = "❌ Error de conexión a la base de datos.";
    } else {
        // Consulta SQL vulnerable (SQLi)
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            header("Location: panel.php?usuario=$usuario");
		exit;

        } else {
            $mensaje = "❌ Usuario o contraseña incorrectos.";
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Insegura Corp - Portal</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 40px; }
    .container {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      max-width: 500px;
      margin: auto;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
    h1 { color: #333; }
    input, button {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      background: #007bff;
      color: white;
      border: none;
    }
    button:hover {
      background: #0056b3;
    }
    .mensaje {
      margin-top: 15px;
      padding: 10px;
      background: #f9f9f9;
      border-left: 4px solid #ccc;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Insegura Corp - Login</h1>

    <form method="POST">
      <input type="text" name="usuario" placeholder="Usuario">
      <input type="password" name="clave" placeholder="Contraseña">
      <button type="submit">Iniciar sesión</button>
<p style="text-align:center; margin-top:15px;">
  ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
</p>
    </form>

    <?php if (!empty($mensaje)): ?>
      <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- Comentarios sospechosos -->
    <!-- admin_panel.php.bak contiene usuarios -->
    <!-- Flag parcial: CTF{sqli_ -->

    <p style="margin-top:20px; font-size:12px; color:gray;">¿Olvidaste tu contraseña? Contacta con soporte@insegura.corp</p>
  </div>
</body>
</html>

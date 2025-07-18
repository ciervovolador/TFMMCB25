<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre_completo"] ?? '';
    $email = $_POST["email"] ?? '';
    $usuario = $_POST["usuario"] ?? '';
    $clave = $_POST["clave"] ?? '';

    // Conexión vulnerable
    $conn = new mysqli("mysql_server", "root", "toor", "insegura");

    if ($conn->connect_error) {
        $mensaje = "❌ Error de conexión a la base de datos.";
    } else {
        // La tabla sigue siendo 'usuarios' con solo usuario y clave
        // Guardamos solo lo que permite el esquema original
        $sql = "INSERT INTO usuarios (usuario, clave) VALUES ('$usuario', '$clave')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "✅ Usuario registrado correctamente.";
        } else {
            $mensaje = "❌ Error: " . $conn->error;
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de nuevo usuario</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #ffffff;
      padding: 40px;
    }
    .container {
      background: #f4fdf4;
      padding: 30px;
      border-radius: 12px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,128,0,0.15);
    }
    h1 {
      color: #2e7d32;
      text-align: center;
    }
    input, button {
      width: 100%;
      padding: 10px;
      margin-top: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      background: #4CAF50;
      color: white;
      font-weight: bold;
    }
    button:hover {
      background: #388e3c;
    }
    .mensaje {
      margin-top: 20px;
      padding: 10px;
      background: #eef;
      border-left: 4px solid #88f;
    }
    .back {
      margin-top: 25px;
      text-align: center;
      font-size: 14px;
    }
    .back a {
      color: #007bff;
      text-decoration: none;
    }
    .back a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Formulario de Registro</h1>

    <form method="POST">
      <input type="text" name="nombre_completo" placeholder="Nombre completo">
      <input type="email" name="email" placeholder="Correo electrónico">
      <input type="text" name="usuario" placeholder="Nombre de usuario">
      <input type="password" name="clave" placeholder="Contraseña">
      <button type="submit">Crear cuenta</button>
    </form>

    <?php if (!empty($mensaje)): ?>
      <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <div class="back">
      ¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a>
    </div>
  </div>
</body>
</html>

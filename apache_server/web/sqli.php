<?php
$host = "172.20.0.12"; // IP de mysql_server
$user = "root";
$password = "toor";
$database = "vuln_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['user'])) {
    $user_input = $_GET['user'];
    $query = "SELECT * FROM users WHERE username = '$user_input'";
    echo "<p>Consulta ejecutada: $query</p>";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID: {$row['id']}, Usuario: {$row['username']}, Clave: {$row['password']}</p>";
        }
    } else {
        echo "<p>No se encontraron resultados.</p>";
    }
}
?>

<form method="GET">
  <input type="text" name="user" placeholder="Nombre de usuario">
  <button type="submit">Buscar</button>
</form>

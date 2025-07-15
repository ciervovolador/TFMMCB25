<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = $_POST["to"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // 🔥 Inseguro: No hay validación, cabeceras pueden ser inyectadas
    mail($to, $subject, $message);

    echo "<p>Correo enviado (o eso dice PHP) a $to</p>";
}
?>

<form method="post">
    <label>Para: <input type="text" name="to"></label><br>
    <label>Asunto: <input type="text" name="subject"></label><br>
    <label>Mensaje:</label><br>
    <textarea name="message" rows="5" cols="40"></textarea><br>
    <input type="submit" value="Enviar correo">
</form>

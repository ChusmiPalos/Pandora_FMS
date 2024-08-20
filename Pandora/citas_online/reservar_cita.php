<?php
include 'conexion.php';

// Recibimos todas las "piezas" del formulario
$nombre = $_POST['nombre'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$tipo_cita = $_POST['tipoCita'];

// Verificar si el paciente ya existe
$query = $conn->prepare("SELECT id FROM pacientes WHERE dni = ?");
$query->bind_param('s', $dni);
$query->execute();
$query->store_result();

// Si ya existe, habrá más de 0 filas
if ($query->num_rows > 0) {
    // Nos "guardamos" el id del paciente
    $query->bind_result($paciente_id);
    $query->fetch();
} else {
    $query->close(); // Cerrar la consulta anterior

    // Si el paciente, no existía, lo insertamos
    $query = $conn->prepare("INSERT INTO pacientes (nombre, dni, telefono, email) VALUES (?, ?, ?, ?)");
    $query->bind_param('ssss', $nombre, $dni, $telefono, $email);
    $query->execute();
    // Aquí también nos "guardamos" el id que nos acaba de generar para este nuevo paciente
    $paciente_id = $query->insert_id;
}
// Cerrar la consulta de pacientes (ya sea por paciente_existente, o por paciente nuevo, aquí hemos llegado con una consulta abierta)
$query->close();

// Buscar la primera hora disponible
$query = $conn->prepare("SELECT fecha, hora FROM horarios WHERE disponible = 1 ORDER BY fecha, hora LIMIT 1");
$query->execute();
// nos "guardamos" la fecha y hora que nos ha asignado
$query->bind_result($fecha, $hora);
$query->fetch();

// Verificar si se encontró una hora disponible
if ($fecha === null || $hora === null) {
    echo "No hay horas disponibles en este momento.";
    $query->close();
    $conn->close();
    exit();
}
$query->close(); // Cerrar la consulta anterior

// Si hemos superado el "if" anterior es porque sí tenemos fecha y hora, debemos guardarla en la tabla "citas"
// Insertar la cita
$query = $conn->prepare("INSERT INTO citas (paciente_id, tipo_cita, fecha, hora) VALUES (?, ?, ?, ?)");
$query->bind_param('isss', $paciente_id, $tipo_cita, $fecha, $hora);
$query->execute();
$query->close(); // Cerrar la consulta anterior

// Marcar la hora como ocupada
$query = $conn->prepare("UPDATE horarios SET disponible = 0 WHERE fecha = ? AND hora = ?");
$query->bind_param('ss', $fecha, $hora);
$query->execute();

// Enviar el email de confirmación (opcional)
$subject = "Confirmación de Cita";
$message = "Su cita ha sido programada para el día $fecha a las $hora. Tipo de cita: $tipo_cita.";
$headers = "From: no-reply@clinica.com";

// mail($email, $subject, $message, $headers);

// Captura de errores y simulación del envío de correo
if (mail($email, $subject, $message, $headers)) {
    $message_to_display = "Cita reservada con éxito. Se ha enviado un email de confirmación.";
} else {

    // Simulación del mensaje de correo en lugar de enviar realmente
    $message_to_display = "Cita reservada con éxito.<br>Con PHPMailer se habría generado un email a {$email}, con el asunto '{$subject}' y el mensaje '{$message}'.<br>Este email se habría enviado desde: {$headers}.";
}


$query->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .message {
            margin-bottom: 20px;
        }

        .button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="message">
            <?php echo $message_to_display; ?>
        </div>
        <a href="index.php" class="button">Volver a reservar otra cita</a>
    </div>
</body>

</html>
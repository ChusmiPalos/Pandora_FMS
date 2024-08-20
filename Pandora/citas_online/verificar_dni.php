<?php
include 'conexion.php';

$dni = $_POST['dni'];

$query = $conn->prepare("SELECT id FROM pacientes WHERE dni = ?");
$query->bind_param('s', $dni);
$query->execute();
$query->store_result();

if($query->num_rows > 0){
    echo 'existe';
} else {
    echo 'no_existe';
}

$query->close();
$conn->close();
?>

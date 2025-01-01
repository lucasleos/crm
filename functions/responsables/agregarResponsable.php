<?php
include("../../config/conexion.php");

$nombre = $_GET['nombre'];
$dni = $_GET['dni'];
$correo = $_GET['correo'];
$telefono = $_GET['telefono'];

$query = "INSERT INTO responsables (nombre, dni, correo, telefono) VALUES (?, ?, ?, ?)";
$statement = $conexion->prepare($query);
$statement->bind_param("ssss", $nombre, $dni, $correo, $telefono);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

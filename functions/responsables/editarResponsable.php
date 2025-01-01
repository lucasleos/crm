<?php
include("../../config/conexion.php");

$id = $_GET['id'];
$nombre = $_GET['nombre'];
$dni = $_GET['dni'];
$correo = $_GET['correo'];
$telefono = $_GET['telefono'];

$query = "UPDATE responsables SET nombre = ?, dni = ?, correo = ?, telefono = ? WHERE id = ?";
$statement = $conexion->prepare($query);
$statement->bind_param("ssssi", $nombre, $dni, $correo,  $telefono, $id);

if (!$statement || $statement->bind_param("ssssi", $nombre, $dni, $correo, $telefono, $id) === false || $statement->execute() === false) {
    echo "Error: (" . $conexion->errno . ") " . $conexion->error;
} else {
    echo 'S';
}

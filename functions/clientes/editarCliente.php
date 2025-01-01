<?php
include("../../config/conexion.php");

$id = $_GET['id'];
$razonSocial = $_GET['razonSocial'];
$correo = $_GET['correo'];
$localidad = $_GET['localidad'];
$cuit = $_GET['cuit'];
$telefono = $_GET['telefono'];
$codigoPostal = $_GET['codigoPostal'];
$provincia = $_GET['provincia'];

$query = "UPDATE clientes SET razon_social = ?, correo = ?, localidad = ?, cuit = ?, telefono = ?, codigo_postal = ?, provincia = ? WHERE id = ?";
$statement = $conexion->prepare($query);
$statement->bind_param("sssssssi", $razonSocial, $correo, $localidad, $cuit, $telefono, $codigoPostal, $provincia, $id);

if (!$statement || $statement->bind_param("sssssssi", $razonSocial, $correo, $localidad, $cuit, $telefono, $codigoPostal, $provincia, $id) === false || $statement->execute() === false) {
    echo "Error: (" . $conexion->errno . ") " . $conexion->error;
} else {
    echo 'S';
}

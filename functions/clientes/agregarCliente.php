<?php
include("../../config/conexion.php");

$razonSocial = $_GET['razonSocial'];
$correo = $_GET['correo'];
$localidad = $_GET['localidad'];
$cuit = $_GET['cuit'];
$telefono = $_GET['telefono'];
$codigoPostal = $_GET['codigoPostal'];
$provincia = $_GET['provincia'];

$query = "INSERT INTO clientes (razon_social, correo, localidad, cuit, telefono, codigo_postal, provincia) VALUES (?, ?, ?, ?, ?, ?, ?)";
$statement = $conexion->prepare($query);
$statement->bind_param("sssssss", $razonSocial, $correo, $localidad, $cuit, $telefono, $codigoPostal, $provincia);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

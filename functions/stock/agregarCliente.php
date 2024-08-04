<?php
include("../../config/conexion.php");

// Recolectar datos del cliente desde la solicitud GET
$razonSocial = $_GET['razonSocial'];
$correo = $_GET['correo'];
$localidad = $_GET['localidad'];
$cuit = $_GET['cuit'];
$telefono = $_GET['telefono'];
$codigoPostal = $_GET['codigoPostal'];
$provincia = $_GET['provincia'];

// Insertar cliente en la base de datos
$query = "INSERT INTO clientes (razon_social, correo, localidad, cuit, telefono, codigo_postal, provincia) VALUES (?, ?, ?, ?, ?, ?, ?)";
$statement = $conexion->prepare($query);
$statement->bind_param("sssssss", $razonSocial, $correo, $localidad, $cuit, $telefono, $codigoPostal, $provincia);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

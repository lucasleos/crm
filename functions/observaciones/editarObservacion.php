<?php
include("../../config/conexion.php");

$observacion_id = $_GET['observacion_id'];
$nueva_observacion = $_GET['nueva_observacion'];

$query = "UPDATE observaciones SET observacion = ? WHERE id = ?";
$statement = $conexion->prepare($query);
$statement->bind_param("si", $nueva_observacion, $observacion_id);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

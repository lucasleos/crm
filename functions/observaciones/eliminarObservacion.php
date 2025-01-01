<?php
include("../../config/conexion.php");

$observacion_id = $_GET['observacion_id'];

$query = "DELETE FROM observaciones WHERE id = ?";
$statement = $conexion->prepare($query);
$statement->bind_param("i", $observacion_id);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

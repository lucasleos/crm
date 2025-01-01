<?php
include("../../config/conexion.php");

date_default_timezone_set('America/Argentina/Buenos_Aires');

$observacion = $_GET['observacion'];
$presupuesto_id = $_GET['presupuesto_id'];
$fecha = date('Y-m-d H:i:s');

$query = "INSERT INTO observaciones (observacion, fecha, presupuesto_id) VALUES (?, ?, ?)";
$statement = $conexion->prepare($query);
$statement->bind_param("ssi", $observacion, $fecha, $presupuesto_id);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

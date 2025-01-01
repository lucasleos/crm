<?php
include("../../config/conexion.php");

$id = $_GET['presupuestoId'];
$nroPresupuesto = $_GET['nroPresupuesto'];
$nroExpediente = $_GET['nroExpediente'];
$fechaInicio = !empty($_GET['fechaInicio']) ? $_GET['fechaInicio'] : NULL;
$fechaVencimiento = !empty($_GET['fechaVencimiento']) ? $_GET['fechaVencimiento'] : NULL;
$fechaAprobacion = !empty($_GET['fechaAprobacion']) ? $_GET['fechaAprobacion'] : NULL;
$fechaEnvio = !empty($_GET['fechaEnvio']) ? $_GET['fechaEnvio'] : NULL;
$fechaGanado = !empty($_GET['fechaGanado']) ? $_GET['fechaGanado'] : NULL;
$productoServicio = $_GET['productoServicio'];
$descripcion = $_GET['descripcion'];
$monto = $_GET['monto'];
$moneda = $_GET['moneda'];
$estado = $_GET['estado'];
$cotizacionDolar = $_GET['cotizacionDolar'];
$clienteId = $_GET['clienteId'];
$responsableId = $_GET['responsableId'];


$query = "UPDATE presupuestos SET 
    nro_presupuesto = ?, 
    nro_expediente = ?, 
    fecha_inicio = ?, 
    fecha_vencimiento = ?, 
    fecha_aprobacion = ?, 
    producto_servicio = ?, 
    descripcion = ?, 
    fecha_envio = ?, 
    monto = ?, 
    moneda = ?, 
    fecha_ganado = ?, 
    estado = ?, 
    cotizacion_dolar = ?, 
    cliente_id = ? ,
    responsable_id = ?
    WHERE id = ?";

$statement = $conexion->prepare($query);
$statement->bind_param(
    "sssssssssssssiii",
    $nroPresupuesto,
    $nroExpediente,
    $fechaInicio,
    $fechaVencimiento,
    $fechaAprobacion,
    $productoServicio,
    $descripcion,
    $fechaEnvio,
    $monto,
    $moneda,
    $fechaGanado,
    $estado,
    $cotizacionDolar,
    $clienteId,
    $responsableId,
    $id
);

if (!$statement || $statement->execute() === false) {
    echo "Error: (" . $conexion->errno . ") " . $conexion->error;
} else {
    echo 'S';
}

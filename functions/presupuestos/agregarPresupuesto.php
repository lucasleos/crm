<?php
include("../../config/conexion.php");

$nroPresupuesto = $_GET['nroPresupuesto'];
$nroExpediente = $_GET['nroExpediente'];
$fechaInicio = empty($_GET['fechaInicio']) ? null : $_GET['fechaInicio'];
$fechaVencimiento = empty($_GET['fechaVencimiento']) ? null : $_GET['fechaVencimiento'];
$fechaAprobacion = empty($_GET['fechaAprobacion']) ? null : $_GET['fechaAprobacion'];
$productoServicio = $_GET['productoServicio'];
$descripcion = $_GET['descripcion'];
$fechaEnvio = empty($_GET['fechaEnvio']) ? null : $_GET['fechaEnvio'];
$monto = $_GET['monto'];
$moneda = $_GET['moneda'];
$fechaGanado = empty($_GET['fechaGanado']) ? null : $_GET['fechaGanado'];
$estado = $_GET['estado'];
$responsableId = $_GET['responsableId'];
$cotizacionDolar = $_GET['cotizacionDolar'];
$clienteId = $_GET['clienteId'];

$query = "INSERT INTO presupuestos (nro_presupuesto, nro_expediente, fecha_inicio, fecha_vencimiento, fecha_aprobacion, producto_servicio, descripcion, fecha_envio, monto, moneda, fecha_ganado, estado, responsable_id, cotizacion_dolar, cliente_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$statement = $conexion->prepare($query);

$statement->bind_param(
    "ssssssssssssssi",
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
    $responsableId,
    $cotizacionDolar,
    $clienteId
);

if ($statement->execute()) {
    $presupuestoId = $conexion->insert_id;
    $nroPresupuesto = "1" . str_pad($presupuestoId, 9, '0', STR_PAD_LEFT);
    $updateQuery = "UPDATE presupuestos SET nro_presupuesto = ? WHERE id = ?";
    $updateStatement = $conexion->prepare($updateQuery);
    $updateStatement->bind_param("si", $nroPresupuesto, $presupuestoId);
    $updateStatement->execute();
    $updateStatement->close();
    echo json_encode([
        'status' => 'S',
        'presupuestoId' => $presupuestoId
    ]);
} else {
    echo 'N';
}

$statement->close();
$conexion->close();

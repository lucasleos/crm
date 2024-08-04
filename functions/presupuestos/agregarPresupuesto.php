<?php
include("../../config/conexion.php");

// Recolectar datos del presupuesto desde la solicitud GET
$nroPresupuesto = $_GET['nroPresupuesto'];
$nroExpediente = $_GET['nroExpediente'];
$fechaInicio = $_GET['fechaInicio'];
$fechaVencimiento = $_GET['fechaVencimiento'];
$fechaAprobacion = $_GET['fechaAprobacion'];
$productoServicio = $_GET['productoServicio'];
$descripcion = $_GET['descripcion'];
$fechaEnvio = $_GET['fechaEnvio'];
$monto = $_GET['monto'];
$moneda = $_GET['moneda'];
$fechaGanado = $_GET['fechaGanado'];
$status = $_GET['status'];
$observaciones = $_GET['observaciones'];
$responsable = $_GET['responsable'];
$cotizacionDolar = $_GET['cotizacionDolar'];
$clienteId = $_GET['clienteId']; // Clave foránea

// Insertar presupuesto en la base de datos
$query = "INSERT INTO presupuestos (nro_presupuesto, nro_expediente, fecha_inicio, fecha_vencimiento, fecha_aprobacion, producto_servicio, descripcion, fecha_envio, monto, moneda, fecha_ganado, status, observaciones, responsable, cotizacion_dolar, cliente_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$statement = $conexion->prepare($query);
$statement->bind_param("sssssssssssssssi", $nroPresupuesto, $nroExpediente, $fechaInicio, $fechaVencimiento, $fechaAprobacion, $productoServicio, $descripcion, $fechaEnvio, $monto, $moneda, $fechaGanado, $status, $observaciones, $responsable, $cotizacionDolar, $clienteId);

if ($statement->execute()) {
    echo 'S';
} else {
    echo 'N';
}

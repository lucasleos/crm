<?php
include("../../config/conexion.php");

$anios = isset($_GET['anios']) ? explode(',', $_GET['anios']) : [];
$meses = isset($_GET['meses']) ? explode(',', $_GET['meses']) : [];
$moneda = $_GET['moneda'];
$estados = $_GET['estados'];

$monthConditions = array_map(function ($mes) {
    list($anio, $mes) = explode('-', $mes);
    return "(YEAR(p.fecha_inicio) = " . (int)$anio . " AND MONTH(p.fecha_inicio) = " . (int)$mes . ")";
}, $meses);

$yearConditions = array_map(function ($anio) {
    return "YEAR(p.fecha_inicio) = " . (int)$anio;
}, $anios);

$monthCondition = !empty($monthConditions) ? ' AND (' . implode(' OR ', $monthConditions) . ')' : '';
$yearCondition = !empty($yearConditions) ? ' AND (' . implode(' OR ', $yearConditions) . ')' : '';

$selectCase = '';
if ($moneda === 'ARS') {
    $selectCase = "SUM(monto) AS total_monto";
    $monedaCondition = "AND moneda = 'ARS'";
} elseif ($moneda === 'USD') {
    $selectCase = "SUM(monto) AS total_monto";
    $monedaCondition = "AND moneda = 'USD'";
} else {
    $selectCase = "
    SUM(
        CASE 
            WHEN moneda = 'USD' THEN monto * cotizacion_dolar
            ELSE monto
        END
    ) AS total_monto
    ";
    $monedaCondition = "";
}

$sql = "
    SELECT $selectCase
    FROM presupuestos p
    WHERE is_active = 1
    AND p.estado IN ('" . implode("','", $estados) . "')
    $yearCondition
    $monthCondition
    $monedaCondition
";

if ($result = $conexion->query($sql)) {
    $row = $result->fetch_assoc();
    echo $row['total_monto'] ?? "0";
} else {
    echo "Error en la consulta: " . $conexion->error;
}

$conexion->close();

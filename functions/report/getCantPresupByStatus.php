<?php
include("../../config/conexion.php");

$anios = isset($_GET['anios']) ? array_map('intval', explode(',', $_GET['anios'])) : [];
$meses = isset($_GET['meses']) ? explode(',', $_GET['meses']) : [];
$moneda = $_GET['moneda'];
$estados = $_GET['estados'];

$monthConditions = array_map(function ($mes) {
    return "DATE_FORMAT(p.fecha_inicio, '%Y-%m') = '" . $mes . "'";
}, $meses);

$monthCondition = !empty($monthConditions) ? implode(' OR ', $monthConditions) : '1=1';

$monedaCondition = '1=1';
if ($moneda === 'USD') {
    $monedaCondition = "p.moneda = 'USD'";
} elseif ($moneda === 'ARS') {
    $monedaCondition = "p.moneda = 'ARS'";
}

$query = "
    SELECT p.estado, COUNT(*) AS cantidad
    FROM presupuestos p
    WHERE YEAR(p.fecha_inicio) IN (" . implode(',', $anios) . ")
    AND ($monthCondition)
    AND $monedaCondition
    AND p.estado IN ('" . implode("','", $estados) . "')
    AND p.is_active = TRUE
    GROUP BY p.estado
";

$result = $conexion->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'name' => $row['estado'],
            'value' => (int)$row['cantidad']
        ];
    }
}

echo json_encode($data);

$conexion->close();

<?php
include("../../config/conexion.php");

$anios = isset($_GET['anios']) ? array_map('intval', explode(',', $_GET['anios'])) : [];
$meses = isset($_GET['meses']) ? explode(',', $_GET['meses']) : [];
$moneda = $_GET['moneda'];
$estados = $_GET['estados'];

$monthConditions = array_map(function ($mes) {
    list($anio, $mes) = explode('-', $mes);
    return "(YEAR(p.fecha_inicio) = " . (int)$anio . " AND MONTH(p.fecha_inicio) = " . (int)$mes . ")";
}, $meses);

$monthCondition = !empty($monthConditions) ? implode(' OR ', $monthConditions) : '1=1';

$monedaCondition = '1=1';
if ($moneda === 'USD') {
    $monedaCondition = "p.moneda = 'USD'";
} elseif ($moneda === 'ARS') {
    $monedaCondition = "p.moneda = 'ARS'";
}

$query = "
    SELECT p.estado, 
           DATE_FORMAT(p.fecha_inicio, '%Y-%m') AS mes, 
           COUNT(*) AS cantidad
    FROM presupuestos p
    WHERE YEAR(p.fecha_inicio) IN (" . implode(',', $anios) . ")
    AND ($monthCondition)
    AND $monedaCondition
    AND p.estado IN ('" . implode("','", $estados) . "')
    AND p.is_active = TRUE
    GROUP BY p.estado, YEAR(p.fecha_inicio), MONTH(p.fecha_inicio)
    ORDER BY p.estado, mes
";

$result = $conexion->query($query);

$data = [];
$totals = [];

// Función para convertir el mes en formato "ENE 2024", etc.
function formatMonth($mes)
{
    $months = [
        '01' => 'ENE',
        '02' => 'FEB',
        '03' => 'MAR',
        '04' => 'ABR',
        '05' => 'MAY',
        '06' => 'JUN',
        '07' => 'JUL',
        '08' => 'AGO',
        '09' => 'SEP',
        '10' => 'OCT',
        '11' => 'NOV',
        '12' => 'DIC'
    ];
    list($year, $month) = explode('-', $mes);
    return $months[$month] . " " . $year;
}

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedMonth = formatMonth($row['mes']);

        // Agregar datos al arreglo
        if (!isset($data[$row['estado']])) {
            $data[$row['estado']] = [];
        }
        $data[$row['estado']][$formattedMonth] = (int)$row['cantidad'];

        // Sumar al total por mes
        if (!isset($totals[$formattedMonth])) {
            $totals[$formattedMonth] = 0;
        }
        $totals[$formattedMonth] += (int)$row['cantidad'];
    }
}

// Agregar los totales a los datos
$data['Totales'] = $totals;

echo json_encode($data);

$conexion->close();

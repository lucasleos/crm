<?php
include("../../config/conexion.php");

$anios = isset($_GET['anios']) ? array_map('intval', explode(',', $_GET['anios'])) : [];
$meses = isset($_GET['meses']) ? explode(',', $_GET['meses']) : [];
$moneda = $_GET['moneda'];
$estados = $_GET['estados'];

$anios = array_filter($anios, function ($anio) {
    return $anio >= 2000 && $anio <= date("Y") + 1;
});

$meses = array_filter($meses, function ($mes) {
    return preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes);
});

$monthConditions = array_map(function ($mes) use ($conexion) {
    return "DATE_FORMAT(p.fecha_inicio, '%Y-%m') = '" . mysqli_real_escape_string($conexion, $mes) . "'";
}, $meses);

$monthCondition = !empty($monthConditions) ? implode(' OR ', $monthConditions) : '1=1';

$yearCondition = !empty($anios) ? "YEAR(p.fecha_inicio) IN (" . implode(',', $anios) . ")" : '1=1';

$selectCase = '';
if ($moneda === 'ARS') {
    $selectCase = "SUM(monto) AS total";
    $monedaCondition = "AND moneda = 'ARS'";
} elseif ($moneda === 'USD') {
    $selectCase = "SUM(monto) AS total";
    $monedaCondition = "AND moneda = 'USD'";
} else {
    $selectCase = "
    SUM(
        CASE 
            WHEN moneda = 'USD' THEN monto * cotizacion_dolar
            ELSE monto
        END
    ) AS total
    ";
    $monedaCondition = "";
}

$query = "
    SELECT p.estado, DATE_FORMAT(p.fecha_inicio, '%Y-%m') AS mes, $selectCase
    FROM presupuestos p
    WHERE $yearCondition
    AND ($monthCondition)
    AND p.estado IN ('" . implode("','", $estados) . "')
    AND p.is_active = TRUE
    $monedaCondition
    GROUP BY p.estado, mes
    ORDER BY mes ASC
";

$result = $conexion->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estado = $row['estado'];
        $mes = $row['mes'];
        $total = (float)$row['total'];

        if (!isset($data[$estado])) {
            $data[$estado] = [];
        }
        $data[$estado][$mes] = $total;
    }
}

echo json_encode($data);

$conexion->close();

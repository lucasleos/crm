<?php
include("../../config/conexion.php");

setlocale(LC_TIME, 'es_AR.UTF-8');

$anios = isset($_GET['anios']) ? array_map('intval', explode(',', $_GET['anios'])) : [];
$meses = isset($_GET['meses']) ? explode(',', $_GET['meses']) : [];
$moneda = $_GET['moneda'];
$estados = $_GET['estados'];

$monthConditions = array_map(function ($mes) {
    return "DATE_FORMAT(p.fecha_inicio, '%Y-%m') = '" . $mes . "'";
}, $meses);

$monthCondition = !empty($monthConditions) ? implode(' OR ', $monthConditions) : '1=1';

$currencyCondition = '';
$totalSelect = '';
if ($moneda === 'ambas') {
    $currencyCondition = "AND p.moneda IN ('ARS', 'USD')";
    $totalSelect = "SUM(
            CASE 
                WHEN moneda = 'USD' THEN monto * cotizacion_dolar
                ELSE monto
            END
    ) AS total";
} else {
    $currencyCondition = "AND p.moneda = '$moneda'";
    $totalSelect = "SUM(monto) as total";
}

$query = "
    SELECT 
        p.estado, 
        DATE_FORMAT(p.fecha_inicio, '%Y-%m') AS mes, 
        $totalSelect
    FROM 
        presupuestos p
    WHERE 
        YEAR(p.fecha_inicio) IN (" . implode(',', $anios) . ")
        AND ($monthCondition)
        AND p.estado IN ('" . implode("','", $estados) . "')
        AND p.is_active = TRUE
        $currencyCondition
    GROUP BY 
        p.estado, mes
    ORDER BY 
        p.estado ASC, mes ASC
";

$result = $conexion->query($query);

$data = [];  // Array para almacenar los datos

if ($result && $result->num_rows > 0) {
    $currentEstado = '';

    $mesesFormateados = [];
    foreach ($meses as $mes) {
        $fecha = DateTime::createFromFormat('Y-m', $mes);
        $mesFormateado = strftime('%b %Y', $fecha->getTimestamp());
        $mesesFormateados[] = strtoupper($mesFormateado);
    }

    $rowData = array_fill_keys($mesesFormateados, 0);  // Inicializa los valores en 0 con meses formateados
    $totalesMes = array_fill_keys($mesesFormateados, 0);

    while ($row = $result->fetch_assoc()) {
        $estado = htmlspecialchars($row['estado'], ENT_QUOTES, 'UTF-8');
        $mes = $row['mes'];
        $total = (float)$row['total'];  // Guarda el total como número

        $fecha = DateTime::createFromFormat('Y-m', $mes);
        $mesFormato = strftime('%b %Y', $fecha->getTimestamp());
        $mesFormato = strtoupper($mesFormato);

        if ($estado !== $currentEstado && $currentEstado !== '') {
            $data[$currentEstado] = $rowData;
            $rowData = array_fill_keys($mesesFormateados, 0);
        }

        $currentEstado = $estado;
        $rowData[$mesFormato] = $total;
        $totalesMes[$mesFormato] += $total;  // Suma el total del mes
    }

    $data[$currentEstado] = $rowData;

    $data['Totales'] = $totalesMes;
}

echo json_encode($data);

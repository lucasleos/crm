<?php
include("../../config/conexion.php");

setlocale(LC_TIME, 'es_ES.UTF-8');

$anios = isset($_GET['anios']) ? array_map('intval', explode(',', $_GET['anios'])) : [];
$meses = isset($_GET['meses']) ? explode(',', $_GET['meses']) : [];
$moneda = $_GET['moneda'];
$estados = $_GET['estados'];

$monthConditions = array_map(function ($mes) {
    return "DATE_FORMAT(p.fecha_inicio, '%Y-%m') = '" . $mes . "'";
}, $meses);

$monthCondition = !empty($monthConditions) ? implode(' OR ', $monthConditions) : '1=1';

$query = "
SELECT
p.estado,
DATE_FORMAT(p.fecha_inicio, '%Y-%m') AS mes,
SUM(CASE WHEN moneda = 'USD' THEN monto * cotizacion_dolar ELSE monto END) AS total,
SUM(CASE WHEN moneda = 'USD' THEN monto ELSE 0 END) AS total_usd,
SUM(CASE WHEN moneda = 'ARS' THEN monto ELSE 0 END) AS total_ars,
COUNT(CASE WHEN moneda = 'USD' THEN 1 END) AS count_usd,
COUNT(CASE WHEN moneda = 'ARS' THEN 1 END) AS count_ars
FROM presupuestos p
WHERE YEAR(p.fecha_inicio) IN (" . implode(',', $anios) . ")
AND ($monthCondition)
AND p.estado IN ('" . implode("','", $estados) . "')
";

if ($moneda !== 'ambas') {
    $query .= " AND p.moneda = '$moneda' ";
}

$query .= "
AND p.is_active = TRUE
GROUP BY p.estado, mes
ORDER BY p.estado ASC, mes ASC
";

$result = $conexion->query($query);

$all = '';

if ($result && $result->num_rows > 0) {
    $tableHeaders = '<th class="text-center bg-primary text-white">ESTADO</th>';
    foreach ($meses as $mes) {
        $timestamp = strtotime($mes . '-01');
        $mesNombre = strftime('%b %Y', $timestamp);
        $tableHeaders .= '<th class="text-center bg-primary text-white">' . ucfirst($mesNombre) . '</th>';
    }
    $tableHeaders .= '<th class="text-center bg-primary text-white">Total x Estado</th>';

    $all .= '
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="font-size: 1.1em;">
                    ' . $tableHeaders . '
                </tr>
            </thead>
            <tbody>';

    $currentEstado = '';
    $rowData = array_fill_keys($meses, '<td class="text-center">-</td>');
    $totalesMes = array_fill_keys($meses, 0);
    $totalesUsdMes = array_fill_keys($meses, 0);
    $totalesArsMes = array_fill_keys($meses, 0);
    $countUsdMes = array_fill_keys($meses, 0);
    $countArsMes = array_fill_keys($meses, 0);
    $totalFila = 0;

    while ($row = $result->fetch_assoc()) {
        $estado = htmlspecialchars($row['estado'], ENT_QUOTES, 'UTF-8');
        $mes = $row['mes'];

        $total = $moneda === 'USD' ? 'USD' . number_format($row['total_usd'], 2, '.', ',') : 'ARS' . number_format($row['total'], 2, '.', ',');
        $totalUsd = number_format($row['total_usd'], 2, '.', ',');
        $totalArs = number_format($row['total_ars'], 2, '.', ',');
        $countUsd = $row['count_usd'];
        $countArs = $row['count_ars'];

        $totalesArsMes[$mes] += $row['total_ars'];
        $totalesUsdMes[$mes] += $row['total_usd'];
        $countUsdMes[$mes] += $row['count_usd'];
        $countArsMes[$mes] += $row['count_ars'];

        if ($estado !== $currentEstado && $currentEstado !== '') {

            $totalFilaFormatted = $moneda === 'USD' ? 'USD' . number_format($totalFila, 2, '.', ',') : 'ARS' . number_format($totalFila, 2, '.', ',');
            $rowData['total'] = '<td class="text-center font-weight-bold">' . $totalFilaFormatted . '</td>';

            $all .= '<tr style="' . $estadoClass . '"><td class="font-weight-bold" style="font-size: 1.1em;">' . $currentEstado . '</td>' . implode('', $rowData) . '</tr>';

            $rowData = array_fill_keys($meses, '<td class="text-center">-</td>');
            $totalFila = 0;
        }

        $currentEstado = $estado;

        $estadoColors = [
            'En proceso de cotización' => 'rgba(26, 26, 255, 0.5)',  // Azul con 50% de opacidad
            'Pendiente de análisis' => 'rgba(23, 162, 184, 0.5)',    // Cian con 50% de opacidad
            'Esperando respuesta' => 'rgba(255, 193, 7, 0.5)',       // Amarillo con 50% de opacidad
            'Cerrado - PERDIDO' => 'rgba(220, 53, 69, 0.5)',         // Rojo con 50% de opacidad
            'Cerrado - NO COMERCIALIZAMOS' => 'rgba(255, 80, 0, 0.5)', // Naranja con 50% de opacidad
            'Cerrado - GANADO' => 'rgba(40, 167, 69, 0.5)',          // Verde con 50% de opacidad
            'Cerrado - VENCIDO' => 'rgba(108, 117, 125, 0.5)'        // Gris con 50% de opacidad
        ];

        $estadoClass = isset($estadoColors[$currentEstado]) ? 'background-color: ' . $estadoColors[$currentEstado] : '';

        if ($moneda === 'ambas') {
            $tooltipText = "USD " . $totalUsd . " (" . $countUsd . " Pptos.)<br>
                            ARS " . $totalArs . " (" . $countArs . " Pptos.)";
        } else {
            if ($moneda === 'USD') {
                $tooltipText = $countUsd . " Pptos.";
            } else {
                $tooltipText = $countArs . " Pptos.";
            }
        }

        $rowData[$mes] = '
                <td class="text-center" style="font-size: 1.1em;">
                    <span
                        data-html="true"
                        data-toggle="tooltip"
                        data-placement="right"
                        title="<div class=\'text-white font-weight-bold\'>
                                ' . htmlspecialchars($tooltipText, ENT_QUOTES, 'UTF-8') . '
                            </div>">
                        ' . $total . '
                    </span>
                </td>';

        $totalesMes[$mes] += $row['total'];

        if ($moneda == "ambas") {
            $totalFila += $row['total'];
            $totaGeneral[$mes] += $row['total'];
        } elseif ($moneda == "USD") {
            $totalFila += $row['total_usd'];
            $totaGeneral[$mes] += $row['total_usd'];
        } else {
            $totalFila += $row['total_ars'];
            $totaGeneral[$mes] += $row['total_ars'];
        }
    }

    $totalFilaFormatted = $moneda === 'USD' ? 'USD' . number_format($totalFila, 2, '.', ',') : 'ARS' . number_format($totalFila, 2, '.', ',');
    $rowData['total'] = '<td class="text-center font-weight-bold">' . $totalFilaFormatted . '</td>';
    $all .= '<tr style="' . $estadoClass . '"><td class="font-weight-bold" style="font-size: 1.1em;">' . $currentEstado . '</td>' . implode('', $rowData) . '</tr>';

    $totalesFila = '<td class="font-weight-bold bg-secondary text-white" style="font-size: 1.1em;">Total</td>';

    if ($moneda === 'ARS') {
        foreach ($meses as $mes) {
            $total = 'ARS' . number_format($totalesArsMes[$mes], 2, '.', ',');
            $tooltipText = $countArsMes[$mes] . ' Pptos.';

            $totalesFila .= '
                <td class="text-center font-weight-bold bg-secondary text-white">
                    <span 
                        data-html="true" 
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="ARS ' . htmlspecialchars($tooltipText, ENT_QUOTES, 'UTF-8') . '">
                        ' . $total . '
                    </span>
                </td>';
        }
    } elseif ($moneda === 'USD') {
        foreach ($meses as $mes) {
            $total = 'USD' . number_format($totalesUsdMes[$mes], 2, '.', ',');
            $tooltipText = $countUsdMes[$mes] . ' Pptos.';

            $totalesFila .= '
                <td class="text-center font-weight-bold bg-secondary text-white">
                    <span 
                        data-html="true" 
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="' . htmlspecialchars($tooltipText, ENT_QUOTES, 'UTF-8') . '">
                        ' . $total . '
                    </span>
                </td>';
        }
    } else {
        foreach ($meses as $mes) {
            $tooltipTotalMes = '
            USD$ ' . number_format($totalesUsdMes[$mes], 2, '.', ',') . ' (' . $countUsdMes[$mes] . ' Pptos.)<br>
            ARS$ ' . number_format($totalesArsMes[$mes], 2, '.', ',') . ' (' . $countArsMes[$mes] . ' Pptos.)';

            $totalesFila .= '
            <td class="text-center font-weight-bold bg-secondary text-white">
                <span 
                    data-html="true" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="' . htmlspecialchars($tooltipTotalMes, ENT_QUOTES, 'UTF-8') . '">
                    ARS' . number_format($totalesMes[$mes], 2, '.', ',') . '
                </span>
            </td>';
        }
    }

    // Agregar la columna de total en la fila de totales
    $totalFinal = array_sum($totaGeneral);
    $totalFinalFormatted = $moneda === 'USD' ? 'USD' . number_format($totalFinal, 2, '.', ',') : 'ARS' . number_format($totalFinal, 2, '.', ',');
    $totalesFila .= '<td class="text-center font-weight-bold bg-secondary text-white">' . $totalFinalFormatted . '</td>';

    $all .= '<tr>' . $totalesFila . '</tr>';

    $all .= '</tbody></table>';
}

echo $all;

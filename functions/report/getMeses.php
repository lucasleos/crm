<?php
include("../../config/conexion.php");

$meses_traduccion = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];

$orden_meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];

$anios = $_GET['anios'];
$anios_array = explode(',', $anios);
$anios_in_clause = implode("','", $anios_array);

$meses_html = '';
$meses_data = [];
$meses_vistos = [];

$query_meses = "
SELECT DISTINCT
DATE_FORMAT(fecha_inicio, '%Y-%m') AS mes,
DATE_FORMAT(fecha_inicio, '%M') AS nombre_mes
FROM presupuestos
WHERE is_active = TRUE
AND DATE_FORMAT(fecha_inicio, '%Y') IN ('$anios_in_clause')
ORDER BY mes
";

$result_meses = $conexion->query($query_meses);

if ($result_meses->num_rows > 0) {
    while ($row = $result_meses->fetch_assoc()) {
        $mes = htmlspecialchars($row['mes'], ENT_QUOTES, 'UTF-8');
        $nombre_mes_en = $row['nombre_mes']; // Nombre del mes en inglés

        // Traducir el nombre del mes
        $nombre_mes = isset($meses_traduccion[$nombre_mes_en]) ? $meses_traduccion[$nombre_mes_en] : $nombre_mes_en;
        $nombre_mes = strtoupper(htmlspecialchars($nombre_mes, ENT_QUOTES, 'UTF-8'));

        if (!isset($meses_data[$nombre_mes])) {
            $meses_data[$nombre_mes] = [];
        }
        $meses_data[$nombre_mes][] = $mes;
    }

    foreach ($orden_meses as $mes_ordenado) {
        if (isset($meses_data[$mes_ordenado]) && !in_array($mes_ordenado, $meses_vistos)) {
            $meses_html .= '<div class="card-select">' . $mes_ordenado . '</div>';
            $meses_vistos[] = $mes_ordenado;
        }
    }
}

$response = [
    'html' => $meses_html,
    'data' => $meses_data
];

echo json_encode($response);

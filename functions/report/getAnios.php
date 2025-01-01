<?php
include("../../config/conexion.php");

$anios = '';

$query_anios = "SELECT DISTINCT YEAR(fecha_inicio) AS anio FROM presupuestos WHERE is_active = TRUE ORDER BY anio";
$result_anios = $conexion->query($query_anios);

if ($result_anios->num_rows > 0) {
    while ($row = $result_anios->fetch_assoc()) {
        $anio = htmlspecialchars($row['anio'], ENT_QUOTES, 'UTF-8');
        $anios .= '<div class="card-select" data-value="' . $anio . '">' . $anio . '</div>';
    }
}
echo $anios;

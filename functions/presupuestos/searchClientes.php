<?php
include("../../config/conexion.php");
$term = $_GET['term'];

$query = "SELECT id, razon_social FROM clientes WHERE is_active = TRUE AND razon_social LIKE '%" . $term . "%'";
$result = $conexion->query($query);

$suggestions = array();
while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        'id' => $row['id'],
        'razon_social' => $row['razon_social']
    ];
}

echo json_encode($suggestions);

$conexion->close();

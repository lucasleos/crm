<?php
include("../../config/conexion.php");
$term = $_GET['term'];

$querry = "SELECT razon_social FROM clientes WHERE razon_social LIKE '%" . $term . "%'";
$result = $conexion->query($querry);

$suggestions = array();
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['razon_social'];
}

echo json_encode($suggestions);

$conexion->close();

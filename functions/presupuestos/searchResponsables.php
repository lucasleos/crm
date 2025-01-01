<?php
include("../../config/conexion.php");

$term = $_GET['term'];
$suggestions = array();

if ($stmt = $conexion->prepare("SELECT id, nombre, dni FROM responsables WHERE nombre LIKE ?")) {
    $term = "%" . $term . "%";
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'),
            'nombre' => htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8'),
            'dni' => htmlspecialchars($row['dni'], ENT_QUOTES, 'UTF-8')
        ];
    }

    $stmt->close();
}

echo json_encode($suggestions);

$conexion->close();

<?php
include("../../config/conexion.php");

// Verificar si el parámetro 'id' está presente en la solicitud GET
$clienteId = isset($_GET['id']) ? $_GET['id'] : '';

if ($clienteId) {
    // Preparar la consulta SQL usando declaraciones preparadas para evitar inyección SQL
    $query = "SELECT * FROM clientes WHERE id = ?";
    $statement = $conexion->prepare($query);

    // Vincular el parámetro 'id' como un entero
    $statement->bind_param("i", $clienteId);
    $statement->execute();

    // Obtener el resultado de la consulta
    $result = $statement->get_result();

    // Verificar si se encontraron registros
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Cliente no encontrado']);
    }

    // Cerrar la declaración
    $statement->close();
} else {
    echo json_encode(['error' => 'ID de cliente no proporcionado']);
}

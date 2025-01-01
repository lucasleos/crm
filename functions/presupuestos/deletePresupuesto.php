<?php
include("../../config/conexion.php");

$id = $_GET['id'];

$sql = "UPDATE presupuestos SET is_active = FALSE WHERE id = ?";

$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo 'S'; // Éxito
    } else {
        echo "Error en la ejecución: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error en la preparación: " . mysqli_error($conexion);
}

mysqli_close($conexion);

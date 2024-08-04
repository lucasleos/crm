<?php
include("../../config/conexion.php");

$idcategoria = $_GET['id'];

$sql = "DELETE FROM STOCK_CATEGORIA WHERE ID_CATEGORIA = $idcategoria";
if (mysqli_query($conexion, $sql)) {
    echo 'S';
} else {
    echo "Error en la inserción: " . mysqli_error($conn);
}

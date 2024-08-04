<?php
include("../../config/conexion.php");

$all = '';
$query = "SELECT SC.ID_CATEGORIA as ID,
SC.DESCRIPCION as CATEGORIA
from STOCK_CATEGORIA SC";
$result = $conexion->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $all = $all . '<option value="'.$row['ID'].'">'.strtoupper($row['CATEGORIA']).'</option>';
    }
}
echo $all;
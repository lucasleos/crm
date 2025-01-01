<?php
include("../../config/conexion.php");

$presupuesto_id = $_GET['presupuesto_id'];

$query = "SELECT id, observacion, fecha FROM observaciones WHERE presupuesto_id = ? ORDER BY id DESC";
$statement = $conexion->prepare($query);
$statement->bind_param("i", $presupuesto_id);
$statement->execute();
$result = $statement->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<div class="observacion-item d-flex align-items-center" id="observacion-' . $row['id'] . '" style="">';
    echo '<div class="observacion-fecha" style="font-weight: bold;">' . date("d/m/Y H:i:s", strtotime($row['fecha'])) . '</div>';
    echo '<textarea class="form-control my-2 mr-2 observacion-input" readonly disabled style="resize: none; overflow-wrap: break-word; white-space: pre-wrap; width: 100%; height: auto;">' . htmlspecialchars($row['observacion']) . '</textarea>';
    echo '<label class="mdi mdi-pencil bg-warning text-dark" style="cursor: pointer; font-size: 16px;border-radius: 5px;" onclick="editarObservacion(' . $row['id'] . ')"></label>';
    echo '<label class="mdi mdi-close mx-2 bg-danger text-dark" style="cursor: pointer; font-size: 16px; border-radius: 5px;" onclick="eliminarObservacion(' . $row['id'] . ')"></label>';
    echo '</div>';
}

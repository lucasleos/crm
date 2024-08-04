<?php
include("../../config/conexion.php");

$search = $_GET['search'];
$all = '';

$query = "SELECT id, nro_presupuesto, nro_expediente, fecha_inicio, fecha_vencimiento, fecha_aprobacion, producto_servicio, descripcion, fecha_envio, monto, moneda, fecha_ganado, status, observaciones, responsable, cotizacion_dolar, cliente_id 
          FROM presupuestos 
          WHERE nro_presupuesto LIKE '%$search%'
          ORDER BY id DESC";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $nro_presupuesto = htmlspecialchars($row['nro_presupuesto'], ENT_QUOTES, 'UTF-8');
        $nro_expediente = htmlspecialchars($row['nro_expediente'], ENT_QUOTES, 'UTF-8');
        $fecha_inicio = htmlspecialchars($row['fecha_inicio'], ENT_QUOTES, 'UTF-8');
        $fecha_vencimiento = htmlspecialchars($row['fecha_vencimiento'], ENT_QUOTES, 'UTF-8');
        $fecha_aprobacion = htmlspecialchars($row['fecha_aprobacion'], ENT_QUOTES, 'UTF-8');
        $producto_servicio = htmlspecialchars($row['producto_servicio'], ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');
        $fecha_envio = htmlspecialchars($row['fecha_envio'], ENT_QUOTES, 'UTF-8');
        $monto = htmlspecialchars($row['monto'], ENT_QUOTES, 'UTF-8');
        $moneda = htmlspecialchars($row['moneda'], ENT_QUOTES, 'UTF-8');
        $fecha_ganado = htmlspecialchars($row['fecha_ganado'], ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8');
        $observaciones = htmlspecialchars($row['observaciones'], ENT_QUOTES, 'UTF-8');
        $responsable = htmlspecialchars($row['responsable'], ENT_QUOTES, 'UTF-8');
        $cotizacion_dolar = htmlspecialchars($row['cotizacion_dolar'], ENT_QUOTES, 'UTF-8');
        $cliente_id = htmlspecialchars($row['cliente_id'], ENT_QUOTES, 'UTF-8');

        $presupuestoData = json_encode([
            'id' => $id,
            'nroPresupuesto' => $nro_presupuesto,
            'nroExpediente' => $nro_expediente,
            'fechaInicio' => $fecha_inicio,
            'fechaVencimiento' => $fecha_vencimiento,
            'fechaAprobacion' => $fecha_aprobacion,
            'productoServicio' => $producto_servicio,
            'descripcion' => $descripcion,
            'fechaEnvio' => $fecha_envio,
            'monto' => $monto,
            'moneda' => $moneda,
            'fechaGanado' => $fecha_ganado,
            'status' => $status,
            'observaciones' => $observaciones,
            'responsable' => $responsable,
            'cotizacionDolar' => $cotizacion_dolar,
            'clienteId' => $cliente_id
        ]);

        $all .= '
        <tr style="font-size: 16px;" id="filaPresupuesto' . $id . '">
            <td><p class="mb-0 text-muted"><strong>' . strtoupper($nro_presupuesto) . '</strong></p></td>
            <td><p class="mb-0 text-muted">' . $nro_expediente . '</p></td>
            <td><p class="mb-0 text-muted">' . $fecha_vencimiento . '</p></td>
            <td><p class="mb-0 text-muted">' . $producto_servicio . '</p></td>
            <td><p class="mb-0 text-muted">' . $moneda . ' ' . $monto . '</p></td>
            <td><p class="mb-0 text-muted">' . $status . '</p></td>
            <td><p class="mb-0 text-muted">' . $responsable . '</p></td>
            <td>
                <label class="mdi mdi-pencil" data-toggle="modal" data-target="#editModal" 
                       onclick=\'editarPresupuesto(' . $presupuestoData . ')\'>
                </label>
                <label class="mdi mdi-close" data-toggle="modal" data-target="#deleteModal" 
                       onclick="deletePresupuestoModal(' . $id . ', ' .
            "'" . strtoupper($nro_presupuesto) . "'" .
            ')"></label>
            </td>
        </tr>';
    }
}
echo $all;

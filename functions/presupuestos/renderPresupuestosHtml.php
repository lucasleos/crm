<?php
function renderPresupuestosHTML($presupuestos)
{
    $table = '';
    if ($presupuestos->num_rows > 0) {
        while ($row = $presupuestos->fetch_assoc()) {

            $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
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
            $estado = htmlspecialchars($row['estado'], ENT_QUOTES, 'UTF-8');
            $cotizacion_dolar = htmlspecialchars($row['cotizacion_dolar'], ENT_QUOTES, 'UTF-8');
            $cliente_id = htmlspecialchars($row['cliente_id'], ENT_QUOTES, 'UTF-8');
            $razon_social = htmlspecialchars($row['razon_social'], ENT_QUOTES, 'UTF-8');
            $responsable_id = htmlspecialchars($row['responsable_id'], ENT_QUOTES, 'UTF-8');
            $nombre_responsable = htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8');

            $estadoClass = '';
            switch ($estado) {
                case 'En proceso de cotización':
                    $estadoClass = 'badge badge-pill estado-cotizacion p-2';
                    break;
                case 'Esperando respuesta':
                    $estadoClass = 'badge badge-pill estado-esperando p-2';
                    break;
                case 'Pendiente de análisis':
                    $estadoClass = 'badge badge-pill estado-analisis p-2';
                    break;
                case 'Cerrado - GANADO':
                    $estadoClass = 'badge badge-pill estado-ganado p-2';
                    break;
                case 'Cerrado - NO COMERCIALIZAMOS':
                    $estadoClass = 'badge badge-pill estado-no-comercializamos p-2';
                    break;
                case 'Cerrado - PERDIDO':
                    $estadoClass = 'badge badge-pill estado-perdido p-2';
                    break;
                case 'Cerrado - VENCIDO':
                    $estadoClass = 'badge badge-pill estado-vencido p-2';
                    break;
                default:
                    $estadoClass = '';
                    break;
            }

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
                'estado' => $estado,
                'responsableId' => $responsable_id,
                'responsable' => $nombre_responsable,
                'cotizacionDolar' => $cotizacion_dolar,
                'clienteId' => $cliente_id,
                'razonSocial' => $razon_social
            ]);

            $table .= '
<tr style="font-size: 16px;" id="filaPresupuesto' . $id . '">
    <td>
        <p class="mb-0 text-muted"><strong>' . strtoupper($nro_presupuesto) . '</strong></p>
    </td>
    <td>
        <p class="mb-0 text-muted">' . $razon_social . '</p>
    </td>
    <td>
        <p class="mb-0 text-muted">' . $producto_servicio . '</p>
    </td>
    <td>
        <p class="mb-0 text-muted">' . $moneda . ' ' . $monto . '</p>
    </td>
    <td>
        <p class="mb-0 text-muted ' . $estadoClass . '"><strong>' . $estado . '</strong></p>
    </td>
    <td>
        <p class="mb-0 text-muted">' . $nombre_responsable . '</p>
    </td>
    <td>
        <p class="mb-0 text-muted">' . $fecha_vencimiento . '</p>
    </td>
    <td>
        <label class="mdi mdi-pencil" data-toggle="modal" data-target="#editModal"
            onclick=\'editarPresupuesto(' . htmlspecialchars($presupuestoData, ENT_QUOTES, 'UTF-8') . ')\'>
        </label>
        <label class="mdi mdi-close" data-toggle="modal" data-target="#deleteModal"
            onclick="deletePresupuestoModal(' . $id . ', ' .
                "'" . strtoupper($nro_presupuesto) . "'" .
                ')"></label>
    </td>
</tr>';
        }
    }

    return $table;
}

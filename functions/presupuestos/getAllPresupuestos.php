<?php
include "../../config/conexion.php";
include "renderPresupuestosHtml.php";
include "renderPagination.php";

function getPresupuestos($conexion, $search, $page, $resultsPerPage, $order)
{
    $offset = ($page - 1) * $resultsPerPage;
    $orderBy = "";

    if ($order === "") {
        $orderBy = 'p.id DESC';
    } else {
        $orderBy = 'p.fecha_vencimiento ' . $order;
    }

    $query = "SELECT p.id, p.nro_presupuesto, p.nro_expediente, p.fecha_inicio, 
                     p.fecha_vencimiento, p.fecha_aprobacion, p.producto_servicio, 
                     p.descripcion, p.fecha_envio, p.monto, p.moneda, p.fecha_ganado, 
                     p.estado, p.cotizacion_dolar, p.cliente_id, c.razon_social, 
                     r.id AS responsable_id, r.nombre 
              FROM presupuestos p
              JOIN clientes c ON p.cliente_id = c.id
              JOIN responsables r ON p.responsable_id = r.id
              WHERE c.razon_social LIKE ? 
              AND c.is_active = TRUE 
              AND p.is_active = TRUE
              ORDER BY $orderBy
              LIMIT ? OFFSET ?";

    $stmt = $conexion->prepare($query);
    $searchParam = "%$search%";
    $stmt->bind_param('sii', $searchParam, $resultsPerPage, $offset);
    $stmt->execute();
    return $stmt->get_result();
}


function getTotalPresupuestos($conexion, $search)
{
    $countQuery = "SELECT COUNT(*) as total FROM presupuestos p
                   JOIN clientes c ON p.cliente_id = c.id
                   WHERE c.razon_social LIKE ? 
                   AND c.is_active = TRUE 
                   AND p.is_active = TRUE";

    $stmt = $conexion->prepare($countQuery);
    $searchParam = "%$search%";
    $stmt->bind_param('s', $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$resultsPerPage = 10;
$order = $_GET['order'];

$totalResults = getTotalPresupuestos($conexion, $search);
$totalPages = ceil($totalResults / $resultsPerPage);
$presupuestos = getPresupuestos($conexion, $search, $page, $resultsPerPage, $order);

$response = [
    'rows' => renderPresupuestosHtml($presupuestos),
    'pagination' => renderPagination($page, $totalPages, $order)
];

header('Content-Type: application/json');
echo json_encode($response);

<?php
include("../../config/conexion.php");

$search = $_GET['search'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Número de resultados por página
$offset = ($page - 1) * $limit;

$queryCount = "SELECT COUNT(*) as total FROM clientes WHERE razon_social LIKE '%$search%' AND is_active = TRUE";
$resultCount = $conexion->query($queryCount);
$rowCount = $resultCount->fetch_assoc();
$totalRows = $rowCount['total'];
$totalPages = ceil($totalRows / $limit);

$query = "SELECT id, razon_social, cuit, correo, telefono, localidad, codigo_postal, provincia 
          FROM clientes 
          WHERE razon_social LIKE '%$search%' AND is_active = TRUE
          ORDER BY id DESC
          LIMIT $limit OFFSET $offset";
$result = $conexion->query($query);

$all = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $razon_social = htmlspecialchars($row['razon_social'], ENT_QUOTES, 'UTF-8');
        $cuit = htmlspecialchars($row['cuit'], ENT_QUOTES, 'UTF-8');
        $correo = htmlspecialchars($row['correo'], ENT_QUOTES, 'UTF-8');
        $telefono = htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8');
        $localidad = htmlspecialchars($row['localidad'], ENT_QUOTES, 'UTF-8');
        $codigo_postal = htmlspecialchars($row['codigo_postal'], ENT_QUOTES, 'UTF-8');
        $provincia = htmlspecialchars($row['provincia'], ENT_QUOTES, 'UTF-8');

        $clienteData = json_encode([
            'id' => $id,
            'razonSocial' => $razon_social,
            'cuit' => $cuit,
            'correo' => $correo,
            'telefono' => $telefono,
            'localidad' => $localidad,
            'codigoPostal' => $codigo_postal,
            'provincia' => $provincia
        ]);

        $all .= '
        <tr style="font-size: 16px;" id="filaCliente' . $id . '">
            <td><p class="mb-0 text-muted"><strong>' . strtoupper($razon_social) . ' (' . $cuit . ')' . '</strong></p></td>
            <td><p class="mb-0 text-muted">' . $correo . '</p></td>
            <td><p class="mb-0 text-muted">' . $telefono . '</p></td>
            <td><p class="mb-0 text-muted">' . $localidad . ', ' . $provincia . '</p></td>
            <td>
                <label class="mdi mdi-text-box-plus-outline" data-toggle="modal" data-target="#editModal" 
                    onclick=\'seleccionarCliente(' . $id . ')\'>
                </label>
                <label class="mdi mdi-pencil" data-toggle="modal" data-target="#editModal" 
                       onclick=\'editarCliente(' . $clienteData . ')\'>
                </label>
                <label class="mdi mdi-close" data-toggle="modal" data-target="#deleteModal" 
                       onclick="deleteClienteModal(' . $id . ', ' .
            "'" . strtoupper($razon_social) . "'" .
            ')"></label>
            </td>
        </tr>';
    }
}

$pagination = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';

// Enlace a la primera página
if ($page > 1) {
    $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getClientes(1)">Primera</a></li>';
}

// Enlace a la página anterior
if ($page > 1) {
    $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getClientes(' . ($page - 1) . ')">Anterior</a></li>';
}

$startPage = max(1, $page - 2);
$endPage = min($totalPages, $page + 2);

if ($startPage > 1) {
    $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
}

for ($i = $startPage; $i <= $endPage; $i++) {
    $active = ($i == $page) ? 'active' : '';
    $pagination .= '<li class="page-item ' . $active . '"><a class="page-link" href="javascript:void(0);" onclick="getClientes(' . $i . ')">' . $i . '</a></li>';
}

if ($endPage < $totalPages) {
    $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
}

if ($page < $totalPages) {
    $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getClientes(' . ($page + 1) . ')">Siguiente</a></li>';
}

// Enlace a la última página
if ($page < $totalPages) {
    $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getClientes(' . $totalPages . ')">Última</a></li>';
}

$pagination .= '</ul></nav>';

$response = [
    'rows' => $all,
    'pagination' => $pagination
];

header('Content-Type: application/json');
echo json_encode($response);

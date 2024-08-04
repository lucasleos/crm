<?php
include("../../config/conexion.php");

$search = $_GET['search'];
$all = '';

$query = "SELECT id, razon_social, cuit, correo, telefono, localidad, codigo_postal, provincia 
          FROM clientes 
          WHERE razon_social LIKE '%$search%'
          ORDER BY id DESC";
$result = $conexion->query($query);

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
                    onclick=\'seleccionarCliente(' . $clienteData . ')\'>
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
echo $all;

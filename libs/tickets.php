<?php
include('../views/body/head.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Mapa de calor con Leaflet</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
</head>

<body>
    <main role="main" class="main-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <h2 class="h5 page-title">Mapa de Tickets</h2>
                        </div>
                        <div class="col-auto">
                            <label for=""><i class="mdi mdi-calendar"></i> <span id="date"></span> - <i class="mdi mdi-clock-outline"></i> <span id="time"></span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="row my-4">
                        <!-- Small table -->
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <input type="date" id="fdesde" value="<?= date('Y-m-d', strtotime('-7 day')) ?>" class="form-control col-md-2 ml-3 mr-2">
                                        <input type="date" id="fhasta" value="<?= date('Y-m-d') ?>" class="form-control col-md-2 mr-2">
                                        <div class="form-group col-auto">
                                            <button class="btn btn-primary" onclick="obtenerFechas()"><i class="mdi mdi-magnify"></i> BUSCAR</button>
                                        </div>
                                        <div class="form-group" id="loaderData">
                                            <div class="loader"></div>
                                        </div>
                                   <!--  </div>
                                    <div id="map" style="width: 100%; height: 900px;z-index:1;border-radius:4px;"></div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
include('../../views/body/footer.php');
require_once('vendor/autoload.php');
?>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../functions/prueba/main.js"></script>

<?php
$client = new \GuzzleHttp\Client();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'ss_intranet';

$mysqli = new mysqli($host, $user, $password, $database);
$mysqli->set_charset("utf8mb4");
set_time_limit(100000);
if ($mysqli->connect_error) {
  die('Error de conexión: ' . $mysqli->connect_error);
}


$desde = $fechaActual = date("Y-m-d");
$hasta = $fechaActual = date("Y-m-d");
$responselpc = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/tickets?page=1&altaHasta=' . $hasta . '&altaDesde=' . $desde, [
  'headers' => [
    'accept' => 'application/json',
    'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
  ],
]);

$jsonDataLPc = $responselpc->getBody();
$dataLPc = json_decode($jsonDataLPc, true);

$ultimapaginac = $dataLPc['last_page'];

//-------------------------------------------end---------------------------------------------//

for ($i = 1; $i <= $ultimapaginac; $i++) {

  $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/tickets?page=' . $i . '&altaHasta=' . $hasta . '&altaDesde=' . $desde, [
    'headers' => [
      'accept' => 'application/json',
      'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
    ],
  ]);

  $jsonData = $response->getBody();
  $data = json_decode($jsonData, true);

  foreach ($data['data'] as $cobranza) {
    $ticket_id = '';
    $ticket_dia = '';
    $ticket_hora = '';
    $ticket_cliente = '';
    $ticket_subcategoria = '';
    $ticket_estado = '';


    $ticket_id = $cobranza['ticket_id'];
    $ticket_dia = $cobranza['ticket_dia'];
    $ticket_hora = $cobranza['ticket_hora'];
    $ticket_cliente = $cobranza['ticket_cliente'];
    $ticket_subcategoria = $cobranza['ticket_subcategoria'];
    $ticket_estado = $cobranza['ticket_estado'];
    $ticket_finalizado = $cobranza['ticket_finalizado'];


    $query = "SELECT * FROM ticket WHERE ticket_id='$ticket_id'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
      $sql = "UPDATE ticket 
      SET ticket_dia='$ticket_dia',
      ticket_hora='$ticket_hora',
      ticket_cliente='$ticket_cliente',
      ticket_subcategoria='$ticket_subcategoria',
      ticket_estado='$ticket_estado',
      ticket_finalizado='$ticket_finalizado'     
      where ticket_id='$ticket_id'";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
      } else {
        echo "Error al actualizar categoria: " . $mysqli->error;
      }
    } else {
      $sql = "INSERT into ticket (ticket_id,ticket_dia,ticket_hora,ticket_cliente,ticket_subcategoria,ticket_estado,ticket_finalizado)
      values ('$ticket_id' ,'$ticket_dia','$ticket_hora','$ticket_cliente','$ticket_subcategoria','$ticket_estado','$ticket_finalizado')";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro insertado con éxito";
      } else {
        echo "Error al insertar registro: " . $mysqli->error;
      }
    }
  }
}






$mysqli->close();




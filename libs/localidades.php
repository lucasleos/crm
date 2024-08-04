<?php
require_once('vendor/autoload.php');

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




//-------------------------------------tickets-----------------------------------------//
$responselpc = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/localidades', [
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

  $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/localidades', [
    'headers' => [
      'accept' => 'application/json',
      'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
    ],
  ]);

  $jsonData = $response->getBody();
  $data = json_decode($jsonData, true);

  foreach ($data['data'] as $localidad) {
    $id_localidad = '';
    $localidad_nombre = '';
    $localidad_provincia = '';
    $localidad_cliente = '';
    $localidad_sucursal = '';
   

    $id_localidad = $localidad['localidad_id'];
    $localidad_nombre = $localidad['localidad_nombre'];
    $localidad_provincia = $localidad['localidad_provincia'];
    $localidad_cliente = $localidad['localidad_cliente'];
    $localidad_sucursal = $localidad['localidad_sucursal'];
    

    $query = "SELECT * FROM localidad WHERE id_localidad='$id_localidad'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
      $sql = "UPDATE localidad 
      SET localidad_nombre='$localidad_nombre',
      localidad_provincia='$localidad_provincia',
      localidad_cliente='$localidad_cliente',
      localidad_sucursal='$localidad_sucursal'      
      where id_localidad='$id_localidad'";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
      } else {
        echo "Error al actualizar categoria: " . $mysqli->error;
      }
    } else {
      $sql = "INSERT into localidad (id_localidad,localidad_nombre,localidad_provincia,localidad_cliente,localidad_sucursal)
      values ('$id_localidad' ,'$localidad_nombre','$localidad_provincia','$localidad_cliente','$localidad_sucursal')";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro insertado con éxito";
      } else {
        echo "Error al insertar registro: " . $mysqli->error;
      }
    }
  }
}






$mysqli->close();

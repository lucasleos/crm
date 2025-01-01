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



/***********************categoria**************** */
 $responsecat = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/categorias-cliente', [
  'headers' => [
    'accept' => 'application/json',
    'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
  ],
]);

$jsonDatacat = $responsecat->getBody();
$datacat = json_decode($jsonDatacat, true);
foreach ($datacat['data'] as $cliente) {

  $id_categoria = $cliente['cliente_categoria_id'];
  $descripcion = $cliente['cliente_categoria_nombre'];
  $corporativo = $cliente['clienteCategoriaCorporativo'];
  $query = "SELECT * FROM CATEGORIA_CLIENTE WHERE ID_CATEGORIA=$id_categoria";

  $result = $mysqli->query($query);
  if ($result->num_rows > 0) {
    $sql = "UPDATE categoria_cliente 
    SET descripcion='$descripcion',
        corporativo='$corporativo'
    where id_categoria='$id_categoria'";

    if ($mysqli->query($sql) === TRUE) {
      echo "Registro actualizado con éxito";
    } else {
      echo "Error al actualizar categoria: " . $mysqli->error;
    }
  } else {


    $sql = "INSERT into categoria_cliente (id_categoria,descripcion,corporativo)
          values ('$id_categoria' ,'$descripcion','$corporativo')";

    if ($mysqli->query($sql) === TRUE) {
      echo "Registro insertado con éxito";
    } else {
      echo "Error al insertar categoria: " . $mysqli->error;
    }
  }
}
 
$fdesde = date('Y-m-d', strtotime($fechaActual . ' - 15 days'));
$fhasta = date('Y-m-d');

/**********START LAST PAGE**********/

  $responseLP = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/clientes?cortado=N&altaDesde='.$fdesde.'&altaHasta='.$fhasta, [
  'headers' => [
    'accept' => 'application/json',
    'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
  ],
]);

$jsonDataLP = $responseLP->getBody();
$dataLP = json_decode($jsonDataLP, true);

$ultimapagina = $dataLP['last_page']; 
 
/**********END LAST PAGE**********/
 /* $ultimapagina200 = $ultimapagina - 10; */
  $ultimapagina200 = 1;
for ($i = $ultimapagina200; $i <= $ultimapagina; $i++) {
 
  $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/clientes?page=' . $i, [
    'headers' => [
      'accept' => 'application/json',
      'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
    ],
  ]);

  $jsonData = $response->getBody();
  $data = json_decode($jsonData, true);

  foreach ($data['data'] as $cliente) {

    $cliente_id_anatod = $cliente['cliente_id'];
    $cliente_nombre = $mysqli->real_escape_string($cliente['cliente_nombre']);
    $cliente_apellido = $mysqli->real_escape_string($cliente['cliente_apellido']);
    $cliente_domiciliofiscal = $mysqli->real_escape_string($cliente['cliente_domiciliofiscal']);
    $cliente_domicilio_real = $mysqli->real_escape_string($cliente['cliente_domicilioreal']);
    $cliente_tipoiva = $cliente['cliente_tipoiva'];
    $cliente_dni_cuit = $cliente['cliente_dnicuit'];
    $telefono = $cliente['cliente_telefono_celular'];
    $email = $cliente['cliente_email'];
    $saldo = $cliente['cliente_saldo'];
    $fec_alta = $cliente['cliente_alta'];
    $fec_habilitacion = $cliente['cliente_habilitacion'];
    $fec_corte = $cliente['cliente_corte'];
    $cliente_cortado = $cliente['cliente_cortado'];
    $id_categoria = $cliente['cliente_categoria'];
    $cliente_cdcbu_cbu = $cliente['cliente_cdcbu_cbu'];
    $cliente_localidadreal=$cliente['cliente_localidadreal'];
    $cliente_usa_medio_pago = $cliente['cliente_usa_medio_pago'];
    $cliente_localidadfiscal = $cliente['cliente_localidadfiscal'];
    $ubicacion = explode(',', $cliente['cliente_domicilio_latlng']);
    if (count($ubicacion) >= 2) {
        $cliente_lat = $ubicacion[0];
        $cliente_lon = $ubicacion[1];
    } else {
      $cliente_lat='';
      $cliente_lon='';
    }
    $query = "SELECT * FROM cliente WHERE cliente_id_anatod='$cliente_id_anatod'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
      $sql = "UPDATE cliente 
      SET cliente_nombre='$cliente_nombre',
          cliente_apellido='$cliente_apellido',
          cliente_domiciliofiscal='$cliente_domiciliofiscal',
          cliente_domicilio_real='$cliente_domicilio_real',
          cliente_tipoiva='$cliente_tipoiva',
          cliente_dni_cuit='$cliente_dni_cuit',
          telefono='$telefono',
          email='$email',
          saldo='$saldo',
          fec_alta='$fec_alta',
          fec_habilitacion='$fec_habilitacion',
          fec_corte='$fec_corte',
          cliente_cortado='$cliente_cortado',
          id_categoria='$id_categoria',
          cliente_cdcbu_cbu='$cliente_cdcbu_cbu',
          cliente_usa_medio_pago='$cliente_usa_medio_pago',
          cliente_latitud='$cliente_lat',
          cliente_longitud='$cliente_lon',
          id_localidad='$cliente_localidadreal',
          id_localidad_fiscal='$cliente_localidadfiscal'
      where cliente_id_anatod='$cliente_id_anatod'";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
      } else {
        echo "Error al actualizar categoria: " . $mysqli->error;
      }
    } else {
      $sql = "INSERT into cliente (cliente_id_anatod,cliente_nombre,cliente_apellido,cliente_domiciliofiscal,cliente_domicilio_real,cliente_tipoiva,cliente_dni_cuit,telefono,email,saldo,fec_alta,fec_habilitacion,fec_corte,cliente_cortado,id_categoria,cliente_cdcbu_cbu,cliente_usa_medio_pago, cliente_latitud, cliente_longitud,id_localidad,id_localidad_fiscal)
      values ('$cliente_id_anatod' ,'$cliente_nombre','$cliente_apellido','$cliente_domiciliofiscal','$cliente_domicilio_real','$cliente_tipoiva','$cliente_dni_cuit','$telefono','$email','$saldo','$fec_alta','$fec_habilitacion','$fec_corte','$cliente_cortado','$id_categoria','$cliente_cdcbu_cbu','$cliente_usa_medio_pago','$cliente_lat','$cliente_lon','$cliente_localidadreal','$cliente_localidadfiscal')";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro insertado con éxito";
      } else {
        echo "Error al insertar registro: " . $mysqli->error;
      }
    }
  }
}  







$mysqli->close();

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


$desde = '2023-10-01';
$hasta = '2023-10-31';

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



/**********START LAST PAGE**********/

$responseLP = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/clientes?page=1', [
  'headers' => [
    'accept' => 'application/json',
    'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
  ],
]);

$jsonDataLP = $responseLP->getBody();
$dataLP = json_decode($jsonDataLP, true);

$ultimapagina = $dataLP['last_page'];

/**********END LAST PAGE**********/
 $ultimapagina200=$ultimapagina-15; 
for ($i = $ultimapagina200; $i <= $ultimapagina; $i++) {
/* for ($i = 1; $i <= $ultimapagina; $i++) { */
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
    $cliente_cdcbu_cbu=$cliente['cliente_cdcbu_cbu'];
    $cliente_usa_medio_pago=$cliente['cliente_usa_medio_pago'];
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
          cliente_usa_medio_pago='$cliente_usa_medio_pago'
      where cliente_id_anatod='$cliente_id_anatod'";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
      } else {
        echo "Error al actualizar categoria: " . $mysqli->error;
      }
    } else {
      $sql = "INSERT into cliente (cliente_id_anatod,cliente_nombre,cliente_apellido,cliente_domiciliofiscal,cliente_domicilio_real,cliente_tipoiva,cliente_dni_cuit,telefono,email,saldo,fec_alta,fec_habilitacion,fec_corte,cliente_cortado,id_categoria,cliente_cdcbu_cbu,cliente_usa_medio_pago)
      values ('$cliente_id_anatod' ,'$cliente_nombre','$cliente_apellido','$cliente_domiciliofiscal','$cliente_domicilio_real','$cliente_tipoiva','$cliente_dni_cuit','$telefono','$email','$saldo','$fec_alta','$fec_habilitacion','$fec_corte','$cliente_cortado','$id_categoria','$cliente_cdcbu_cbu','$cliente_usa_medio_pago')";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro insertado con éxito";
      } else {
        echo "Error al insertar registro: " . $mysqli->error;
      }
    }
  }
} 

//----------------------------------ultima pagina de api factura---------------------------//
$responselpf = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/facturas?page=1&altaHasta=' . $hasta . '&altaDesde=' . $desde, [
  'headers' => [
    'accept' => 'application/json',
    'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
  ],
]);

$jsonDataLPf = $responselpf->getBody();
$dataLPf = json_decode($jsonDataLPf, true);

$ultimapaginaf = $dataLPf['last_page'];

//-------------------------------------------end---------------------------------------------//

for ($i = 1; $i <= $ultimapaginaf; $i++) {

  $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/facturas?page=' . $i . '&altaHasta=' . $hasta . '&altaDesde=' . $desde, [
    'headers' => [
      'accept' => 'application/json',
      'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
    ],
  ]);

  $jsonData = $response->getBody();
  $data = json_decode($jsonData, true);

  foreach ($data['data'] as $factura) {

    $factura_id = $factura['factura_id'];
    $factura_puntoventa = $mysqli->real_escape_string($factura['factura_puntoventa']);
    $factura_importe = $mysqli->real_escape_string($factura['factura_importe']);
    $factura_cliente = $mysqli->real_escape_string($factura['factura_cliente']);
    $factura_fecha = $mysqli->real_escape_string($factura['factura_fecha']);
    $factura_usuario = $factura['factura_usuario'];
    $factura_anulada = $factura['factura_anulada'];
    $factura_1vencimiento = $factura['factura_1vencimiento'];
    $factura_2vencimiento = $factura['factura_2vencimiento'];
    $factura_detalle = $factura['factura_detalle'];
    $factura_ip = $factura['factura_ip'];
    $factura_cae = $factura['factura_cae'];
    $factura_iva21 = $factura['factura_iva21'];
    $factura_iva10 = $factura['factura_iva10'];
    $factura_liquidacion = $factura['factura_liquidacion'];
    $factura_dnicuit = $factura['factura_dnicuit'];
    $factura_razon_social = $factura['factura_razon_social'];
    $factura_localidad = $factura['factura_localidad'];
    $factura_domicilio =  $mysqli->real_escape_string($factura['factura_domicilio']);
    $factura_iva = $factura['factura_iva'];
    $factura_moneda = $factura['factura_moneda'];
    $factura_consolidado = $factura['factura_consolidado'];
    $factura_clienteiva = $factura['factura_clienteiva'];
    $factura_tipo = $factura['factura_tipo'];
    $factura_numero = $factura['factura_numero'];
    $query = "SELECT * FROM factura WHERE factura_id='$factura_id'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
      $sql = "UPDATE factura 
      SET factura_puntoventa='$factura_puntoventa',
      factura_importe='$factura_importe',
      factura_cliente='$factura_cliente',
      factura_fecha='$factura_fecha',
      factura_usuario='$factura_usuario',
      factura_anulada='$factura_anulada',
      factura_1vencimiento='$factura_1vencimiento',
      factura_2vencimiento='$factura_2vencimiento',
      factura_detalle='$factura_detalle',
      factura_ip='$factura_ip',
      factura_cae='$factura_cae',
      factura_iva21='$factura_iva21',
      factura_iva10='$factura_iva10',
      factura_liquidacion='$factura_liquidacion',
      factura_dnicuit='$factura_dnicuit',
      factura_razon_social='$factura_razon_social',
      factura_localidad='$factura_localidad',
      factura_domicilio='$factura_domicilio',
      factura_iva='$factura_iva',
      factura_moneda='$factura_moneda',
      factura_consolidado='$factura_consolidado',
      factura_clienteiva='$factura_clienteiva',
      factura_tipo='$factura_tipo',
      factura_numero='$factura_numero'
      where factura_id='$factura_id'";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
      } else {
        echo "Error al actualizar categoria: " . $mysqli->error;
      }
    } else {
      $sql = "INSERT into factura (factura_id,factura_puntoventa,factura_importe,factura_cliente,factura_fecha,factura_usuario,factura_anulada,factura_1vencimiento,factura_2vencimiento,factura_detalle,factura_ip,factura_cae,factura_iva21,factura_iva10,factura_liquidacion,factura_dnicuit,factura_razon_social,factura_localidad,factura_domicilio,factura_iva,factura_moneda,factura_consolidado,factura_clienteiva,factura_tipo,factura_numero)
      values ('$factura_id' ,'$factura_puntoventa','$factura_importe','$factura_cliente','$factura_fecha','$factura_usuario','$factura_anulada','$factura_1vencimiento','$factura_2vencimiento','$factura_detalle','$factura_ip','$factura_cae','$factura_iva21','$factura_iva10','$factura_liquidacion','$factura_dnicuit','$factura_razon_social','$factura_localidad','$factura_domicilio','$factura_iva','$factura_moneda','$factura_consolidado','$factura_clienteiva','$factura_tipo','$factura_numero')";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro insertado con éxito";
      } else {
        echo "Error al insertar registro: " . $mysqli->error;
      }
    }
  }
}

//----------------------------------ultima pagina de api cobranza---------------------------//
$responselpc = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/cobranzas?page=1&altaHasta=' . $hasta . '&altaDesde=' . $desde, [
  'headers' => [
    'accept' => 'application/json',
    'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
  ],
]);

$jsonDataLPc = $responselpc->getBody();
$dataLPc = json_decode($jsonDataLPc, true);

$ultimapaginac = $dataLPc['last_page'];

//-------------------------------------------end---------------------------------------------//
/*$ultimapagina200=$ultimapaginaf-15;  */
/* $ultimapagina200 = 1; */
for ($i = 1; $i <= $ultimapaginac; $i++) {

  $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/cobranzas?page=' . $i . '&altaHasta=' . $hasta . '&altaDesde=' . $desde, [
    'headers' => [
      'accept' => 'application/json',
      'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
    ],
  ]);

  $jsonData = $response->getBody();
  $data = json_decode($jsonData, true);

  foreach ($data['data'] as $cobranza) {
    $cobranza_id='';
    $cobranza_fecha='';
    $cobranza_cliente='';
    $cobranza_usuario='';
    $cobranza_descripcion='';
    $cobranza_consolidada='';
    $cobranza_importe='';
    
    $cobranza_id = $cobranza['cobranza_id'];
    $cobranza_fecha = $cobranza['cobranza_fecha'];
    $cobranza_cliente = $cobranza['cobranza_cliente'];
    $cobranza_usuario = $cobranza['cobranza_usuario'];
    $cobranza_descripcion = $cobranza['cobranza_descripcion'];
    $cobranza_consolidada = $cobranza['cobranzaConsolidar'];
    $cobranza_importe = $cobranza['cobranza_importe'];

    $query = "SELECT * FROM cobranza WHERE cobranza_id='$cobranza_id'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
      $sql = "UPDATE cobranza 
      SET cobranza_fecha='$cobranza_fecha',
      cobranza_cliente='$cobranza_cliente',
      cobranza_usuario='$cobranza_usuario',
      cobranza_descripcion='$cobranza_descripcion',
      cobranza_consolidada='$cobranza_consolidada',
      cobranza_importe='$cobranza_importe'
      where cobranza_id='$cobranza_id'";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
      } else {
        echo "Error al actualizar categoria: " . $mysqli->error;
      }
    } else {
      $sql = "INSERT into cobranza (cobranza_id,cobranza_fecha,cobranza_cliente,cobranza_usuario,cobranza_descripcion,cobranza_consolidada,cobranza_importe)
      values ('$cobranza_id' ,'$cobranza_fecha','$cobranza_cliente','$cobranza_usuario','$cobranza_descripcion','$cobranza_consolidada','$cobranza_importe')";

      if ($mysqli->query($sql) === TRUE) {
        echo "Registro insertado con éxito";
      } else {
        echo "Error al insertar registro: " . $mysqli->error;
      }
    }


    $query = "SELECT * FROM rel_fact_cobranza WHERE cobranza_id='$cobranza_id'";
    $result = $mysqli->query($query);
    if ($result->num_rows == 0) {

      $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/cobranza/' . $cobranza_id . '/consolidado', [
        'headers' => [
          'accept' => 'application/json',
          'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
        ],
      ]);

      $jsonDatafcob = $response->getBody();
      $datafcob = json_decode($jsonDatafcob, true);



      foreach ($datafcob['data'] as $factura) {
        $factura_id = $factura['cuenta_corriente_consolidado_factura'];
        $sql = "INSERT into rel_fact_cobranza (cobranza_id,factura_id)
      values ('$cobranza_id' ,'$factura_id')";

        if ($mysqli->query($sql) === TRUE) {
          echo "Registro relacion insertado con éxito";
        } else {
          echo "Error al insertar relcion registro: " . $mysqli->error;
        }
      }
    } else {
      $response = $client->request('GET', 'https://5ovwkv3q1m.execute-api.sa-east-1.amazonaws.com/prod/cobranza/' . $cobranza_id . '/consolidado', [
        'headers' => [
          'accept' => 'application/json',
          'x-api-key' => 'mojEu45nVV39nGvDLhChW9MTe2rLmIUi4JZJabUD',
        ],
      ]);

      $jsonDatafcob = $response->getBody();
      $datafcob = json_decode($jsonDatafcob, true);



      foreach ($datafcob['data'] as $factura) {

        $factura_id = $factura['cuenta_corriente_consolidado_factura'];
        $query = "SELECT * FROM rel_fact_cobranza WHERE cobranza_id='$cobranza_id' and factura_id=$factura_id";
        $result = $mysqli->query($query);
        if ($result->num_rows == 0) {
          $sql = "INSERT into rel_fact_cobranza (cobranza_id,factura_id)
          values ('$cobranza_id' ,'$factura_id')";

          if ($mysqli->query($sql) === TRUE) {
            echo "Registro relacion insertado con éxito";
          } else {
            echo "Error al insertar relcion registro: " . $mysqli->error;
          }
        } else {
          if ($cobranza_id != NULL) {
            $sql = "UPDATE rel_fact_cobranza SET factura_id='$factura_id' WHERE cobranza_id='$cobranza_id'";
          }
          if ($mysqli->query($sql) === TRUE) {
            echo "Registro relacion actualizado con éxito";
          } else {
            echo "Error al actualizacion relcion registro: " . $mysqli->error;
          }
        }
      }
    }
  }
}
//-------------------------------------relacion-----------------------------------------//





$mysqli->close();

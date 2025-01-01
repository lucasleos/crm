<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "crm";

$conexion = mysqli_connect($servidor, $usuario, $contrasena, $base_datos);

mysqli_set_charset($conexion, "utf8");

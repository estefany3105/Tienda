<?php
include 'funciones.php';
$config = require 'conexion1.php';
$resultado = [
  'error' => false,
  'mensaje' => ''
];
  $codigo = $_GET['codigo'];
  $consultaSQL = "DELETE FROM productos WHERE codigo =" . $codigo;

  $sentencia = $db->prepare($consultaSQL);
  $sentencia->execute();
  header('Location: index.php');
?>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TIENDA TORRES</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
<link rel="stylesheet" href="css/custom.css">
  <body>
<div class="container mt-2">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger" role="alert">
        <?= $resultado['mensaje'] ?>
      </div>
    </div>
  </div>
</div>
</body>
</html>

<?php
include 'funciones.php';
csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}
if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El producto ' . escapar($_POST['producto']) . ' ha sido agregado con éxito'
  ];
  $config = require 'conexion1.php';
    $productos = [
      "producto"   => $_POST['producto'],
      "categoria" => $_POST['categoria'],
      "stock"    => $_POST['stock'],
      "precio"     => $_POST['precio'],
    ];
    $consultaSQL = "INSERT INTO productos (producto, categoria, stock, precio)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($productos)) . ")";

    $sentencia = $db->prepare($consultaSQL);
    $sentencia->execute($productos);
}
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
<?php
if (isset($resultado)) {
  ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Agregar un producto</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="producto">Producto</label>
          <input type="text" name="producto" id="producto" class="form-control">
        </div>
        <div class="form-group">
          <label for="categoria">Categoria</label>
          <input type="text" name="categoria" id="categoria" class="form-control">
        </div>
        <div class="form-group">
          <label for="stock">Stock</label>
          <input type="text" name="stock" id="stock" class="form-control">
        </div>
        <div class="form-group">
          <label for="precio">Precio</label>
          <input type="text" name="precio" id="precio" class="form-control">
        </div>
        <div class="form-group">
          <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
          <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>

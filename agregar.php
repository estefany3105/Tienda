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

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $productos = [
      "producto"   => $_POST['producto'],
      "categoria" => $_POST['categoria'],
      "stock"    => $_POST['stock'],
      "precio"     => $_POST['precio'],
    ];

    $consultaSQL = "INSERT INTO productos (producto, categoria, stock, precio)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($productos)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($productos);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>

<?php include 'templates/header.php'; ?>

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

<?php include 'templates/footer.php'; ?>
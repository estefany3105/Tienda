<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

if (!isset($_GET['codigo'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'El producto no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $productos = [
      "codigo"        => $_GET['codigo'],
      "producto"    => $_POST['producto'],
      "categoria"  => $_POST['categoria'],
      "stock"     => $_POST['stock'],
      "precio"      => $_POST['precio']
    ];
    
    $consultaSQL = "UPDATE productos SET
        producto = :producto,
        categoria = :categoria,
        stock = :stock,
        precio = :precio,
        actualizado = NOW()
        WHERE codigo = :codigo";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($productos);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $codigo = $_GET['codigo'];
  $consultaSQL = "SELECT * FROM productos WHERE codigo =" . $codigo;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $productos = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$productos) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el producto';
  }

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
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
if ($resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success" role="alert">
          ¡Éxito! Producto actualizado.
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($productos) && $productos) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Actualizando el registro del producto</h2>
        <hr>
        <form method="post">
          <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" name="producto" id="producto" value="<?= escapar($productos['producto']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="categoria">Categoria</label>
            <input type="text" name="categoria" id="categoria" value="<?= escapar($productos['categoria']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="stock">Stock</label>
            <input type="text" name="stock" id="stock" value="<?= escapar($productos['stock']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="precio">Precio</label>
            <input type="text" name="precio" id="precio" value="<?= escapar($productos['precio']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>
  </body>
  </html>

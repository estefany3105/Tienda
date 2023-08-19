<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['producto'])) {
    $consultaSQL = "SELECT * FROM productos WHERE producto LIKE '%" . $_POST['producto'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM productos";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $productos = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['producto']) ? 'Administrar productos (' . $_POST['producto'] . ')' : 'Administrar poductos';
?>

<?php include "templates/header.php"; ?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
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
    
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="producto" name="producto" placeholder="Buscar por producto" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>

<div class="container">  
 <div class="table-wrapper">
    <div class="table-title">
  <div class="row">
    <div class="col-md-6">
      <h2 ><?= $titulo ?></h2>
   </div>
   <div class="col-sm-6">
						<a href="agregar.php" class="btn btn-success" ><i class="material-icons">&#xE147;</i> <span>Agregar nuevo producto</span></a>
					</div>
</div>
</div>
   <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Categoria</th>
            <th>Stock</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($productos && $sentencia->rowCount() > 0) {
            foreach ($productos as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["codigo"]); ?></td>
                <td><?php echo escapar($fila["producto"]); ?></td>
                <td><?php echo escapar($fila["categoria"]); ?></td>
                <td><?php echo escapar($fila["stock"]); ?></td>
                <td><?php echo escapar($fila["precio"]); ?></td>
                <td>
                  <a href="<?= 'eliminar.php?codigo=' . escapar($fila["codigo"]) ?>">🗑️</a>
                  <a href="<?= 'editar.php?codigo=' . escapar($fila["codigo"]) ?>">✏️</a>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>
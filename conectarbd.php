<?php
$opciones = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
try{
        $db = new PDO('mysql:host=localhost;port=3306;dbname=TIENDA','root','',$opciones);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexion exitosa<br>";
} catch(PDOException $e){
    echo'¡Upss!Fallo la conexion:' ,$e->getMessage();
}
?>

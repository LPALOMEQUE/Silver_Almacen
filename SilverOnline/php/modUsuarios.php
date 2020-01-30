<?php
session_start();
require_once "Conexion.php";

$con = conexion();

$nombre = $_POST['NOMBRE'];
$apellidoP = $_POST['apellidoP'];
$apellidoM = $_POST['apellidoM'];
$calle = $_POST['CALLE'];
$numCalle = $_POST['numCalle'];
$cp = $_POST['CP'];
$ciudad = $_POST['CIUDAD'];
$estado = $_POST['ESTADO'];
$cel = $_POST['CEL'];
$email = $_POST['EMAIL'];

$ID = $_SESSION['ID_USER'];
$MAIL = $_SESSION['Email'];

$sql = "UPDATE user SET NOMBRE='$nombre', apellidoP='$apellidoP', apellidoM='$apellidoM',CALLE='$calle',numCalle=$numCalle,CP='$cp', CIUDAD='$ciudad',ESTADO='$estado', CEL='$cel' WHERE ID_USER='$ID' AND EMAIL='$MAIL'";

// EL echo NOS RETORNA UN 0 O UN 1 DEPENDIENDO SI EJECUTA O NO EL COMANDO
echo $result = mysqli_query($con,$sql);

?>

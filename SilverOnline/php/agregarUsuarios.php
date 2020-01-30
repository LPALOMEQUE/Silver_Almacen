<?php

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
$pass = $_POST['PASS'];
$roll = $_POST['ROLL'];

$sql = "INSERT INTO USER (EMAIL,NOMBRE,apellidoP,apellidoM,CALLE,numCalle,CP,CIUDAD,ESTADO,CEL,PASS,CATEGORY_USER) VALUES ('$email','$nombre','$apellidoP','$apellidoM','$calle',$numCalle,$cp,'$ciudad','$estado','$cel','$pass','$roll')";

// EL echo NOS RETORNA UN 0 O UN 1 DEPENDIENDO SI EJECUTA O NO EL COMANDO
echo $result = mysqli_query($con,$sql);

?>

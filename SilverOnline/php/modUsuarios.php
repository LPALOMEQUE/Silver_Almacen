<?php
session_start();
require_once "Conexion.php";

$con = conexion();

$nombre = $_POST['NOMBRE'];
$nombre_recibe = $_POST['NOMBRE_RECIBE'];
$calle = $_POST['CALLE'];
$numCalle = $_POST['numCalle'];
$cp = $_POST['CP'];
$ciudad = $_POST['CIUDAD'];
$estado = $_POST['ESTADO'];
$cel = $_POST['CEL'];
$email = $_POST['EMAIL'];

if ($_SESSION['ID_USER'] == 'ADMIN') {
  $ID = $_SESSION['ID_USER'];
}
else{
  $ID = $_SESSION['ID_CLIENTE'];
}

$MAIL = $_SESSION['Email'];
$BD = '01';

$sql = "UPDATE CLIE01 SET NOMBRE='$nombre',
ADDENDAF='$nombre_recibe',
CALLE='$calle',
NUMEXT=$numCalle,
CODIGO='$cp',
LOCALIDAD='$ciudad',
MUNICIPIO = '$ciudad',
ESTADO='$estado',
TELEFONO='$cel'
WHERE CLAVE='$ID'";

$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){
  echo "1";
  sqlsrv_close($con);
}else{
  echo "2";
  sqlsrv_close($con);
}

?>

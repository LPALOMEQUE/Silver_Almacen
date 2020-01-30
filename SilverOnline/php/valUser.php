<?php
session_start();
// $_SESSION["ID_USER"] = 0;
require_once "Conexion.php";

$con = conexion();

$email = $_POST['EMAIL'];
$pass = $_POST['PASS'];



$sql = "SELECT
CLAVE,
CRUZAMIENTOS_ENVIO AS CORREO,
CRUZAMIENTOS_ENVIO2 AS PASS,
MODELO AS ROLL
 FROM CLIE13
 WHERE CRUZAMIENTOS_ENVIO ='$email' AND CRUZAMIENTOS_ENVIO2='$pass'";

$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){
  while ($user = sqlsrv_fetch_array($res)) {

    if ($email == $user['CORREO'] && $pass == $user['PASS']) {
      echo 1;
      $_SESSION["ID_USER"] = $user['CLAVE'];
      $_SESSION["Email"] = $user['CORREO'];
      $_SESSION["status"] = $user['ROLL'];
    }
  }
}

?>

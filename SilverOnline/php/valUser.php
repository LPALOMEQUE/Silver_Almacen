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
 FROM CLIE01
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
else{

  $sql2 = "SELECT
          ID,
          USUARIO,
          CLAVE,
          NIVEL AS ROLL
          FROM SOUSUARIOS
          WHERE USUARIO ='$email' AND CLAVE='$pass'";

  $res2 =  sqlsrv_query($con, $sql2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

  if (0 !== sqlsrv_num_rows($res2)){
    while ($sousuario = sqlsrv_fetch_array($res2)) {

      if ($email == $sousuario['USUARIO'] && $pass == $sousuario['CLAVE']) {
        echo 1;
        $_SESSION["ID_USER"] = $sousuario['ID'];
        $_SESSION["Email"] = $sousuario['USUARIO'];

        if($sousuario['ROLL'] == 0){

          $_SESSION["status"] = 'ADMIN';

        }else{

          $_SESSION["status"] = 'COMUN';

        }
      }
    }
}
}
?>

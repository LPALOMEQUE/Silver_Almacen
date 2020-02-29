<?php
session_start();

$BD = '01';
$i=0;



if (isset($_SESSION['ID_ARTICLES'])) {
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}




if (isset($_SESSION['ID_ARTICLES'])) {
  foreach ($ID_ARTICLES as $key => $item) {
    // ==============ID===================
    $id= $item['id'];
    // ===================================

    // ==============Cant_art===================
    $cantidad_art_cart = $item['cantidad'];
    // =========================================

    require_once "php/Conexion.php";
    $con = conexion();

    $sql = "SELECT EXIST,DESCR,CVE_ART FROM INVE" .$BD. " where CVE_ART= '$id'";

    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($arti = sqlsrv_fetch_array($res)) {

        $exist_BD =  $arti['EXIST'];
        $array_stock[$i] = $exist_BD;


        $i++;
      }
    }
    sqlsrv_close($con);
  }

if (in_array(0,$array_stock)) {
  echo 1;
}
else{
  echo 0;
}

}

?>

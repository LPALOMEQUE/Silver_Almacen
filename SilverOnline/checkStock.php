<?php
session_start();

$BD = '01';
$i=0;

$validador = 'exitoso';

if (isset($_SESSION['ID_ARTICLES'])) {
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}

if (isset($_SESSION['ID_ARTICLES'])) {
  foreach ($ID_ARTICLES as $key => $item) {
    // ==============ID===================
    $id= $item['id'];
    $stock_cart = $item['cantidad'];
    // ===================================

    // ==============Cant_art===================
    $cantidad_art_cart = $item['cantidad'];
    // =========================================

    require_once "php/Conexion.php";
    $con = conexion();

    $sql = "SELECT
    M.EXIST,
    I.DESCR
    FROM INVE" .$BD. " I
    LEFT JOIN MULT" .$BD. " M ON M.CVE_ART = I.CVE_ART
    where
    M.EXIST > 0 AND
    M.CVE_ALM = 1 AND
    I.CVE_ART= '$id'";

    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($arti = sqlsrv_fetch_array($res)) {

        $exist_BD =  $arti['EXIST'];
        $array_stock_BD[$i] = $exist_BD;
        $array_cantidad_cart[$i] = $stock_cart;
        if($stock_cart > $exist_BD){


        }

        $i++;
      }
    }
    sqlsrv_close($con);
  }

// echo "STOCK BASE   ";
// var_dump($array_stock_BD);
// echo "------------ STOCK CARRITO ";
// var_dump($array_cantidad_cart);
// echo "------------";

$cantidad  = count($array_cantidad_cart)-1;

  for ($i=0; $i <= $cantidad ; $i++) {
    // echo "-----------";
    // echo $array_cantidad_cart[$i] . ' <=  ' . $array_stock_BD[$i];
    // echo "-----------";
    if ($array_cantidad_cart[$i] <= $array_stock_BD[$i]) {

      // echo $validador;
    }
    else{
      // echo "********";
    echo   $validador = 'error';
      break;
    }

  }




// if (in_array(0,$array_stock_BD)) {
//   echo 1;
// }
// else{
//   echo 0;
// }

}

?>

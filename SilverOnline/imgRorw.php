<?php

require_once "php/Conexion.php";
$con = conexion();
$BD = '01';

$sql="SELECT
I.EXIST,
I.CVE_ART,
I.DESCR as Nombre,
PP.PRECIO AS ULT_COSTO,
I.CVE_IMAGEN,
I.DESCR as Descripcion
FROM INVE" .$BD. " I
LEFT JOIN MULT" .$BD. " M ON M.CVE_ART = I.CVE_ART
INNER JOIN PRECIO_X_PROD" .$BD. " PP ON PP.CVE_ART = I.CVE_ART
WHERE I.EXIST > 0 AND
M.CVE_ALM = 1 AND
PP.CVE_PRECIO = 2
ORDER BY I.CVE_ART";

$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){
  while ($category = sqlsrv_fetch_array($res)) {
    $EXISTENCIA = $category['EXIST'];
    $precio = $category['ULT_COSTO'];

    $nombre_fichero = 'images/large/'.$category['CVE_IMAGEN'].'.jpg';

if (file_exists($nombre_fichero)) {

    ?>
    <input type="text" id="<?php echo $category['CVE_ART']  ?>" name="" value="<?php echo $category['Descripcion'] ?>">


    <?php
  }
  else {

  }
  }
  sqlsrv_close($con);
}
?>

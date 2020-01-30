<?php

require_once "Conexion.php";

$con = conexion();

  $nomArt = $_POST['NomArt'];
  $descArt = $_POST['DescArt'];
  $barCode = $_POST['BarCode'];
  $modelArt = $_POST['ModelArt'];
  $marcaArt = $_POST['MarcaArt'];
  $precioArt = $_POST['PrecioArt'];
  $categoria = $_POST['Categoria'];
  $subCatego = $_POST['SubCatego'];
  $statusArt = $_POST['StatusArt'];
  $nombreImg = $_POST['NombreImg'];


  // $sql = "INSERT INTO USER (EMAIL,PASS)  VALUES ('$email','$pass')";
  $sql = "INSERT INTO ".
   " articles ".
    "(ID_CATEGORY,".
    "ID_SUB_CATEGORY,".
    "NAME_ART, DESCRIPTION,".
    "ID_BRAND,".
    "MODEL_ART,".
    "BARCODE,".
    "PRICE,".
    "STATUS,".
    "USER_ADD,".
    "DATE_ADD,".
    "USER_UPDATE,".
    "DATE_UPDATE,".
    "USER_DELETE,".
    "DATE_DELETE,".
    "URL_IMAGE ) ".
    "VALUES ".
    "($categoria,".
    "$subCatego,".
    "'$nomArt',".
    "'$descArt',".
    "$marcaArt,".
    "'$modelArt',".
    "'$barCode',".
    "$precioArt,".
    " $statusArt,".
    "'FER',".
    "NOW(),".
    "NULL,".
    "NULL,".
    "NULL,".
    "NULL,".
    "'img/product-img/$nombreImg')";
  // EL echo NOS RETORNA UN 0 O UN 1 DEPENDIENDO SI EJECUTA O NO EL COMANDO
  echo $result = mysqli_query($con,$sql);

?>

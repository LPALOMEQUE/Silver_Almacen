<?php
$NuevaClave = "";

$nombre = $_POST['NOMBRE'];
$apellidoP = $_POST['apellidoP'];
$apellidoM = $_POST['apellidoM'];
$calle = $_POST['CALLE'];
$numCalle = $_POST['numCalle'];
$cp = $_POST['CP'];
$ciudad = $_POST['CIUDAD'];
$estado = $_POST['ESTADO'];
$cel = $_POST['CEL'];
$nombre_recibe = $_POST['NOMBRE_RECIBE'];
$apellidoP_recibe = $_POST['apellidoP_Recibe'];
$apellidoM_recibe = $_POST['apellidoM_Recibe'];
$email = $_POST['EMAIL'];
$pass = $_POST['PASS'];
$roll = $_POST['ROLL'];


require_once "Conexion.php";
$con = conexion();

// $id = $item['id'];
$sql = "SELECT TOP 1 CLAVE  FROM CLIE13 WHERE CLAVE LIKE 'WEB-%' ORDER BY CLAVE DESC";
$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){

  while ($fila = sqlsrv_fetch_array($res)) {
    $identificador = $fila['CLAVE'];

    $str = $identificador;
    $array = (explode("-",$str));

    $num = $array[1]+1;
    //echo $num+1;
    $claveOld = $array[0];
    //echo $CVE;
    $NuevaClave = $claveOld .'-'. $num;

  }
  sqlsrv_close($con);
}
else {

  sqlsrv_close($con);
}

require_once "Conexion.php";
$con = conexion();
$sql2=" INSERT INTO CLIE13
(CLAVE,
  STATUS,
  NOMBRE,
  RFC,
  CALLE,
  NUMINT,
  NUMEXT,
  CRUZAMIENTOS,
  CRUZAMIENTOS2,
  COLONIA,
  CODIGO,
  LOCALIDAD,
  MUNICIPIO,
  ESTADO,
  PAIS,
  NACIONALIDAD,
  REFERDIR,
  TELEFONO,
  CLASIFIC,
  FAX,
  PAG_WEB,
  CURP,
  CVE_ZONA,
  IMPRIR,
  MAIL,
  NIVELSEC,
  ENVIOSILEN,
  EMAILPRED,
  DIAREV,
  DIAPAGO,
  CON_CREDITO,
  DIASCRED,
  LIMCRED,
  SALDO,
  LISTA_PREC,
  CVE_BITA,
  ULT_PAGOD,
  ULT_PAGOM,
  ULT_PAGOF,
  DESCUENTO,
  ULT_VENTAD,
  ULT_COMPM,
  FCH_ULTCOM,
  VENTAS,
  CVE_VEND,
  CVE_OBS,
  TIPO_EMPRESA,
  MATRIZ,
  PROSPECTO,
  CALLE_ENVIO,
  NUMINT_ENVIO,
  NUMEXT_ENVIO,
  -- CORREO
  CRUZAMIENTOS_ENVIO,
  -- PASS
  CRUZAMIENTOS_ENVIO2,
  COLONIA_ENVIO,
  LOCALIDAD_ENVIO,
  MUNICIPIO_ENVIO,
  ESTADO_ENVIO,
  PAIS_ENVIO,
  CODIGO_ENVIO,
  CVE_ZONA_ENVIO,
  REFERENCIA_ENVIO,
  CUENTA_CONTABLE,
  -- NOMBRE_RECIBE
  ADDENDAF,
  ADDENDAD,
  NAMESPACE,
  METODODEPAGO,
  NUMCTAPAGO,
  -- ROLL
  MODELO,
  DES_IMPU1,
  DES_IMPU2,
  DES_IMPU3,
  DES_IMPU4,
  DES_PER,
  LAT_GENERAL,
  LON_GENERAL,
  LAT_ENVIO,
  LON_ENVIO,
  FINGERPRINT)
  VALUES
  (
    '$NuevaClave',
    'A',
    '$nombre' + ' ' + '$apellidoP' + ' ' + '$apellidoM',
    ' ',
    '$calle',
    ' ',
    '$numCalle',
    ' ',
    ' ',
    ' ',
    '$cp',
    '$ciudad',
    '$ciudad',
    '$estado',
    ' ',
    ' ',
    ' ',
    '$cel',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    0,
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    0,
    0,
    0,
    0,
    0,
    ' ',
    0,
    -- getdate()
    0,
    ' ',
    0,
    ' ',
    0,
    -- getdate()
    0,
    ' ',
    0,
    ' ',
    0,
    ' ',
    ' ',
    ' ',
    ' ',
    '$email',
    '$pass',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    '$nombre_recibe' + ' ' + '$apellidoP_recibe' + ' ' + '$apellidoM_recibe',
    ' ',
    ' ',
    ' ',
    ' ',
    '$roll',
    ' ',
    ' ',
    ' ',
    ' ',
    ' ',
    0,
    0,
    0,
    0,
    ' ')";

    $res =  sqlsrv_query($con, $sql2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      echo "1";
      sqlsrv_close($con);
    }else{
      echo "2";
      sqlsrv_close($con);
    }


    ?>

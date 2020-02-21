<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';

setlocale(LC_ALL,"es_ES");
$anio = date("Y");
$mes = date("m");
$dia = date("d");

$fecha_php =$anio . $mes . $dia;
// region carrito_compra

session_start();
$aCarrito = array();
$TotalxArtGlobal = 0;
$costoEnvio = 0;
$totalP =0;
$vtaTotal = 0;
$ID = '';
$BD = '01';
$ID_MOV = 0;
$CVE_DOC = '';


if (isset($_SESSION['ID_CLIENTE'])) {
  $id_cliente = $_SESSION['ID_CLIENTE'];
}


// PRECIO CON DESCUENTO (SUPER PRECIO)
$ID_PRECIO = 2;

// FILTRADO POR PRECIO DEPENDIENDO DEL TIPO DE USUARIO
if(isset($_SESSION['status'])){
  if($_SESSION["status"] == 'ADMIN'){
    // PRECIO NORMAL
    $ID_PRECIO = 1;
  }
}

if (isset($_POST['VACIAR_LOGIN'])) {
  unset($_SESSION['ID_USER']);
  unset($_SESSION['Email']);
  // session_destroy();
}

if (isset($_SESSION['ID_ARTICLES'])) {
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}

//Imprimimos datos globales del carrito
if (isset($_SESSION['ID_ARTICLES'])) {
  require_once "php/Conexion.php";
  $con = conexion();
  foreach($ID_ARTICLES as $key => $item){

    $id = $item['id'];
    $sql = "SELECT PRECIO AS ULT_COSTO FROM PRECIO_X_PROD" .$BD. " WHERE CVE_ART = '$id' AND  CVE_PRECIO = $ID_PRECIO";
    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($arti = sqlsrv_fetch_array($res)) {

        $precioNormal = $arti['ULT_COSTO'];
        $TotalxArtGlobal += $precioNormal * $item['cantidad'];
        $vtaTotal = $TotalxArtGlobal + $_COOKIE['express'];
      }
    }
  }
  sqlsrv_close($con);
}


if (isset($_POST['MONTO'])) {
  setcookie('express',$_POST['MONTO'],$iTemCad);
  $costoEnvio = $_COOKIE['express'];
}

$ID = $_SESSION['ID_USER'];
$MAIL = $_SESSION['Email'];
require_once "php/Conexion.php";
$con = conexion();

require_once "php/Conexion.php";
$con = conexion();
$MAIL = $_SESSION['Email'];

$sql = "SELECT
C.CLAVE,
C.CRUZAMIENTOS_ENVIO AS CORREO,
C.NOMBRE,
SU.NOMBRE AS VENDEDOR,
C.CALLE,
C.NUMEXT,
C.CODIGO,
C.LOCALIDAD,
C.ESTADO,
C.TELEFONO
FROM CLIE" .$BD. " C
INNER JOIN SOUSUARIOS SU ON SU.VEND = C.CVE_VEND
WHERE c.CLAVE='$id_cliente'";


$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){
  while ($user = sqlsrv_fetch_array($res)) {
    $email = $user['CORREO'];
    $nombre = $user['NOMBRE'];
    $vendedor = $user['VENDEDOR'];
    $calle = $user['CALLE'];
    $numCalle = $user['NUMEXT'];
    $cp = $user['CODIGO'];
    $ciudad = $user['LOCALIDAD'];
    $estado = $user['ESTADO'];
    $cel = $user['TELEFONO'];
  }
}

sqlsrv_close($con);
// endRegion carrito_compra

require_once "php/Conexion.php";
$con = conexion();
$sql2 = "SELECT MAX(CONVERT(INT,SUBSTRING(CONVERT(VARCHAR,CVE_DOC), 5, 500))) AS CVE_DOC FROM PAR_FACTP" .$BD. " WHERE CVE_DOC LIKE 'WEBP%' ORDER BY CVE_DOC DESC";
$res2 =  sqlsrv_query($con, $sql2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res2)){

  while ($fila = sqlsrv_fetch_array($res2)) {


    $num = $fila['CVE_DOC']+1;

    //echo $CVE;
    $CVE_DOC =  'WEBP'. $num;

  }
}
else {
  $CVE_DOC = 'WEBP1';
}
sqlsrv_close($con);



// instanci de pdf
$mpdf = new \Mpdf\Mpdf();
$fecha = "  " .date("d") . "/" . date("m") . "/" . date("Y");

//cabecera del pdf
$mpdf->SetHTMLHeader('
<div style="text-align: right; font-weight: bold;">
ATREVETE A GANAR MAS...
</div>');

//pie del pdf
$mpdf->SetHTMLFooter('
<table width="100%">
<tr>
<td width="33%">{DATE j-m-Y}</td>
<td width="33%" align="center">{PAGENO}/{nbpg}</td>
<td width="33%" style="text-align: right;">'.$CVE_DOC.'</td>
</tr>
</table>');



// almacenara todo el cuerpo html
$dataHTML = '<link rel="stylesheet" href="style.css">';

$dataHTML .= '<img src="img/core-img/silverEvolution.png"><br/><br/>';

$dataHTML .= '<h1>Comprobante de Pedido</h1>';

$dataHTML .= '<br/>'.'<h3><i><strong>Vendedor:</strong></i></h3>';
$dataHTML .= '' .$vendedor. '<br/>';


$dataHTML .= '<br/>'.'<h3><i><strong>Cliente:</strong></i></h3>';
$dataHTML .= '' .$nombre. '<br/>';

$dataHTML .= '<br/>'.'<h3><i><strong>Dirección del cliente...</strong></i></h3>';
$dataHTML .= '
<table style="width:100%">


<tr>
<td width="5%">Calle: ' . $calle . '</td>
<td width="5%">Número: #' . $numCalle . '</td>
<td width="5%">Código Postal: ' . $cp . '</td>
</tr>

<tr>
<td width="5%">Ciudad: ' . $ciudad . '</td>
<td width="5%">Estado: ' . $estado . '</td>
</tr>
</table>
';

$dataHTML .= '<br/>'.'<h3><i><strong>Información de contacto...</strong></i></h3>';
$dataHTML .= '
<table style="width:100%">

<tr>
<td width="5%">Correo: ' . $email . '</td>
</tr>
<tr>
<td width="5%">Celular: ' . $cel . '</td>
</tr>
</table>
';

$dataHTML .= '<h3><i><strong>Información del pedido...</strong></i></h3>';

$dataHTML .= '<strong>Folio de Consigna: </strong>#'.$CVE_DOC.'<br/>' ;
$dataHTML .= '<strong>Fecha del pedido:</strong> '. $fecha . '<br/><br/><br/>';

$dataHTML .= '<br/><br/>
<table style="width:100%">
<tr>
<td width="52%"><h3>Artículo</h3></td>
<td width="15%"><h3>P.U.</h3></td>
<td width="15%"><h3>Cantidad</h3></td>
<td width="18%"><h3>Precio x Art</h3></td>
</tr>
';

require_once "php/Conexion.php";
$con = conexion();
foreach ($ID_ARTICLES as $key => $item) {
  $id = $item['id'];
  $sql = "SELECT
  I.DESCR as Nombre,
  PP.PRECIO AS ULT_COSTO
  FROM INVE" .$BD. " I
  INNER JOIN PRECIO_X_PROD" .$BD. " PP ON PP.CVE_ART = I.CVE_ART
  WHERE
  I.CVE_ART = '$id' AND
  PP.CVE_PRECIO = $ID_PRECIO";

  $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
  if (0 !== sqlsrv_num_rows($res)){
    while ($arti = sqlsrv_fetch_array($res)) {
      $precioNormal = $arti['ULT_COSTO'];
      $TotalxArt = $precioNormal  * $item['cantidad'];
      $dataHTML .= '
      <tr>
      <td width="52%">'.$arti['Nombre'] .'</td>
      <td width="15%">$'. number_format($precioNormal,2) .'</td>
      <td width="15%">'.$item['cantidad'] .'</td>
      <td width="18%">$'. number_format($TotalxArt,2) .'</td>
      </tr>
      ';
    }
  }
}
sqlsrv_close($con);

$dataHTML .= '
<tr>
<td width="52%"> </td>
<td width="15%"> </td>
<td width="15%"> </td>
<td width="18%"> </td>
</tr>
';

$dataHTML .= '
<tr>
<td width="52%"></td>
<td width="15%"></td>
<td width="15%"><b><i>SUBTOTAL</i></b></td>
<td width="18%"><b><i>$'. number_format($TotalxArtGlobal,2) .'</i></b></td>
</tr>
';

$dataHTML .= '
<tr>
<td width="52%"></td>
<td width="15%"></td>
<td width="15%"><b><i>ENVÍO</i></b></td>
<td width="18%"><b><i>$'. number_format($_COOKIE['express'],2) .'</i></b></td>
</tr>
';

$dataHTML .= '
<tr>
<td width="52%"></td>
<td width="15%"></td>
<td width="15%"><b><i>TOTAL</i></b></td>
<td width="18%"><b><i>$'. number_format($vtaTotal,2) .'</i></b></td>
</tr>
';
$dataHTML .='</table>';

$html = mb_convert_encoding($dataHTML, 'UTF-8', 'UTF-8');
$mpdf -> WriteHTML($html);
// $mpdf -> WriteHTML($dataHTML);

//output
$pdf = $mpdf -> Output('','S');

//obtener informacion
$sendData = [
  'EMAIL' => $email,
  'idVenta' => $CVE_DOC
];

require_once "php/Conexion.php";
$con = conexion();
$i=1;
if (isset($_SESSION['ID_ARTICLES'])) {
  foreach ($ID_ARTICLES as $key => $item) {
    $id= $item['id'];
    $sql = "SELECT CVE_ART,COSTO_PROM FROM INVE" .$BD. " where CVE_ART='$id'";

    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($arti = sqlsrv_fetch_array($res)) {

        // echo "----";
        // echo 'id_cliente:' .$id_cliente;
        $id_cliente;
        // echo "----";
        // ECHO $CVE_DOC;
        $CVE_DOC;
        // echo "----";
        // echo $ID;
        $ID;
        // echo "----";
        // echo $fecha_php;
        $fecha_php;
        // echo "----";
        $SUPER_PRECIO_ART = $arti['COSTO_PROM'];
        $PRECIO_ART = $arti['COSTO_PROM']*3;
        $CANTIDAD_ART = $item['cantidad'];
        $TotalxArt = $PRECIO_ART * $item['cantidad'];
        $CVE_ART = $arti['CVE_ART'];

        // PASO 1
        $sql2 = "IF NOT EXISTS (SELECT CVE_DOC FROM PAR_FACTP" .$BD. "
        WHERE CVE_DOC = '$CVE_DOC' AND NUM_PAR = '$i') --EL 1 ES LA PARTIDA, EN ESTE CASO SERA LA VARIABLE AUTO INCREMENTABLE

        INSERT INTO PAR_FACTP" .$BD. "
        (CVE_DOC,
        NUM_PAR,
        CVE_ART,
        CANT,
        PXS,
        PREC,
        COST,
        IMPU1,
        IMPU2,
        IMPU3,
        IMPU4,
        IMP1APLA,
        IMP2APLA,
        IMP3APLA,
        IMP4APLA,
        TOTIMP1,
        TOTIMP2,
        TOTIMP3,
        TOTIMP4,
        DESC1,
        DESC2,
        DESC3,
        COMI,
        APAR,
        ACT_INV,
        NUM_ALM,
        POLIT_APLI,
        TIP_CAM,
        UNI_VENTA,
        TIPO_PROD,
        CVE_OBS,
        REG_SERIE,
        E_LTPD,
        TIPO_ELEM,
        NUM_MOV,
        TOT_PARTIDA,
        IMPRIMIR)
        VALUES
        ('$CVE_DOC', --CVE_DOC
        '$i',			--_NUM_PAR       EN ESTE CASO SERA LA VARIABLE AUTO INCREMENTABLE
        '$CVE_ART',  --CVE_ART
        '$CANTIDAD_ART',
        '0',			--VALOR FIJO
        '$PRECIO_ART',			--PRECIO UNITARIO DEL PRODUCTO,
        '$SUPER_PRECIO_ART',	--SUPER PRECIO
        '0',--Impu1		--VALOR FIJO
        '0',--IMPU2		--VALOR FIJO
        '0',--_IMPU3	--VALOR FIJO
        '0',--Impu4		--VALOR FIJO
        '4',--IMP1APLA	--VALOR FIJO
        '4',--_IMP2APLA	--VALOR FIJO
        '4',--IMP3APLA	--VALOR FIJO
        '0',--IMP4APLA	--VALOR FIJO
        '0',--TOTIMP1	--VALOR FIJO
        '0',--TOTIMP2	--VALOR FIJO
        '0',--TOTIMP3	--VALOR FIJO
        '0',--TOTIMP4	--VALOR FIJO
        '0',--DESC1		--VALOR FIJO
        '0',--DESC2		--VALOR FIJO
        '0',--DESC3		--VALOR FIJO
        '0',--COMI		--VALOR FIJO
        '0',--APAR		--VALOR FIJO
        'S',--ACT_INV	--VALOR FIJO
        '1',--NUM_ALM	--VALOR FIJO
        '',--POLIT_APLI	--VALOR FIJO
        '1',--TIP_CAM	--VALOR FIJO
        'pz',--UNI_VENTA--VALOR FIJO
        'P',--TIPO_PROD	--VALOR FIJO
        '0',--CVE_OBS	--VALOR FIJO
        '0',--REG_SERIE	--VALOR FIJO
        '0',--E_LTPD	--VALOR FIJO
        'N',--TIPO_ELEM	--VALOR FIJO
        (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE" .$BD. "),
        '$TotalxArt', --VALOR DEL PRODUCTO UNITARIO POR SU CANTIDAD TOTAL
        'S')";

        $res2 =  sqlsrv_query($con, $sql2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


        // PASO 2

        $sql3 = "UPDATE INVE" .$BD. " SET EXIST = ISNULL(EXIST,0) - $CANTIDAD_ART --CANTIDAD VENDIDA POR CADA ARTICULO
        , FCH_ULTVTA = '20200213',
        VTAS_ANL_C = ISNULL(VTAS_ANL_C,0) + $CANTIDAD_ART, --CANTIDAD VENDIDA POR CADA ARTICULO
        VTAS_ANL_M = ISNULL(VTAS_ANL_M,0) + $TotalxArt -- total de cantidad por articulo
        WHERE CVE_ART = '$CVE_ART'

        UPDATE MULT" .$BD. " SET EXIST = ISNULL(EXIST,0) - $CANTIDAD_ART --CANTIDAD VENDIDA POR CADA ARTICULO
        WHERE CVE_ART = '$CVE_ART' AND CVE_ALM = 1";

        $res3 =  sqlsrv_query($con, $sql3, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

        // PASO 3

        $sql4 = "INSERT INTO MINVE" .$BD. "
        (CVE_ART,
        ALMACEN,
        NUM_MOV,
        CVE_CPTO,
        FECHA_DOCU,
        TIPO_DOC,
        REFER,
        CLAVE_CLPV,
        VEND,
        CANT,
        CANT_COST,
        PRECIO,
        COSTO,
        AFEC_COI,
        REG_SERIE,
        UNI_VENTA,
        E_LTPD,
        EXISTENCIA,
        TIPO_PROD,
        FACTOR_CON,
        FECHAELAB,
        CVE_FOLIO,
        SIGNO,
        COSTEADO,
        COSTO_PROM_INI,
        COSTO_PROM_FIN,
        DESDE_INVE,
        MOV_ENLAZADO)
        VALUES
        ('$CVE_ART',	--CVE_ART
        '1',
        (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE" .$BD. "),
        '59',--_CVE_CPTO												--VALOR FIJO
        '',--FECHA_DOC
        'P',--TIPO_DOC
        '$CVE_DOC',--REFER ES $CVE_DOC
        '$id_cliente',--CLAVE_CLPV ESTA ES LA CLAVE DEL CLIENTE
        '$ID',--Vend														--VALOR FIJO
        '$CANTIDAD_ART',--Cant ES  LA CANTIDAD VENDIDA POR ARTICULO
        '0',--CANT_COST													--VALOR FIJO
        '$PRECIO_ART',--PREC ES EL PRECIO UNITARIO DEL PRODUCTO
        '$SUPER_PRECIO_ART',--COSTO ES EL SUPER PRECIO UNITARIO DEL PRODUCTO
        'N',--AFEC_COI													---VALOR FIJO
        '1',--REG_SERIE													---VALOR FIJO
        'pz',--UNI_VENTA												---VALOR FIJO
        '0',--E_LTPD													---VALOR FIJO
        (SELECT EXIST FROM MULT" .$BD. " WHERE CVE_ART = '$CVE_ART' AND CVE_ALM = 1),
        'P',--TIPO_PROD													---VALOR FIJO
        '1',--FACTOR_CON												---VALOR FIJO
        GETDATE(),
        (SELECT ISNULL(ULT_CVE,1) FROM TBLCONTROL" .$BD. " WHERE ID_TABLA = 32),
        '-1',--SIGNO													---VALOR FIJO
        'S',--COSTEADO													---VALOR FIJO
        '$SUPER_PRECIO_ART',--COSTO_PROM_INI ES EL SUPER PRECIO UNITARIO DEL PRODUCTO ($PRECIO_ART)
        '$SUPER_PRECIO_ART',--COSTO_PROM_FIN ES EL SUPER PRECIO UNITARIO DEL PRODUCTO ($PRECIO_ART)
        'N',--DESDE_INVE												---VALOR FIJO
        '0')--MOV_ENLAZADO												---VALOR FIJO";

        $res4 =  sqlsrv_query($con, $sql4, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

        // PASO 4

        $sql5 = "UPDATE TBLCONTROL" .$BD. " SET ULT_CVE = (SELECT ISNULL(MAX(NUM_MOV),0) FROM MINVE" .$BD. ") WHERE ID_TABLA = 44

        UPDATE TBLCONTROL" .$BD. " SET ULT_CVE = ISNULL(ULT_CVE,0) + 1  WHERE ID_TABLA = 32";

        $res5 =  sqlsrv_query($con, $sql5, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

        $i++;

      }
    }
  }

  // PASO 5

  $sql6 = "SELECT isnull(MAX(FOLIO),0) AS FOLIO FROM FACTP" .$BD. " where SERIE = 'WEBP'";

  $res6 =  sqlsrv_query($con, $sql6, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
  if (0 !== sqlsrv_num_rows($res6)){
    while ($f = sqlsrv_fetch_array($res6)) {


      if($f['FOLIO'] == 0){
        $folio = 1;
      }
      else{
        $folio = $f['FOLIO'] + 1;
      }
    }
  }

  $sql7 = "IF NOT EXISTS (SELECT CVE_DOC FROM FACTP" .$BD. "
  WHERE CVE_DOC = '$CVE_DOC')

  INSERT INTO FACTP" .$BD. "
  (CVE_CLPV,
  CVE_PEDI,
  FECHA_DOC,
  FECHA_ENT,
  FECHA_VEN,
  IMP_TOT1,
  IMP_TOT2,
  DES_FIN,
  COM_TOT,
  ACT_COI,
  NUM_MONED,
  TIPCAMB,
  IMP_TOT3,
  IMP_TOT4,
  PRIMERPAGO,
  RFC,
  AUTORIZA,
  FOLIO,
  SERIE,
  AUTOANIO,
  ESCFD,
  NUM_ALMA,
  ACT_CXC,
  TIP_DOC,
  CVE_DOC,
  CAN_TOT,
  CVE_VEND,
  FECHA_CANCELA,
  DES_TOT,
  ENLAZADO,
  NUM_PAGOS,
  DAT_ENVIO,
  CONTADO,
  DAT_MOSTR,
  CVE_BITA,
  BLOQ,
  FECHAELAB,
  CTLPOL,
  CVE_OBS,
  STATUS,
  TIP_DOC_E,
  FORMAENVIO,
  DES_FIN_PORC,
  DES_TOT_PORC,
  IMPORTE,
  COM_TOT_PORC,
  METODODEPAGO,
  NUMCTAPAGO,
  TIP_DOC_ANT,
  DOC_ANT,
  CONDICION)
  VALUES
  ('$id_cliente',--CVE_CLPV ES LA CLAVE DEL CLIENTE
  '',-- CVE_PEDI									---VALOR FIJO
  '$fecha_php',--FECHA_DOC  VARIABLE
  '$fecha_php',--FECHA_ENT  VARIABLE
  '$fecha_php',--FECHA_VEN  VARIABLE
  '0',--IMP_TOT1									---VALOR FIJO
  '0',--IMP_TOT2									---VALOR FIJO
  '0',--DES_FIN									---VALOR FIJO
  '0',--COM_TOT									---VALOR FIJO
  'N',--ACT_COI									---VALOR FIJO
  '1',--NUM_MONED									---VALOR FIJO
  '1',--TIPCAMB									---VALOR FIJO
  '0',--IMP_TOT3									---VALOR FIJO
  '0',--IMP_TOT4									---VALOR FIJO
  '0',--PRIMERPAGO								---VALOR FIJO
  '', --RFC										---VALOR FIJO
  '0',--AUTORIZA									---VALOR FIJO
  '$folio',--FOLIO VARIABLE
  'WEBP',--SERIE			VALOR FIJO PARA CONSIGNACION
  '',--AUTOANIO									---VALOR FIJO
  'N',--ESCFD										---VALOR FIJO
  '1',--NUM_ALMA									---VALOR FIJO
  'N',--ACT_CXC									---VALOR FIJO
  'P',--TIP_DOC									---VALOR FIJO
  '$CVE_DOC',--CVE_DOC
  '$TotalxArtGlobal',--CAN_TOT   ES LA CANTIDDA TOTAL DE LA VENTA VARIABLE
  '$ID',--CVE_VEND									---VALOR FIJO
  NULL,--FECHA_CANCELA							---VALOR FIJO
  '0',--DES_TOT									---VALOR FIJO
  'O',--ENLAZADO
  '1',--NUM_PAGOS									---VALOR FIJO
  '0',--DAT_ENVIO									---VALOR FIJO
  'S',--CONTADO									---VALOR FIJO
  '0',--DAT_MOSTR									---VALOR FIJO
  '0',--CVE_BITA									---VALOR FIJO
  'N',--BLOQ										---VALOR FIJO
  GETDATE(), --DIA ACTUAL
  '0',--CTLPOL									---VALOR FIJO
  '0',--CVE_OBS									---VALOR FIJO
  'E',--Status									---VALOR FIJO
  'O',--TIP_DOC_E									---VALOR FIJO
  'I',--FORMAENVIO								---VALOR FIJO
  '0',--DES_FIN_PORC								---VALOR FIJO
  '0',--DES_TOT_PORC								---VALOR FIJO
  '$TotalxArtGlobal',--Importe		CANTIDAD TOTAL DE VENTA VARIABLE
  '0',--COM_TOT_PORC								---VALOR FIJO
  '$id_cliente',--METODODEPAGO ACA SE LE PASARA LA CLAVE DEL CLIENTE (WEB-01)
  '$nombre',--NUMCTAPAGO				ACA IRA EL NOMBRE DE LA PERSONA QUE COMPRARA VARIABLE ($nombre)
  '',--TIP_DOC_ANT								---VALOR FIJO
  '',--DOC_ANT									---VALOR FIJO
  'CONSIGNACION')--CONDICION		---VALOR FIJO";

  $res7 =  sqlsrv_query($con, $sql7, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

  // PASO 6

  $sql8 = "INSERT INTO BITA" .$BD. "
  (CVE_BITA,
  CVE_CLIE,
  CVE_CAMPANIA,
  CVE_ACTIVIDAD,
  FECHAHORA,
  CVE_USUARIO,
  OBSERVACIONES,
  STATUS,
  NOM_USUARIO)
  Values
  (ISNULL((SELECT MAX(CVE_BITA) + 1 FROM BITA" .$BD. " ),1),
  '$id_cliente',--CVE_CLIE
  'VENTA SILVER_ONLINE CONSIGNA',--CVE_CAMPANIA			--VALOR FIJO
  '11',--CVE_ACTIVIDAD									--VALOR FIJO
  GETDATE(),
  'admin',--CVE_USUARIO									--VALOR FIJO
  'FACTP: $CVE_DOC   $$TotalxArtGlobal',--OBSERVACIONES  FOLIO DE VENTA(PAYPAL) Y MONTO TOTAL
  'F',--STATUS											--VALOR FIJO
  'WEBP');--NOM_USUARIO";

  $res8 =  sqlsrv_query($con, $sql8, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


  // PASO 7

  $sql9 = "UPDATE AFACT" .$BD. " SET
  PVTA_COM = ISNULL(PVTA_COM,0) + $TotalxArtGlobal, --CAN_TOT        EL 159 ES LA CANTIDAD TOTAL DE VENTA
  PDESCTO = ISNULL(PDESCTO,0) + 0 --DES_TOT                  EL 0 ES EL DESCUENTO
  WHERE CVE_AFACT = $mes --OBTENEMOS EL NUMERO DEL MES";

  $res9=  sqlsrv_query($con, $sql9, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

  // paso 8

  $sql10 = "INSERT INTO CUEN_M" .$BD. "
  (CVE_CLIE,
  REFER,
  NUM_CPTO,
  NUM_CARGO,
  CVE_OBS,
  NO_FACTURA,
  DOCTO,
  IMPORTE,
  FECHA_APLI,
  FECHA_VENC,
  AFEC_COI,
  STRCVEVEND,
  NUM_MONED,
  TCAMBIO,
  IMPMON_EXT,
  FECHAELAB,
  TIPO_MOV,
  CVE_BITA,
  SIGNO,
  USUARIO,
  ENTREGADA,
  FECHA_ENTREGA,
  STATUS,
  REF_SIST,
  CVE_AUT,
  BENEFICIARIO,
  NUMCTAPAGO_ORIGEN)
  VALUES
  ('$id_cliente',--_CVE_CLIE
  '$CVE_DOC',--REFER
  '25',--NUM_CPTO									--VALOR FIJO
  '1',											--VALOR FIJO
  '0',--CVE_OBS									--VALOR FIJO
  '$CVE_DOC',--NO_FACTURA
  '$CVE_DOC',--DOCTO VARIABLE
  '$TotalxArtGlobal',--IMPORTE			variable
  '$fecha_php',--FECHA_APLI
  '$fecha_php',--FECHA_VENC
  'N',--AFEC_COI									--VALOR FIJO
  '1',--STRCVEVEND								--VALOR FIJO
  '1',--NUM_MONED									--VALOR FIJO
  '1',--TCAMBIO									--VALOR FIJO
  '$TotalxArtGlobal',--IMPMON_EXT variable   CANTIDAD TOTAL DE VENTA
  GETDATE(),--FECHAELAB
  'C',--TIPO_MOV									--VALOR FIJO
  '0',--CVE_BITA									--VALOR FIJO
  '1',--SIGNO										--VALOR FIJO
  '0',--USUARI0									--VALOR FIJO
  'S',--ENTREGADA									--VALOR FIJO
  '$fecha_php',--FECHA_ENTREGA
  'A',											--VALOR FIJO
  'P',--REF_SIST									--VALOR FIJO
  '0',--CVE_AUT									--VALOR FIJO
  '$nombre',--BENEFICIARIO		NOMBRE DEL CLIENTE
  '$id_cliente')--NUMCTAPAGO_ORIGEN						ES LA CLAVE DEL CLIENTE";

  $res10=  sqlsrv_query($con, $sql10, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


  // PASO 9

  $sql11 = "UPDATE CLIE" .$BD. " SET
  SALDO = ISNULL(SALDO,0) + $TotalxArtGlobal, --EL 15557 ES LA VENTA TOTAL GLOBAL
  ULT_VENTAD = '$CVE_DOC', -- ES EL CVE_DOC
  ULT_COMPM = '$TotalxArtGlobal',--EL 15557 ES LA VENTA TOTAL GLOBAL
  FCH_ULTCOM = '$fecha_php',
  VENTAS = ISNULL(VENTAS,0) + $TotalxArtGlobal --EL 15557 ES LA VENTA TOTAL GLOBAL
  WHERE
  CLAVE = '$id_cliente' --ES LA CLAVE DEL CLIENTE";

  $res11 =  sqlsrv_query($con, $sql11, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


}

sendEmail($pdf, $sendData);

header('Location: index.php?vaciar=2');
die();

function sendEmail($pdf, $sendData){

  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'fernando18092105@gmail.com';                     // SMTP username  gerenciageneral@evolutionsilver.com
    $mail->Password   = '*******';                              // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->SMTPSecure = 'tls';
    $mail->Port  = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('fernando18092105@gmail.com');
    // $mail->addAddress('gerenciageneral@evolutionsilver.com');     // Add a recipient
    $mail->addAddress($sendData['EMAIL']);               // Name is optional
    // $mail->addReplyTo('gerenciageneral@evolutionsilver.com', 'Information');
    $mail->addCC('vgeneral736@gmail.com');
    // $mail->addCC('sistemas@evolutionsilver.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    $mail->addStringAttachment($pdf, $sendData['idVenta'].'.pdf');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Pedido: '. $sendData['idVenta'];
    $mail->Body    = 'Su pedido ha sido recibio, en breve nos pondremos en contacto para la validación de existencia.';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: ', $mail->ErrorInfo";
  }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <!-- Title  -->
  <title>Siler - Evolution | Email</title>

  <!-- Favicon  -->
  <link rel="icon" href="img/core-img/favicon.ico">


  <!-- scripts LFPO -->
  <script src="js/jquery/jquery-2.2.4.min.js"></script>
  <script src="js/funciones.js"></script>
  <script src="librerias/alertify/alertify.js"></script>
</head>
<body>
  <!-- <?php echo $dataHTML ?> -->

</body>
</html>

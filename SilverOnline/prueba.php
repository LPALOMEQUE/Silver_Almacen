<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION["ID_USER"]) || !isset($_COOKIE['express'])) {
  header('Location: index.php');
}

// verifica si el vendedor selecciono un cliente, de lo contrario no permite agregar productos
if (isset($_SESSION['status'])) {

  if ($_SESSION['status'] == 'ADMIN' && !isset($_SESSION["ID_CLIENTE"])) {
    header('Location: index.php?Vcs=4');
  }
  elseif(isset($_SESSION["ID_CLIENTE"]) && strlen($_SESSION['BUS_CLIENTE']) <= 10 ){
    header('Location: index.php?Vcs=4');

  }
}

if(!isset($_SESSION['ID_ARTICLES'])){
  header('Location: index.php');

}

setlocale(LC_ALL,"es_ES");
$anio = date("Y");
$mes = date("m");
$dia = date("d");

$fecha_php =$anio . $mes . $dia;
// region carrito_compra

session_start();
$aCarrito = array();
$sHTML = '';
$fPrecioTotal = 0;
$bagNumber = 0;
$TotalxArtGlobal = 0;
$costoEnvio = 0;
$totalP =0;
$vtaTotal = 0;
$ID = '';
$BD = '01';
$ID_MOV = 0;


$validador = 'Exitoso';


// $CVE_DOC = '';

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
    $sql = "SELECT COSTO_PROM FROM INVE" .$BD. " where CVE_ART='$id'";
    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($arti = sqlsrv_fetch_array($res)) {
        $TotalxArtGlobal += $arti['COSTO_PROM'] * $item['cantidad'];
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

$sql = "SELECT
CRUZAMIENTOS_ENVIO AS CORREO,
NOMBRE,
ADDENDAF AS NOMBRE_RECIBE,
CALLE,
NUMEXT,
CODIGO,
LOCALIDAD,
ESTADO,
TELEFONO
FROM CLIE" .$BD. "
WHERE CLAVE='$ID' AND CRUZAMIENTOS_ENVIO='$MAIL'";

$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){
  while ($user = sqlsrv_fetch_array($res)) {
    $email = $user['CORREO'];
    $nombre = $user['NOMBRE'];
    $nombreRecibe = $user['NOMBRE_RECIBE'];
    // $apellidoM = $user[4];
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

// ===========================================================================================================
    // VALIDACION DE STOCK
// ===========================================================================================================

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

  $sql = "SELECT EXIST,DESCR,CVE_ART FROM INVE" .$BD. " where CVE_ART= '$id'";

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
     $validador = 'error';
    break;
  }

}



echo 'Valor del validador: ' . ' ' . $validador;




  if($validador != 'error'){

$emailUser = $_GET['EMAIL'];
$paymentToken = $_GET['paymentToken'];
$paymentID = $_GET['paymentID'];

//print_r($_GET);

// ----------------------------------------------------------------------------------------------------------------
//PRUEBAS.......
$ClientID = "AQfqqbzkFvxShrOBEbcFqOB6uDjVlaFgIwpW2JEErSGMSQe1cCzMMHdhA6jYXqhnYGVzSsmI3BGYQF9G";
$Secret = "EIRbeX9Yv6ze9ozLPagaHsMvOmvdw_MWK2kPH-CYmcGnov-RssU2sEh4KFHd2DZfpQQ28d1s-rd5TydZ";

// PRODUCCION
// $ClientID = "AWkFACdq0h4aeDpN-yfYhlk4FxnpGYbLmX6rcVA5qo3N2ErxCp3GrPyQ1sWIwCR2EH6UubCHJfNnH84I";
// $Secret = "EDiXuARlbHy0D8LdGLpOTOFO7YLdrqk9oapqR2mmQxJfq9DIYESd84N7DyZq6LVr2Wnz-yRJVbXcmtsb";
// ----------------------------------------------------------------------------------------------------------------

// ----------------------------------------------------------------------------------------------------------------
//PRUEBAS.......
$login = curl_init("https://api.sandbox.paypal.com/v1/oauth2/token");

//PRODUCCION
// $login = curl_init("https://api.paypal.com/v1/oauth2/token");
// ----------------------------------------------------------------------------------------------------------------

curl_setopt($login,CURLOPT_RETURNTRANSFER,TRUE);

curl_setopt($login,CURLOPT_USERPWD,$ClientID.":".$Secret);

curl_setopt($login,CURLOPT_POSTFIELDS,"grant_type=client_credentials");

$respuesta = curl_exec($login);

// print_r($respuesta);

$objRespuesta = json_decode($respuesta);

$accessToken = $objRespuesta->access_token;

// print_r($accessToken);

// ----------------------------------------------------------------------------------------------------------------
//PRUEBAS.......
$venta = curl_init("https://api.sandbox.paypal.com/v1/payments/payment/".$_GET['paymentID']);

//PRODUCCION
// $venta = curl_init("https://api.paypal.com/v1/payments/payment/".$_GET['paymentID']);
// ----------------------------------------------------------------------------------------------------------------

curl_setopt($venta,CURLOPT_HTTPHEADER,array("Content-Type: application/json","Authorization: Bearer ".$accessToken));

curl_setopt($venta,CURLOPT_RETURNTRANSFER,TRUE);

$respuestaVenta = curl_exec($venta);

// print_r($respuestaVenta);

$objDatosTransaccion = json_decode($respuestaVenta);

$state = $objDatosTransaccion->state;
$email = $objDatosTransaccion->payer->payer_info->email;
$total = $objDatosTransaccion->transactions[0]->amount->total;
$currrency = $objDatosTransaccion->transactions[0]->amount->currency;
$idventa = $objDatosTransaccion->transactions[0]->related_resources[0]->sale->id;

curl_close($venta);
curl_close($login);

if ($state == 'approved') {

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
  <td width="33%" style="text-align: right;">'.$idventa.'</td>
  </tr>
  </table>');



  // almacenara todo el cuerpo html
  $dataHTML = '<link rel="stylesheet" href="style.css">';

  $dataHTML .= '<img src="img/core-img/silverEvolution.png"><br/><br/>';

  $dataHTML .= '<h1>Comprobante de Pedido</h1>';

  $dataHTML .= '<br/>'.'<h3><i><strong>Cliente:</strong></i></h3>';
  $dataHTML .= '' .$nombre. '<br/>';

  $dataHTML .= '<br/>'.'<h3><i><strong>Información de envío...</strong></i></h3>';
  $dataHTML .= '
  <table style="width:100%">
  <tr>
  <td width="5%">Quien recibe: ' . $nombreRecibe . '</td>
  <td width="5%"> </td>
  <td width="5%"> </td>
  </tr>

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
  <td width="5%">Correo: ' . $emailUser . '</td>
  </tr>
  <tr>
  <td width="5%">Celular: ' . $cel . '</td>
  </tr>
  </table>
  ';


  $dataHTML .= '<h3><i><strong>Información del pedido...</strong></i></h3>';

  $dataHTML .= '<strong>Folio de pedido: </strong>#' . $idventa . '<br/>' ;
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
    $sql = "SELECT DESCR as Nombre, COSTO_PROM FROM INVE" .$BD. " where CVE_ART='$id'";

    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($arti = sqlsrv_fetch_array($res)) {
        $TotalxArt = $arti['COSTO_PROM'] * $item['cantidad'];
        $dataHTML .= '
        <tr>
        <td width="52%">'.$arti['Nombre'] .'</td>
        <td width="15%">$'. number_format($arti['COSTO_PROM'],2) .'</td>
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
    'EMAIL' => $emailUser,
    'idVenta' => $idventa
  ];

  // OBTENEMOS LOS DATOS NECESARIOS DEL ARTICULO
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
          // ECHO $CVE_DOC = 'WEB'.$idventa;
          $CVE_DOC = 'WEB'.$idventa;
          // echo "----";
          // echo $ID;
          $ID;
          // echo "----";
          // echo $fecha_php;
          $fecha_php;
          $PRECIO_ART = $arti['COSTO_PROM'];
          $CANTIDAD_ART = $item['cantidad'];
          $TotalxArt = $arti['COSTO_PROM'] * $item['cantidad'];
          // $fecha = date (dd/mm/YYYY);
          $CVE_ART = $arti['CVE_ART'];

          // // PASO 1
          $sql2 = "IF NOT EXISTS (SELECT CVE_DOC FROM PAR_FACTR" .$BD. "
          WHERE CVE_DOC = '$CVE_DOC' AND NUM_PAR = $i)

          INSERT INTO PAR_FACTR" .$BD. "
          (CVE_DOC,
          NUM_PAR,
          CVE_ART,
          CANT,
          PXS,
          PREC,
          COST,
          IMPU1,
          IMPU2,
          IMPU3, IMPU4,
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
          ('$CVE_DOC',--CVE_DOC
          $i, --NUM_PAR
          '$CVE_ART', -- CVE_ART
          '$CANTIDAD_ART', --CANT
          0, --PXS
          '$PRECIO_ART', --PREC			ES EL PRECIO UNITARIO DEL ART SIN MUKTIPLICAR POR SU CANTIDAD
          '$PRECIO_ART', --COST			ES EL PRECIO UNITARIO DEL ART SIN MUKTIPLICAR POR SU CANTIDAD
          '0', --IMPU1
          '0', --IMPU2
          '0', --IMPU3
          '0', --IMPU4
          '4', --IMP1APLA
          '4', --IMP2APLA
          '4', --IMP3APLA
          '0', --IMP4APLA
          '0', --TOTIMP1
          '0', --TOTIMP2
          '0', --TOTIMP3
          '0', --TOTIMP4
          '0', --DESC1
          '0', --DESC2
          '0', --DESC3
          '0', --COMI
          '0', --APAR
          'S', --ACT_INV
          '1', --NUM_ALM
          '', --POLIT_APLI
          '1', --TIP_CAM
          'pz', --UNI_VENTA
          'P', --TIPO_PROD
          '0', --CVE_OBS
          '0', --REG_SERIE
          '0', --E_LTPD
          'N', --TIPO_ELEM
          (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE" .$BD. "), --NUM_MOV (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE)
          '$TotalxArt', --TOT_PARTIDA   ES LA CANTIDA DEL ARTICULO POR SU PRECIO   ----IMPORTANTE ESTO ES POR CADA ART QUE SE ENCUENTRE
          'S')";

          $res2 =  sqlsrv_query($con, $sql2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

          // PASO 2
          $sql3 = "UPDATE INVE" .$BD. " SET EXIST = ISNULL(EXIST,0) - $CANTIDAD_ART --el 1 es la cantidad vendida
          , FCH_ULTVTA = '$fecha_php',--día actual
          VTAS_ANL_C = ISNULL(VTAS_ANL_C,0) + $CANTIDAD_ART, -- el 1 es la cantidad vendida
          VTAS_ANL_M = ISNULL(VTAS_ANL_M,0) + $TotalxArt -- total de cantidad por articulo
          WHERE CVE_ART = '$CVE_ART' -- ALI000036OLes la clave del artículo";

          $res3 =  sqlsrv_query($con, $sql3, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

          // PASO 3
          $sql4 = "UPDATE MULT" .$BD. " SET EXIST = ISNULL(EXIST,0) - $CANTIDAD_ART --CANTIDAD DEL ART VENDIDO
          WHERE CVE_ART = '$CVE_ART' --CLAVE DELA ART
          AND CVE_ALM = 1 -- NUMERO DEL ALMACEN";

          $res4 =  sqlsrv_query($con, $sql4, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

          // PASO 4
          $CadenaMinve = "SELECT EXIST FROM MULT" .$BD. " WHERE
          CVE_ART = '$CVE_ART' --CLAVE DEL ART
          AND CVE_ALM = 1 -- NUM DE ALMACEN ";

          // PASO 5
          $sql6 = "INSERT INTO MINVE" .$BD. "
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
          ('$CVE_ART',--CVE_ART
          '1',--ALMACEN
          (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE" .$BD. "),--(SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE" .$BD. ") NUM_MOV
          '61',--CVE_CPTO				---VALOR FIJO
          '$fecha_php',--FECHA_DOCU
          'R',--TIPO_DOC				---VALOR FIJO
          '$CVE_DOC',--REFER
          '$ID',--CLAVE_CLPV  ESTA ES LA CLAVE DEL CLIENTE
          '  100',--VEND
          '$CANTIDAD_ART',--CANT
          '0',--CANT_COST
          '$PRECIO_ART',--PRECIO
          '$PRECIO_ART',--COSTO
          'N',--AFEC_COI				---VALOR FIJO
          '1',--REG_SERIE				---VALOR FIJO
          'pz',--UNI_VENTA
          '0',--E_LTPD				---VALOR FIJO
          (SELECT EXIST FROM MULT" .$BD. " WHERE CVE_ART = '$CVE_ART' AND CVE_ALM = 1),-- SELECT EXIST FROM MULT WHERE CVE_ART = 'ALI000036OL' AND CVE_ALM = 1     ...EXISTENCIA
          'P',--TIPO_PROD				---VALOR FIJO
          '1',--FACTOR_CON			---VALOR FIJO
          '$fecha_php',--FECHAELAB
          (SELECT ISNULL(ULT_CVE,1) FROM TBLCONTROL" .$BD. " WHERE ID_TABLA = 32), --(SELECT ISNULL(ULT_CVE,1) FROM TBLCONTROL13 WHERE ID_TABLA = 32),
          '-1',--SIGNO				---VALOR FIJO
          'S',--COSTEADO				---VALOR FIJO
          '$PRECIO_ART',--COSTO_PROM_INI
          '$PRECIO_ART',--COSTO_PROM_FIN
          'N',--DESDE_INVE			---VALOR FIJO
          '0')--MOV_ENLAZADO			---VALOR FIJO";

          $res6 =  sqlsrv_query($con, $sql6, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

          // PASO 6
          $sql7 = "UPDATE TBLCONTROL" .$BD. " SET ULT_CVE = (SELECT ISNULL(MAX(NUM_MOV),0) FROM MINVE" .$BD. ")
          WHERE ID_TABLA = 44";

          $res7 =  sqlsrv_query($con, $sql7, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

          $sql8 = "UPDATE TBLCONTROL" .$BD. " SET ULT_CVE = ISNULL(ULT_CVE,0) + 1
          WHERE ID_TABLA = 32";

          $res8 =  sqlsrv_query($con, $sql8, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));





          $i++;

        }
      }
    }

    // PASO 7

    $sql = "SELECT FOLIO FROM FACTR" .$BD. " where SERIE = 'WEB'";

    $res9 =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res9)){
      while ($f = sqlsrv_fetch_array($res9)) {
        $folio = $f['FOLIO'] + 1;
      }
    }
    else{
      $folio = 1;

    }

    $sql10 = "IF NOT EXISTS (SELECT CVE_DOC FROM FACTR" .$BD. "
    WHERE CVE_DOC = '$CVE_DOC')

    INSERT INTO FACTR" .$BD. "
    (CVE_CLPV,	--*
    CVE_PEDI,	--*
    FECHA_DOC,	--*
    FECHA_ENT,	--*
    FECHA_VEN,	--*
    IMP_TOT1,	--*
    IMP_TOT2,	--*
    DES_FIN,	--*
    COM_TOT,	--*
    ACT_COI,	--*
    NUM_MONED,	--*
    TIPCAMB,	--*
    IMP_TOT3,	--*
    IMP_TOT4,	--*
    PRIMERPAGO, --*
    RFC,		--*
    AUTORIZA,	--*
    FOLIO,		--*
    SERIE,		--*
    AUTOANIO,	--*
    ESCFD,		--*
    NUM_ALMA,	--*
    ACT_CXC,	--*
    TIP_DOC,	--*
    CVE_DOC,	--*
    CAN_TOT,	--*
    CVE_VEND,	--*
    FECHA_CANCELA, --*
    DES_TOT,	--*
    ENLAZADO,	--*
    NUM_PAGOS,	--*
    DAT_ENVIO,	--*
    CONTADO,	--*
    DAT_MOSTR,	--*
    CVE_BITA,	--*
    BLOQ,		--*
    FECHAELAB,	--*
    CTLPOL,		--*
    CVE_OBS,	--*
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
    ('$ID',--CVE_CLPV
    '',-- CVE_PEDI		---VALOR FIJO
    '$fecha_php',--FECHA_DOC
    '$fecha_php',--FECHA_ENT
    '$fecha_php',--FECHA_VEN
    '0',--IMP_TOT1		---VALOR FIJO ---------------------PREGUNTAR
    '0',--IMP_TOT2		---VALOR FIJO ---------------------PREGUNTAR
    '0',--DES_FIN		---VALOR FIJO
    '0',--COM_TOT		---VALOR FIJO
    'N',--ACT_COI		---VALOR FIJO
    '1',--NUM_MONED		---VALOR FIJO
    '1',--TIPCAMB		---VALOR FIJO
    '0',--IMP_TOT3		---VALOR FIJO ---------------------PREGUNTAR
    '0',--IMP_TOT4		---VALOR FIJO ---------------------PREGUNTAR
    '0',--PRIMERPAGO
    'RFC', --RFC
    '0',--AUTORIZA		---VALOR FIJO ---------------------PREGUNTAR	QUE ES?
    '$folio',--FOLIO
    'WEB',--SERIE
    '',--AUTOANIO		---VALOR FIJO
    'N',--ESCFD			---VALOR FIJO
    '1',--NUM_ALMA		---VALOR FIJO
    'N',--ACT_CXC		---VALOR FIJO
    'R',--TIP_DOC		---VALOR FIJO
    '$CVE_DOC',--CVE_DOC
    '$TotalxArtGlobal',--CAN_TOT CANTIDAD TOTAL DE VENTA
    '  100',--CVE_VEND		---VALOR FIJO
    NULL,--FECHA_CANCELA
    '0',--DES_TOT		---VALOR FIJO ---------------------PREGUNTAR
    'O',--ENLAZADO
    '1',--NUM_PAGOS		---VALOR FIJO
    '0',--DAT_ENVIO		---VALOR FIJO
    'S',--CONTADO		---VALOR FIJO
    '0',--DAT_MOSTR		---VALOR FIJO
    '0',--CVE_BITA		---VALOR FIJO
    'N',--BLOQ			---VALOR FIJO
    GETDATE(), --DIA ACTUAL
    '0',--CTLPOL		---VALOR FIJO
    '0',--CVE_OBS		---VALOR FIJO
    'E',--Status		---VALOR FIJO
    'O',--TIP_DOC_E		---VALOR FIJO
    'I',--FORMAENVIO	---VALOR FIJO
    '0',--DES_FIN_PORC	---VALOR FIJO
    '0',--DES_TOT_PORC	---VALOR FIJO
    '$TotalxArtGlobal',--Importe		CANTIDAD TOTAL DE VENTA
    '0',--COM_TOT_PORC	---VALOR FIJO
    '$ID',--METODODEPAGO ACA SE LE PASARA LA CLAVE DEL CLIENTE (WEB-01)
    '$nombre',--NUMCTAPAGO				ACA IRA EL NOMBRE DE LA PERSONA QUE COMPRARA
    '',--TIP_DOC_ANT	---VALOR FIJO
    '',--DOC_ANT		---VALOR FIJO
    'VENTA DIRECTA')--CONDICION		---VALOR FIJO
    ";

    $res10 =  sqlsrv_query($con, $sql10, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

    // PASO 8
    $sql11 = "INSERT INTO BITA" .$BD. "
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
    (ISNULL((SELECT MAX(CVE_BITA) + 1 FROM BITA" .$BD. "),1),--CVE_BITA
    '$ID',--CVE_CLIE
    'VENTA SILVER_ONLINE',--CVE_CAMPANIA	--VALOR FIJO
    '11',--CVE_ACTIVIDAD					--VALOR FIJO
    GETDATE(),--FECHAHORA					--VALOR FIJO
    'admin',--CVE_USUARIO					--VALOR FIJO
    'FACTR: $CVE_DOC   $$TotalxArtGlobal',--OBSERVACIONES  FOLIO DE VENTA(PAYPAL) Y MONTO TOTAL
    'F',--STATUS							--VALOR FIJO
    'WEB')--NOM_USUARIO";

    $res11 =  sqlsrv_query($con, $sql11, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

    // PASO 9
    $sql12 = "UPDATE AFACT" .$BD. "
    SET RVTA_COM = ISNULL(RVTA_COM,0) + $TotalxArtGlobal, --CAN_TOT        EL 160 ES LA CANTIDAD TOTAL DE VENTA
    RDESCTO = ISNULL(RDESCTO,0) + 0 --DES_TOT                  EL 0 ES EL DESCUENTO
    WHERE CVE_AFACT = $mes	--OBTENEMOS EL NUMERO DEL MES";

    $res12 =  sqlsrv_query($con, $sql12, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

    // PASO 10
    //
    //     $sql13 = "UPDATE FOLIOSF" .$BD. "
    // SET ULT_DOC = $idventa--REMISION LA 524
    // WHERE TIP_DOC = 'R' AND
    // SERIE = 'WEB'";
    //
    //     $res13 =  sqlsrv_query($con, $sql13, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

    // PASO 11
    $sql14 = "INSERT INTO CUEN_M" .$BD. "
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
    ('$ID',--CVE_CLIE
    '$CVE_DOC',--REFER
    '24',--NUM_CPTO					--VALOR FIJO
    '1',--NUM_CARGO					--VALOR FIJO
    '0',--CVE_OBS					--VALOR FIJO
    '$CVE_DOC',--NO_FACTURA
    '$CVE_DOC',--DOCTO
    '$TotalxArtGlobal',--IMPORTE										CANTIDAD TOTAL DE VENTA
    '$fecha_php',--FECHA_APLI
    '$fecha_php',--FECHA_VENC
    'N',--AFEC_COI					--VALOR FIJO
    '1',--STRCVEVEND				--VALOR FIJO
    '1',--NUM_MONED					--VALOR FIJO
    '1',--TCAMBIO		--VALOR FIJO
    '$TotalxArtGlobal',--IMPMON_EXT									CANTIDAD TOTAL DE VENTA
    GETDATE(),--FECHAELAB
    'C',--TIPO_MOV					--VALOR FIJO
    '0',--CVE_BITA					--VALOR FIJO
    '1',--SIGNO						--VALOR FIJO
    '0',--USUARI0					--VALOR FIJO
    'S',--ENTREGADA
    '$fecha_php',--FECHA_ENTREGA
    'A',--STATUS					--VALOR FIJO
    'R',--REF_SIST					--VALOR FIJO
    '0',--CVE_AUT					--VALOR FIJO
    '$nombre',--BENEFICIARIO				NOMBRE DEL CLIENTE
    '$ID')--NUMCTAPAGO_ORIGEN						ES LA CLAVE DEL CLIENTE";

    $res14 =  sqlsrv_query($con, $sql14, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


    // PASO 12

    $sql15 = "SELECT TOP 1
    ISNULL(NUM_CPTO, 0) as ID_MOV
    FROM CUEN_M" .$BD. "
    WHERE REFER = '$CVE_DOC' AND CVE_CLIE = '$ID'";

    $res15 =  sqlsrv_query($con, $sql15, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res9)){
      while ($CUEN_M = sqlsrv_fetch_array($res9)) {
        $ID_MOV = $CUEN_M['ID_MOV'];
      }
    }


    $sql16 = "SELECT ISNULL(MAX(NO_PARTIDA),0) + 1 AS NO_PAR
    FROM CUEN_DET" .$BD. "
    WHERE REFER = '$CVE_DOC'";

    $res16 =  sqlsrv_query($con, $sql16, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));

    // PASO 13

    $sql17 = "IF NOT EXISTS (SELECT REFER FROM
    CUEN_DET" .$BD. "
    WHERE REFER = '$CVE_DOC' AND
    CVE_CLIE = '$ID' AND
    ID_MOV = $ID_MOV AND -- EL ID_MOV ES EL QUE SALE DEL QUERY DE ARRIBA (STEP_12)
    NUM_CPTO = 10 AND -- VALOR FIJO
    NO_PARTIDA = 1) --CECHAR EN EL CODIGO SI EL NO_PARTIDA ES 1 SEMPRE O CAMBI EN OTRO CICLO

    INSERT INTO CUEN_DET" .$BD. "
    (CVE_CLIE,
    REFER,
    ID_MOV,
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
    CTLPOL,
    CVE_FOLIO,
    TIPO_MOV,
    SIGNO,
    CVE_AUT,
    USUARIO,
    NO_PARTIDA,
    REF_SIST,
    BENEFICIARIO,
    NUMCTAPAGO_ORIGEN)
    VALUES
    ('$ID',--CVE_CLIE
    '$CVE_DOC',--REFER				FOLIO DE VENTA
    ISNULL((SELECT TOP 1 ISNULL(NUM_CPTO,0) FROM CUEN_M" .$BD. " WHERE REFER = '$CVE_DOC' AND CVE_CLIE = '$ID'),25),
    '10',--NUM_CPTO					--VALOR FIJO
    '1',--NUM_CARGO					--VALOR FIJO
    '0',--CVE_OBS					--VALOR FIJO
    '$CVE_DOC',--NO_FACTURA
    '$CVE_DOC',--DOCTO
    '$TotalxArtGlobal',--fIMPORTE							MONTO TOTAL DE VENTA
    '$fecha_php',--FECHA_APLI
    '$fecha_php',--FECHA_VENC
    'N',--AFEC_COI					--VALOR FIJO
    '1',--STRCVEVEND				--VALOR FIJO
    '1',--NUM_MONED					--VALOR FIJO
    '1',--TCAMBIO					--VALOR FIJO
    '$TotalxArtGlobal',--IMPMON_EXT								CANTIDAD TOTAL DE VENTA
    GETDATE(),--FECHAELAB
    '0',--CTLPOL					--VALOR FIJO
    '',--CVE_FOLIO					--VALOR FIJO
    'A',--TIPO_MOV					--VALOR FIJO
    '-1',--SIGNO					--VALOR FIJO
    '0',--CVE_AUT					--VALOR FIJO
    '0',--USUARIO					--VALOR FIJO
    '1',--NO_PARTIDA								HAY QUE REVISAR SI ESTE VALOR ES FIJO O DEPENDE EN VDD DE LAS PARTIDAS QUE TIENE LA VENTA
    'R',--REF_SIST					--VALOR FIJO
    '$nombre',--BENEFICIARIO			NOMBRE DEL CLIENTE
    '$ID')--NUMCTAPAGO_ORIGEN					CLAVE DEL CLIENT";

    $res17 =  sqlsrv_query($con, $sql17, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


    // PASO 14

    $sql18 = "UPDATE CLIE" .$BD. "
    SET
    SALDO = ISNULL(SALDO,0) + $TotalxArtGlobal,--316 ES LA CANTIDd TOTAL DE LA VENTA QUE SE ESTA HACIENDO
    ULT_VENTAD = '$CVE_DOC', --FOLIO DE VENTA QUE SERA EL DE PAYPAL
    ULT_COMPM = '$TotalxArtGlobal',--316 ES LA CANTIDd TOTAL DE LA VENTA QUE SE ESTA HACIENDO
    FCH_ULTCOM = '$fecha_php',
    VENTAS = ISNULL(VENTAS,0) + $TotalxArtGlobal--316 ES LA CANTIDd TOTAL DE LA VENTA QUE SE ESTA HACIENDO
    WHERE CLAVE = '$ID'--CLAVE DEL CLIENTE";

    $res18 =  sqlsrv_query($con, $sql18, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


    sqlsrv_close($con);
  }

  sendEmail($pdf, $sendData);
  // echo "
  // <script type='text/javascript'>
  // alert('El pago se aprobó de forma correcta...');
  // </script>";

  header('Location: index.php?vaciar=3');
  die();
}

else{
  echo "<script>
  window.location= 'index.php';
  alert('Ocurrio un error con el pago');
  </script>";
}

}else {
  header('Location: checkout.php?Del=8');
  die();

}

function sendEmail($pdf, $sendData){

  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'fernando18092105@gmail.com';                     // SMTP username  gerenciageneral@evolutionsilver.com
    $mail->Password   = 'ferxoykaren';                               // SMTP password
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
    $mail->Body    = 'Su pedido ha sido recibido,
                      verifiqué que esten correctos los datos de su comprobante.
                      <br/>
                      <br/>
                      <br/>
                      <br/>
                      <br/>
                      <strong>Contacto:</strong> 922-123-45-45';
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
  <?php echo $dataHTML ?>

</body>
</html>

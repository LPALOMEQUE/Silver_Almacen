<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';

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
    $sql = "SELECT COSTO_PROM FROM INVE01 where CVE_ART='$id'";
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
$ID = $_SESSION['ID_USER'];
$MAIL = $_SESSION['Email'];
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
FROM CLIE01
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

  $dataHTML .= '<br/>'.'<h3><i><strong>Vendedor:</strong></i></h3>';
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
    $sql = "SELECT DESCR as Nombre, COSTO_PROM FROM INVE01 where CVE_ART='$id'";

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

  sendEmail($pdf, $sendData);

  // OBTENEMOS LOS DATOS NECESARIOS DEL ARTICULO
  require_once "php/Conexion.php";
  $con = conexion();
  $i=1;
  if (isset($_SESSION['ID_ARTICLES'])) {
    foreach ($ID_ARTICLES as $key => $item) {
      $id= $item['id'];
      $sql = "SELECT CVE_ART,COSTO_PROM FROM INVE01 where CVE_ART='$id'";

      $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
      if (0 !== sqlsrv_num_rows($res)){
        while ($arti = sqlsrv_fetch_array($res)) {
          ECHO $CVE_DOC = 'CHR'.$idventa;
          echo "----";
          $PRECIO_ART = $arti['COSTO_PROM'];
          $CANTIDAD_ART = $item['cantidad'];
          $TotalxArt = $arti['COSTO_PROM'] * $item['cantidad'];
          // $fecha = date (dd/mm/YYYY);
          $CVE_ART = $arti['CVE_ART'];

          // INICIO --->      SAVE_PAR_FACTR13(STEP_1)
          $sql2 = "IF NOT EXISTS (SELECT CVE_DOC FROM PAR_FACTR01
          WHERE CVE_DOC = '$CVE_DOC' AND NUM_PAR = $i)

          INSERT INTO PAR_FACTR01
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
          (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE01), --NUM_MOV (SELECT ISNULL(MAX(NUM_MOV),0) + 1 FROM MINVE13)
          '$TotalxArt', --TOT_PARTIDA   ES LA CANTIDA DEL ARTICULO POR SU PRECIO   ----IMPORTANTE ESTO ES POR CADA ART QUE SE ENCUENTRE
          'S')";

          $res2 =  sqlsrv_query($con, $sql2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


          $sql3 = "UPDATE INVE01 SET EXIST = ISNULL(EXIST,0) - $CANTIDAD_ART --el 1 es la cantidad vendida
                    , FCH_ULTVTA = GETDATE(),--día actual
                    VTAS_ANL_C = ISNULL(VTAS_ANL_C,0) + $CANTIDAD_ART, -- el 1 es la cantidad vendida
                    VTAS_ANL_M = ISNULL(VTAS_ANL_M,0) + $TotalxArt -- total de cantidad por articulo
                    WHERE CVE_ART = '$CVE_ART' -- ALI000036OLes la clave del artículo";

                    $res3 =  sqlsrv_query($con, $sql3, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));


          $i++;

          // FIN --->      SAVE_PAR_FACTR13(STEP_1)---------------------------------------------------------------------------------------------------------------------------------------
        }
      }
    }
    sqlsrv_close($con);
  }


  // INICIO --->       UPDATE_INVE13(STEP_2)
  // require_once "php/Conexion.php";
  // $con = conexion();
  // $sql = "UPDATE INVE13 SET EXIST = ISNULL(EXIST,0) - $CANTIDAD_ART --el 1 es la cantidad vendida
  //                     , FCH_ULTVTA = '04/02/2020',--día actual
  //                     VTAS_ANL_C = ISNULL(VTAS_ANL_C,0) + $CANTIDAD_ART, -- el 1 es la cantidad vendida
  //                     VTAS_ANL_M = ISNULL(VTAS_ANL_M,0) + 115 --el 115 es el total de la venta
  //                     WHERE CVE_ART = '$CVE_ART' -- ALI000036OLes la clave del artículo";
  //
  //   $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
  //   if (0 !== sqlsrv_num_rows($res)){
  //     echo "1";
  //     sqlsrv_close($con);
  //   }else{
  //     echo "2";
  //     sqlsrv_close($con);
  //   }

  // FIN --->       UPDATE_INVE13(STEP_2)


  echo "
  <script type='text/javascript'>
  // window.location= 'index.php?vaciar=1';
  alert('Pago aprobado');
  </script>";
}

else{
  echo "<script>
  window.location= 'index.php';
  alert('Ocurrio un error con el pago');
  </script>";
}

function sendEmail($pdf, $sendData){

  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'gerenciageneral@evolutionsilver.com';                     // SMTP username
    $mail->Password   = 'Balbucerito2016';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->SMTPSecure = 'tls';
    $mail->Port  = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('gerenciageneral@evolutionsilver.com');
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

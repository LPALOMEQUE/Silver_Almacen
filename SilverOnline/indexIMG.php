<?php
session_start();
require_once "php/Conexion.php";
$con = conexion();
$aCarrito = array();
$arrayCart = array();
$sHTML = '';
$bagNumber = 0;
$TotalxArtGlobal = 0;
$cantidad = 0;
$key = 1;
$BD = '01';


if(isset($_POST['NOMBREC_Consigna'])){

  $_SESSION['BUS_CLIENTE'] = $_POST['NOMBREC_Consigna'];
}


if (isset($_POST['NombreHide'])) {
  $_SESSION['BUS_CLIENTE'] = $_POST['NombreHide'];
}
if (isset($_POST['ID_CLIENTEPost'])) {
  $_SESSION['ID_CLIENTE'] = $_POST['ID_CLIENTEPost'];
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

if (isset($_SESSION['ID_ARTICLES'])) {
  // $bagNumber = count($_SESSION['ID_ARTICLES']);
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}

if (isset($_GET['vaciar']) && $_GET['vaciar'] == 2 ) {
  unset($_SESSION['ID_ARTICLES']);
  unset($_COOKIE['express']);
}

if (isset($_GET['vaciar']) && $_GET['vaciar'] == 3 ) {
  unset($_SESSION['ID_ARTICLES']);
  unset($_COOKIE['express']);
}

if (isset($_POST['VACIAR_LOGIN'])) {
  unset($_SESSION['ID_USER']);
  unset($_SESSION['Email']);
  unset($_SESSION['status']);
}

//Imprimiendo datos globales del carrito
require_once "php/Conexion.php";
$con = conexion();
if (isset($_SESSION['ID_ARTICLES'])) {

  foreach($ID_ARTICLES as $key => $item){

    $id = $item['id'];
    $sql = "SELECT PRECIO AS ULT_COSTO FROM PRECIO_X_PROD" .$BD. " WHERE CVE_ART = '$id' AND  CVE_PRECIO = $ID_PRECIO";

    $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
    if (0 !== sqlsrv_num_rows($res)){
      while ($fila = sqlsrv_fetch_array($res)) {

        $precioNormal = $fila['ULT_COSTO'];
        $TotalxArtGlobal += $precioNormal * $item['cantidad'];
      }
    }
  }

  sqlsrv_close($con);
}
$p =   $key+1;

// anydando articulos al carrito
if(isset($_POST['ID']) && isset($_POST['CANTIDAD'])) {
  $ultimaPos = count($_SESSION['ID_ARTICLES']);
  $_SESSION['ID_ARTICLES'][$p]=
  array(
    "id" => $_POST['ID'],
    "cantidad" => $_POST['CANTIDAD']);
  }

  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>Silver - Evolution | Inicio</title>

    <!-- Favicon  -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-style.css">
    <link rel="stylesheet" href="style.css">

    <!-- Responsive CSS -->
    <link href="css/responsive.css" rel="stylesheet">

    <!-- css LFPO -->
    <link rel="stylesheet" type="text/css" href="alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="alertifyjs/css/themes/default.css">
    <!-- end -->

    <!-- scripts LFPO -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src='js/jquery/jquery.elevatezoom.js'></script>

    <script src="alertifyjs/alertify.js"></script>
    <script src="js/funciones.js"></script>
    <!-- end -->

  </head>

  <body class="divRes">

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src='js/jquery/jquery.elevatezoom.js'></script>

    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>

  </body>
  <?php
  require_once "php/Conexion.php";
  $con = conexion();
  $sql="SELECT
  M.EXIST,
  I.CVE_ART,
  I.DESCR as Nombre,
  PP.PRECIO AS ULT_COSTO,
  I.CVE_IMAGEN,
  I.DESCR as Descripcion
  FROM INVE" .$BD. " I
  LEFT JOIN MULT" .$BD. " M ON M.CVE_ART = I.CVE_ART
  INNER JOIN PRECIO_X_PROD" .$BD. " PP ON PP.CVE_ART = I.CVE_ART
  WHERE
  I.EXIST >0 AND
	M.CVE_ALM=1 AND
	M.EXIST >0 AND
  PP.CVE_PRECIO = $ID_PRECIO AND
  I.CVE_ART = '".$_GET['SKU']. "'";

  $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
  if (0 !== sqlsrv_num_rows($res)){
    while ($category = sqlsrv_fetch_array($res)) {
      $EXISTENCIA = $category['EXIST'];
      $precio = $category['ULT_COSTO'];
      ?>
      <br/>
      <br/>
      <br/>
      <div class="row">

        <div class="col-12 col-lg-6" align="center" >
          <br/>
          <div id="showIMG" class="col-12 col-lg-6">
            <img src="images/large/<?php echo $_GET['SKU']?>.jpg" height="600px" width="300px">
          </div>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="">
          <div class="quickview_pro_des" aling="right">

            <h4 class="title" style="color: #d0368c;"><?php echo $category['Nombre'] ?></h4>
            <div class="top_seller_product_rating mb-15">
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
            </div>
            <h5 class="price">$<?php echo number_format($precio,2) ?> <span>$624</span></h5>
            <p>Marca: SILVER</p>
            <p>SKU: <?php echo $_GET['SKU']?></p>
            <p><?php echo $category['Descripcion'] ?></p>
            <p style="color: #d0368c;"><strong>STOCK DISPONIBLE: <?php echo $category['EXIST'] ?></strong></p>
          </div>

          <br/>
          <div class="" aling="center">


          <img id="img<?php echo $_GET['SKU']?>" src="images/large/<?php echo $_GET['SKU']?>.jpg" height="200px" width="110px" data-zoom-image="images/large/<?php echo $_GET['SKU']?>.jpg"/>

          <img id="img<?php echo $_GET['SKU']?>2" src="images/large/<?php echo $_GET['SKU']?>.1.jpg" height="200px" width="110px" data-zoom-image="images/large/<?php echo $_GET['SKU']?>.1.jpg"/>

          <img id="img<?php echo $_GET['SKU']?>3" src="images/large/<?php echo $_GET['SKU']?>.2.jpg" height="200px" width="110px" data-zoom-image="images/large/<?php echo $_GET['SKU']?>.2.jpg"/>
          </div>
          <!-- END ENVIO DE DATOS POR URL ESCONDIDA -->
          <div class="share_wf mt-30" align="right">
            <p style="color: #d0368c;">Comparte con tus amigos</p>
            <div class="_icon">
              <a href="https://es-la.facebook.com/newsilverevolution/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
              <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
              <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
              <a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
            </div>
          </div>

        </div>

      </div>
      <?php
    }
    sqlsrv_close($con);
  }
  ?>
  </html>



  <script type="text/javascript">

  $(document).ready(function(){
    alertify.set('notifier','position', 'top-right');

    //   $('#img<?php echo $_GET['SKU']?>').elevateZoom({
    //     easing: true,
    //     scrollZoom : true,
    // zoomWindowPosition: 1, zoomWindowOffetx: 10,    // cursor: "crosshair",
    //     zoomWindowFadeIn: 500,
    //     zoomWindowFadeOut: 750,
    //     zoomWindowWidth:450,
    //             zoomWindowHeight:450,
    //             zoomWindowPosition: 1
    //   });
    $("#img<?php echo $_GET['SKU']?>").elevateZoom({
      zoomWindowPosition: "showIMG ",
      scrollZoom : true,
      cursor: "crosshair",
      zoomWindowHeight: 420,
      zoomWindowWidth:430,
      borderSize: 1,
      easing:true
    });

    $("#img<?php echo $_GET['SKU']?>2").elevateZoom({
      zoomWindowPosition: "showIMG ",
      scrollZoom : true,
      cursor: "crosshair",
      zoomWindowHeight: 420,
      zoomWindowWidth:430,
      borderSize: 1,
      easing:true
    });

    $("#img<?php echo $_GET['SKU']?>3").elevateZoom({
      zoomWindowPosition: "showIMG ",
      scrollZoom : true,
      cursor: "crosshair",
      zoomWindowHeight: 420,
      zoomWindowWidth:430,
      borderSize: 1,
      easing:true
    });

  });
  </script>

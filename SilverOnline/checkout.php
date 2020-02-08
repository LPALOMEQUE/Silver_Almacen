<?php
session_start();
require_once "php/Conexion.php";
$con = conexion();
$aCarrito = array();
$sHTML = '';
$bagNumber = 0;
$TotalxArtGlobal = 0;
$TotalxArt =0;
$cantidad = 0;
$totalP =0;
$vtaTotal = 0;
$costoEnvio = 0;


// formulario
$nombre = '';
$apellidoP = '';
$apellidoM = '';
$calle = '';
$numCalle = '';
$cp = '';
$ciudad = '';
$estado = '';
$cel = '';
$email = '';
$paymentToken = '';
$paymentID = '';

if (!isset($_SESSION["ID_USER"])) {
  header('Location: index.php');
}
if (isset($_SESSION['ID_ARTICLES'])) {
  $bagNumber = count($_SESSION['ID_ARTICLES']);
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}

//Vaciamos el la session
if (isset($_POST['VACIAR_LOGIN'])) {
  unset($_SESSION['ID_USER']);
  unset($_SESSION['Email']);
}

//Vaciamos el carrito
if(isset($_POST['vaciar'])) {
  unset($_COOKIE['express']);
}

$iTemCad = time() + (60 * 60);

if (isset($_POST['MONTO'])) {
  setcookie('express',$_POST['MONTO'],$iTemCad);
  $costoEnvio = $_COOKIE['express'];
}

//Imprimimos datos globales del carrito
require_once "php/Conexion.php";
$con = conexion();
if (isset($_SESSION['ID_ARTICLES'])) {

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
  <title>Karl - Fashion Ecommerce Template | Checkout</title>

  <!-- Favicon  -->
  <link rel="icon" href="img/core-img/favicon.ico">

  <!-- Core Style CSS -->
  <link rel="stylesheet" href="css/core-style.css">
  <link rel="stylesheet" href="style.css">

  <!-- Responsive CSS -->
  <link href="css/responsive.css" rel="stylesheet">

  <!-- scripts LFPO -->
  <script src="js/funciones.js"></script>
  <script src="librerias/alertify/alertify.js"></script>
  <!-- end -->
</head>

<body>
  <div class="catagories-side-menu">
    <!-- Close Icon -->
    <div id="sideMenuClose">
      <i class="ti-close"></i>
    </div>
    <!--  Side Nav  -->
    <div class="nav-side-menu">
      <div class="menu-list">
        <h6>Categories</h6>
        <ul id="menu-content" class="menu-content collapse out">
          <!-- Single Item -->
          <li data-toggle="collapse" data-target="#joyas" class="collapsed active">
            <a href="#">Joyas<span class="arrow"></span></a>
            <ul class="sub-menu collapse" id="joyas">
              <li><a href="joyas-h.php">Hombre</a></li>
              <li><a href="joyas-m.php">Mujer</a></li>
            </ul>
          </li>

          <!-- Single Item -->
          <li data-toggle="collapse" data-target="#bolsas" class="collapsed active">
            <a href="#">Bolsas<span class="arrow"></span></a>
            <ul class="sub-menu collapse" id="bolsas">
              <li><a href="#">Hombre</a></li>
              <li><a href="#">Mujer</a></li>
            </ul>
          </li>

          <!-- Single Item -->
          <li data-toggle="collapse" data-target="#perfumes" class="collapsed active">
            <a href="#">Perfumes<span class="arrow"></span></a>
            <ul class="sub-menu collapse" id="perfumes">
              <li><a href="#">Hombre</a></li>
              <li><a href="#">Mujer</a></li>
            </ul>
          </li>

          <!-- Single Item -->
          <li data-toggle="collapse" data-target="#ropa" class="collapsed active">
            <a href="#">Ropa<span class="arrow"></span></a>
            <ul class="sub-menu collapse" id="ropa">
              <li><a href="#">Hombre</a></li>
              <li><a href="#">Mujer</a></li>
              <li><a href="#">Niño</a></li>
              <li><a href="#">Niña</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div id="wrapper">

    <!-- ****** Header Area Start ****** -->
    <header class="header_area bg-img background-overlay-white" style="background-image: url(img/bg-img/bg-1.jpg);">
      <div class="row">
        <div class="col-md-3 error">
          <a class="center"> <strong>Usuario:</strong> <?php
          if (isset($_SESSION["Email"])) {
            echo $_SESSION["Email"];
          }else {
            echo $invitado = 'Invitado...';
          } ?>
        </a>
      </div>
      <div class="col-md-2 error">
        <div class="<?php
        if (isset($_SESSION["Email"])) {

          echo $mostrar = 'inline';
        }else {
          echo $ocultar = 'none';
        } ?> ">
        <button type="button" class="btn btn-link" id="btnLogOut">Salir</button>
      </div>

    </div>
    <div class="col-md-2">

    </div>
    <!-- <div class="col-md-1">

  </div> -->
  <div class="col-md-3 right">

  </div>

  <div class="col-md-2">

  </div>
</div>
<!-- Top Header Area Start -->
<div class="top_header_area">
  <div class="container h-100">
    <div class="row h-100 align-items-center justify-content-end">

      <div class="col-12 col-lg-7">
        <div class="top_single_area d-flex align-items-center">
          <!-- Logo Area -->
          <div class="top_logo">
            <a href="#"><img src="img/core-img/logo.png" alt=""></a>
          </div>
          <!-- Cart & Menu Area -->
          <div class="header-cart-menu d-flex align-items-center ml-auto">
            <!-- Cart Area -->
            <div class="cart">
              <a href="cart.php"><span class="cart_quantity"> <?php echo $bagNumber ?> </span> <i class="ti-bag"></i><strong> Carrito:</strong>  $<?php echo number_format($TotalxArtGlobal,2) ?></a>
            </div>
            <div class="header-right-side-menu ml-15">
              <a href="#" id="sideMenuBtn"><i class="ti-menu" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Top Header Area End -->
<div class="main_header_area">
  <div class="container h-100">
    <div class="row h-100">
      <div class="col-12 d-md-flex justify-content-between">
        <!-- Header Social Area -->
        <div class="header-social-area">
          <a href="#"><span class="karl-level">Share</span> <i class="fa fa-pinterest" aria-hidden="true"></i></a>
          <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
          <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
          <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
        </div>
        <!-- Menu Area -->
        <div class="main-menu-area">
          <nav class="navbar navbar-expand-lg align-items-start">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#karl-navbar" aria-controls="karl-navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"><i class="ti-menu"></i></span></button>

            <div class="collapse navbar-collapse align-items-start collapse" id="karl-navbar">
              <ul class="navbar-nav animated" id="nav">
                <li class="nav-item active"><a class="nav-link" href="index.php">Inicio</a></li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="karlDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Paginas</a>
                  <div class="dropdown-menu" aria-labelledby="karlDropdown">
                    <a class="dropdown-item" href="index.php">Inicio</a>
                    <a class="dropdown-item" href="shop.html">Compras</a>
                    <a class="dropdown-item" href="product-details.html">Detalles de productos</a>
                    <a class="dropdown-item" href="cart.html">Carrito</a>
                    <a class="dropdown-item" href="checkout.html">Resiva</a>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </div>
        <!-- Help Line -->
        <div class="help-line">
          <a href="tel:9221197785"><i class="ti-headphone-alt"></i> +52 922 1197 785</a>
        </div>
      </div>
    </div>
  </div>
</div>
</header>
<!-- ****** Header Area End ****** -->

<section class="top-discount-area d-md-flex align-items-center">
  <!-- Single Discount Area -->
  <div class="single-discount-area">
    <h5>Free Shipping &amp; Returns</h5>
    <h6><a href="#">BUY NOW</a></h6>
  </div>
  <!-- Single Discount Area -->
  <div class="single-discount-area">
    <h5>20% Discount for all dresses</h5>
    <h6>USE CODE: Colorlib</h6>
  </div>
  <!-- Single Discount Area -->
  <div class="single-discount-area">
    <h5>20% Discount for students</h5>
    <h6>USE CODE: Colorlib</h6>
  </div>
</section>

<!-- ****** Checkout Area Start ****** -->
<div class="checkout_area section_padding_100">
  <div class="container">
    <div class="row">

      <div class="col-12 col-md-6">
        <div class="checkout_details_area mt-50 clearfix">

          <div class="cart-page-heading">
            <h5>Datos de envío</h5>
            <!-- <p>...</p> -->
          </div>

          <form action="#" method="post">
            <?php
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
              ?>
              <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="txtName">Nombre del Vendedor<span>*</span></label>
                    <input type="text" onkeyup="mayus(this);" class="form-control" id="txtName" value="<?php echo $nombre ?>" readonly required>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="txtApellidoP">Nombre quien recibe <span>*</span></label>
                    <input type="text" onkeyup="mayus(this);" class="form-control" id="txtName_Recibe" value="<?php echo $nombreRecibe ?>" required>
                  </div>
                  <!-- <div class="col-md-6 mb-3">
                  <label for="txtApellidoM">Apellido Materno <span>*</span></label>
                  <input type="text" class="form-control" id="txtApellidoM" value="<?php echo $user[4] ?>" required>
                </div> -->
                <div class="col-6 mb-3">
                  <label for="txtCalle">Calle <span>*</span></label>
                  <input type="text" onkeyup="mayus(this);" class="form-control mb-3" id="txtCalle" value="<?php echo $calle ?>">
                </div>
                <div class="col-3 mb-3">
                  <label for="txtNumCalle">Número # <span>*</span></label>
                  <input type="text" class="form-control" id="txtNumCalle" value="<?php echo $numCalle ?>">
                </div>
                <div class="col-3 mb-3">
                  <label for="txtCp">Codígo Postal <span>*</span></label>
                  <input type="text" class="form-control" id="txtCp" value="<?php echo $cp ?>">
                </div>
                <div class="col-12 mb-3">
                  <label for="txtCiudad">Ciudad <span>*</span></label>
                  <input type="text" onkeyup="mayus(this);" class="form-control" id="txtCiudad" value="<?php echo $ciudad ?>">
                </div>
                <div class="col-12 mb-3">
                  <label for="txtEstado">Estado <span>*</span></label>
                  <input type="text" onkeyup="mayus(this);" class="form-control" id="txtEstado" value="<?php echo $estado ?>">
                </div>
                <div class="col-12 mb-3">
                  <label for="txtCel">Num. de contacto <span>*</span></label>
                  <input type="number" class="form-control" id="txtCel" min="0" value="<?php echo $cel ?>">
                </div>
                <div class="col-12 mb-4">
                  <label for="txtEmail">Dirección de correo <span>*</span></label>
                  <input type="email" class="form-control" id="txtEmail" value="<?php echo $email ?>" readonly>
                </div>
              <?php }
            } ?>
            </div>
            <button type="button" class="btn karl-checkout-btn" id="btnActualizarDatos">Actualizar</button>
          </form>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-5 ml-lg-auto">
        <div class="order-details-confirmation">

          <div class="cart-page-heading">
            <h5>Tu orden</h5>
            <p>Detalles</p>
          </div>

          <ul class="order-details-form mb-4">
            <li><span>Artículos</span> <span>Total</span></li>
            <?php
            require_once "php/Conexion.php";
            $con = conexion();
            if (isset($_SESSION['ID_ARTICLES'])) {
            foreach ($ID_ARTICLES as $key => $item) {
              $id= $item['id'];
              $sql = "SELECT DESCR as Nombre,COSTO_PROM FROM INVE01 where CVE_ART='$id'";

              $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
              if (0 !== sqlsrv_num_rows($res)){
                while ($arti = sqlsrv_fetch_array($res)) {


                $TotalxArt = $arti['COSTO_PROM'] * $item['cantidad'];
                ?>
                <li><span><?php echo $arti['Nombre'] ?></span> <span>$<?php echo number_format($TotalxArt,2) ?></span></li>

              <?php }
            }
            }
          }?>
            <li><strong><span>Subtotal</span></strong> <strong><span>$<?php echo number_format($TotalxArtGlobal,2) ?></span></span></li>
              <li><strong><span>Envio</span></span></strong> <strong><span>$<?php
              if (isset($_COOKIE['express'])) {
                echo number_format($_COOKIE['express'],2);
              }else {
                echo $snf='0.00';
              }
              ?></span></span></li>
              <li><strong><span>Total</span></span></strong> <strong><span>$<?php echo number_format($vtaTotal,2) ?></span></span></li>
              </ul>


              <div id="accordion" role="tablist" class="mb-4">
                <div class="card">
                  <div class="card-header" role="tab" id="headingOne">
                    <h6 class="mb-0">
                      <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-circle-o mr-3"></i>Paypal</a>
                    </h6>
                  </div>

                  <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                      <div id="paypal-button-container"></div>
                      <div id="paypal-button"></div>
                      <script src="https://www.paypalobjects.com/api/checkout.js"></script>
                      <script>
                      paypal.Button.render({
                        env: 'sandbox',
                        style:{

                          label: 'checkout',
                          size: 'responsive',
                          shape: 'pill',
                          color: 'gold'

                        },
                        client: {
                          sandbox: 'AQfqqbzkFvxShrOBEbcFqOB6uDjVlaFgIwpW2JEErSGMSQe1cCzMMHdhA6jYXqhnYGVzSsmI3BGYQF9G',
                          production: 'AWkFACdq0h4aeDpN-yfYhlk4FxnpGYbLmX6rcVA5qo3N2ErxCp3GrPyQ1sWIwCR2EH6UubCHJfNnH84I'

                        },
                        payment: function (data, actions) {
                          return actions.payment.create({
                            transactions:
                            [
                              {
                                amount: {total: '<?php echo $vtaTotal; ?>', currency: 'MXN'},
                                description: 'Compra de artículos a Silver Evolution:$<?php echo number_format($vtaTotal,2);?>'
                              }
                            ]
                          });
                        },
                        onAuthorize: function (data, actions) {
                          return actions.payment.execute().then(function () {
                            // console.log(data);
                            window.location="verificador.php?paymentToken="+ data.paymentToken +
                            "&paymentID=" + data.paymentID +
                            "&EMAIL=" + '<?php echo $email ?>';
                          });
                        }
                      }, '#paypal-button-container');
                      </script>

                    </div>
                  </div>
                </div>
                <!-- <div class="card">
                <div class="card-header" role="tab" id="headingTwo">
                <h6 class="mb-0">
                <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fa fa-circle-o mr-3"></i>cash on delievery</a>
              </h6>
            </div>
            <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo quis in veritatis officia inventore, tempore provident dignissimos.</p>
          </div>
        </div>
      </div> -->

    </div>

    <!-- <a href="#" class="btn karl-checkout-btn">Place Order</a> -->
  </div>
</div>

</div>
</div>
</div>
<!-- ****** Checkout Area End ****** -->

<!-- ****** Footer Area Start ****** -->
<footer class="footer_area">
  <div class="container">
    <div class="row">
      <!-- Single Footer Area Start -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="single_footer_area">
          <div class="footer-logo">
            <img src="img/core-img/logo.png" alt="">
          </div>
          <div class="copywrite_text d-flex align-items-center">
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a> &amp; distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
            </div>
          </div>
        </div>
        <!-- Single Footer Area Start -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
          <div class="single_footer_area">
            <ul class="footer_widget_menu">
              <li><a href="#">About</a></li>
              <li><a href="#">Blog</a></li>
              <li><a href="#">Faq</a></li>
              <li><a href="#">Returns</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>
        </div>
        <!-- Single Footer Area Start -->
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
          <div class="single_footer_area">
            <ul class="footer_widget_menu">
              <li><a href="#">My Account</a></li>
              <li><a href="#">Shipping</a></li>
              <li><a href="#">Our Policies</a></li>
              <li><a href="#">Afiliates</a></li>
            </ul>
          </div>
        </div>
        <!-- Single Footer Area Start -->
        <div class="col-12 col-lg-5">
          <div class="single_footer_area">
            <div class="footer_heading mb-30">
              <h6>Subscribe to our newsletter</h6>
            </div>
            <div class="subscribtion_form">
              <form action="#" method="post">
                <input type="email" name="mail" class="mail" placeholder="Your email here">
                <button type="submit" class="submit">Subscribe</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="line"></div>

      <!-- Footer Bottom Area Start -->
      <div class="footer_bottom_area">
        <div class="row">
          <div class="col-12">
            <div class="footer_social_area text-center">
              <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
              <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
              <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
              <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- ****** Footer Area End ****** -->
</div>
<!-- /.wrapper end -->

<!-- jQuery (Necessary for All JavaScript Plugins) -->
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<!-- Popper js -->
<script src="js/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="js/bootstrap.min.js"></script>
<!-- Plugins js -->
<script src="js/plugins.js"></script>
<!-- Active js -->
<script src="js/active.js"></script>

<script src="https://smtpjs.com/v3/smtp.js"></script>

</body>

</html>

<script type="text/javascript">

$(document).ready(function(){

  $('#btnLogOut').click(function(){
    vaciar = 1;

    logOut(vaciar);

  });

  $('#btnPaypal').click(function(){
    Email.send({
      Host : "smtp.elasticemail.com",
      Username : "fernando18092105@gmail.com",
      Password : "C8C00D5D9EEF4F923A4B7190F4F83F9D4E5B",
      To : 'fer18092105@icloud.com',
      From : "fernando18092105@gmail.com",
      Subject : "This is the subject",
      Body : "And this is the body"
    }).then(
      message => alert(message)
    );
  });

  $('#btnActualizarDatos').click(function(){

    nombre = $('#txtName').val();
    debugger;
    nombre_recibe = $('#txtName_Recibe').val();
    calle = $('#txtCalle').val();
    numCalle = $('#txtNumCalle').val();
    cp = $('#txtCp').val();
    ciudad = $('#txtCiudad').val();
    estado = $('#txtEstado').val();
    cel = $('#txtCel').val();
    email= $('#txtEmail').val();

    if(validar_email( email ) )
    {
    }
    else
    {
      alert("El correo: " +email+ " no contiene el formato correcto, verifíquelo...");
      email = 1;
    }

    pass= $('#txtPass').val();

    if(nombre == ""){

      alert("Debe ingresar un nombre...");
    }
    if(nombre_recibe == ""){

      alert("Debe ingresar nombre de la persona que recibbirá el producto...");
    }
    if(calle == ""){

      alert("Debe ingresar una calle...");
    }if(numCalle == ""){

      alert("Debe ingresar un número de la hubicación...");
    }
    if(cp == ""){

      alert("Debe ingresar un código postal...");
    }if(ciudad == ""){

      alert("Debe ingresar una ciudad...");
    }
    if(estado == ""){

      alert("Debe ingresar un estado...");
    }
    if(cel == ""){

      alert("Debe ingresar un número de contacto...");
    }
    if(email == ""){

      alert("Debe ingresar un E-mail...");
    }
    if(pass == ""){

      alert("Debe ingresar una contraseña...");
    }

    if(nombre != "" && nombre_recibe != ""  && calle != "" && numCalle != "" && cp != "" && ciudad != "" && estado != "" && cel != ""  && email != "" && email !=1 && pass != ""){
      ModDatosUsuarios(nombre,nombre_recibe,calle,numCalle,cp,ciudad,estado,cel,email, pass);
    }

  });



  function validar_email( email )
  {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
  }

});
function mayus(e) {
    e.value = e.value.toUpperCase();
}
function minus(e) {
    e.value = e.value.toLowerCase();
}
</script>

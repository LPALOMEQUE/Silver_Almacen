<?php
session_start();
$aCarrito = array();
$sHTML = '';
$bagNumber = 0;
$TotalxArtGlobal = 0;
$cantidad = 0;


if (isset($_SESSION['ID_ARTICLES'])) {
  $bagNumber = count($_SESSION['ID_ARTICLES']);
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}

//Salimos del usuario que inicio sesion
if (isset($_POST['VACIAR_LOGIN'])) {
  unset($_SESSION['ID_USER']);
  unset($_SESSION['Email']);
}

// Vaciamos el carrito
if(isset($_GET['vaciar'])) {
  unset($_SESSION['ID_ARTICLES']);
  unset($_SESSION['filtro_price']);
  echo "

  <script type='text/javascript'>
  window.location= 'joyas-h.php';
  </script>";
  //session_destroy();
}

//Eliminamos articulos del carrito
if(isset($_POST['ID']) && isset($_POST['DelArt']) && isset($_POST['Posicion'])) {
  // echo ("posicion: ".$_POST['Posicion']);

  unset($_SESSION['ID_ARTICLES'][$_POST['Posicion']]);

  array_values($_SESSION['ID_ARTICLES']);

}

//Actualizando un articulo del carrito
if(isset($_POST['ID']) && isset($_POST['Posicion']) && isset($_POST['CANTIDAD'])) {

  foreach ($ID_ARTICLES as $key => $item) {
    if ($ID_ARTICLES[$_POST['Posicion']]['id'] == $_POST['ID']) {

      $_SESSION['ID_ARTICLES'][$_POST['Posicion']]=
      array(
        "id" => $_POST['ID'],
        "cantidad" => $_POST['CANTIDAD']);

        print_r('existe el articulo'. ' '. $ID_ARTICLES[$_POST['Posicion']]['id']);
        echo ("posicion: ".$_POST['Posicion']);

        var_dump($ID_ARTICLES);
      }
    }
  }

  //Imprimimos datos globales del carrito
  require_once "php/Conexion.php";
  $con = conexion();
  if (isset($_SESSION['ID_ARTICLES'])) {

    foreach($ID_ARTICLES as $key => $item){

      $id = $item['id'];
      $sql = "SELECT COSTO_PROM FROM INVE13 where CVE_ART='$id'";
      $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
      if (0 !== sqlsrv_num_rows($res)){
        while ($fila = sqlsrv_fetch_array($res)) {
          $TotalxArtGlobal += $fila['COSTO_PROM'] * $item['cantidad'];
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
    <title>Siler - Evolution | Carrito</title>

    <!-- Favicon  -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="css/core-style.css">
    <link rel="stylesheet" href="style.css">

    <!-- Responsive CSS -->
    <link href="css/responsive.css" rel="stylesheet">

    <!-- css LFPO -->
    <link rel="stylesheet" type="text/css" href="librerias/alertify/css/alertify.css" >
    <link rel="stylesheet" type="text/css" href="librerias/alertify/css/themes/default.css" >
    <!-- end -->

    <!-- scripts LFPO -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/funciones.js"></script>
    <script src="librerias/alertify/alertify.js"></script>
    <!-- end -->

  </head>

  <body>

    <!-- Modal para inicio de sesion -->
    <div class="modal fade" id="ModalLogin" tabindex="-1" role="dialog" aria-labelledby="ModalLogin" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLogin">Inicio de sesión...</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="txtEmail">E-MaiL</label>
                <input type="email" class="form-control" id="txt_Email" value="" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="txtPass">Contraseña</label>
                <input type="password" class="form-control" id="txt_Pass" value="" required>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btnEntrar">Entrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para inicio de sesion valida envio y location a checkout.php -->
    <div class="modal fade" id="ModalLoginVal" tabindex="-1" role="dialog" aria-labelledby="ModalLoginVal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLoginVal">Inicio de sesión...</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="txtEmailVal">E-MaiL</label>
                <input type="email" class="form-control" id="txt_EmailVal" value="" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="txtPassVal">Contraseña</label>
                <input type="password" class="form-control" id="txt_PassVal" value="" required>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btnEntrarVal">Entrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="catagories-side-menu">
      <!-- Close Icon -->
      <div id="sideMenuClose">
        <i class="ti-close"></i>
      </div>
      <!--  Side Nav  -->
      <div class="nav-side-menu">
        <div class="menu-list">
          <h6>Categorías</h6>
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

      <div class="<?php
      if (isset($_SESSION["Email"])) {

        echo $ocultar = 'none';
      }else {
        echo $mostrar = 'inline';
      } ?>">
      <button type="button" class="btn btn-link" data-toggle="modal" data-target="#ModalLogin">Entrar</button>
      <button type="button" class="btn btn-link" data-toggle="modal" data-target="#ModalRegistroUsuarios">Registrate</button>
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
<!-- <P><?php   var_dump($_SESSION['ID_ARTICLES']); ?></P> -->

<!-- ****** Header Area Start ****** -->
<header class="header_area bg-img background-overlay-white" style="background-image: url(img/bg-img/bg-1.jpg);">
  <!-- Top Header Area Start -->
  <div class="top_header_area">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-end">

        <div class="col-12 col-lg-7">
          <div class="top_single_area d-flex align-items-center">
            <!-- Logo Area -->
            <div class="top_logo">
              <a href="#"><img src="img/core-img/logo_Silver.png" alt=""></a>
            </div>
            <!-- Cart & Menu Area -->
            <div class="header-cart-menu d-flex align-items-center ml-auto">
              <!-- Cart Area -->
              <div class="cart">
                <a href="#"><span class="cart_quantity"> <?php echo $bagNumber ?> </span> <i class="ti-bag"></i><strong> Carrito:</strong>  $<?php echo number_format($TotalxArtGlobal,2) ?></a>
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

<!-- ****** Top Discount Area Start ****** -->
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
<!-- ****** Top Discount Area End ****** -->

<!-- ****** Cart Area Start ****** -->
<div class="cart_area section_padding_100 clearfix">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="cart-table clearfix">
          <table class="table table-responsive">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th> </th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once "php/Conexion.php";
              $con = conexion();
              if (isset($_SESSION['ID_ARTICLES'])) {
                foreach ($ID_ARTICLES as $key => $item) {
                  $id= $item['id'];
                  $sql = "SELECT DESCR,CVE_IMAGEN,COSTO_PROM FROM INVE13 where CVE_ART='$id'";

                  $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
                  if (0 !== sqlsrv_num_rows($res)){
                    while ($arti = sqlsrv_fetch_array($res)) {


                      $TotalxArt = $arti['COSTO_PROM'] * $item['cantidad'];

                      ?>
                      <tr>
                        <td class="cart_product_img d-flex align-items-center">
                          <a href="#"><img src="<?php echo $arti['CVE_IMAGEN'] ?>" alt="Product"></a>
                          <h6 id="h6Nombre<?php echo $id ?>"><?php echo $arti['DESCR'] ?></h6>
                        </td>
                        <td class="price">$<?php echo number_format($arti['COSTO_PROM'],2) ?></span></td>
                        <td class="qty">
                          <div class="quantity">
                            <button type="button" class="qty-minus" id="btnMenos<?php echo $id ?>">-</button>
                            <input type="number" class="qty-text" id="qty<?php echo $id ?>" name="CANTIDAD">
                            <button type="button" class="qty-minus" id="btnMas<?php echo $id ?>">+</button>

                          </div>
                        </td>
                        <td>
                          <button type="button" class="btn btn-danger" id="btnDel<?php echo $id ?>">X</button>
                        </td>
                        <td >
                          <input type="text" class="sinborde" id="txtTotalxArt<?php echo $id ?>" name="CANTIDAD" value="$<?php echo number_format($TotalxArt,2) ?>" readonly="readonly">
                        </td>
                      </tr>

                      <script type="text/javascript">
                      $(document).ready(function(){
                        var i = 0;
                        $('#btnMenos<?php echo $id ?>').click(function(){
                          valor = document.getElementById("qty<?php echo $id ?>");
                          valor.value --;
                          id = '<?php echo $id ?>';
                          cantidad=$('#qty<?php echo $id ?>').val();
                          posicion = <?php echo $key ?>;
                          cartModPrice(id,
                            cantidad,
                            posicion);

                          });
                          $('#btnMas<?php echo $id ?>').click(function(){
                            valor = document.getElementById("qty<?php echo $id ?>");
                            valor.value ++;
                            id = '<?php echo $id ?>';
                            cantidad=$('#qty<?php echo $id ?>').val();
                            posicion = <?php echo $key ?>;
                            cartModPrice(id,
                              cantidad,
                              posicion);

                            });

                            $('#btnDel<?php echo $id ?>').click(function(){
                              id = '<?php echo $id ?>';
                              posicion = <?php echo $key ?>;
                              valida = 1;
                              eliminarArticulo(id, posicion, valida);

                            });

                            var input = document.getElementById("qty<?php echo $id ?>");
                            // Execute a function when the user releases a key on the keyboard
                            input.addEventListener("keyup", function(event) {
                              // Number 13 is the "Enter" key on the keyboard
                              if (event.keyCode === 13) {
                                // Cancel the default action, if needed
                                event.preventDefault();
                                // Trigger the button element with a click
                                valor = document.getElementById("qty<?php echo $id ?>");
                                // valor.value ++;
                                id = '<?php echo $id ?>';
                                cantidad=$('#qty<?php echo $id ?>').val();
                                posicion = <?php echo $key ?>;
                                cartModPrice(id,
                                  cantidad,
                                  posicion);
                                }
                              });
                            });
                            </script>
                            <?php
                          }
                        }
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>

              <div class="cart-footer d-flex mt-30">
                <div class="back-to-shop w-50">
                  <a href="joyas-m.php">Continuar Comprando</a>
                </div>
                <div class="update-checkout w-50 text-right">
                  <a href="cart.php?vaciar=1">Vaciar carrito</a>
                </div>
              </div>

            </div>
          </div>

          <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
              <div class="coupon-code-area mt-70">
                <div class="cart-page-heading">
                  <!-- <h5>Cupon code</h5>
                  <p>Enter your cupone code</p> -->
                </div>
                <!-- <form action="#">
                <input type="search" name="search" placeholder="#569ab15">
                <button type="submit">Apply</button>
              </form> -->
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-4">
            <div class="shipping-method-area mt-70">
              <div class="cart-page-heading">
                <h5>Metodo de envío</h5>
                <p>Selecciona el tipo de envío</p>
              </div>
              <div class="custom-control custom-radio mb-30">
                <input type="radio" id="customRadio1" name="rbDelivery" class="custom-control-input" value="express">
                <label class="custom-control-label d-flex align-items-center justify-content-between" for="customRadio1"><span>Día siguiente</span><span>$700.00</span></label>
              </div>

              <div class="custom-control custom-radio mb-30">
                <input type="radio" id="customRadio2" name="rbDelivery" class="custom-control-input" value="normal">
                <label class="custom-control-label d-flex align-items-center justify-content-between" for="customRadio2"><span>Entrega estandar</span><span>$250.00</span></label>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <div class="cart-total-area mt-70">
              <div class="cart-page-heading">
                <h5>Total del Carrito</h5>
                <p>Información Final</p>
              </div>

              <ul class="cart-total-chart">
                <li><span>Subtotal</span> <span>$<?php echo number_format($TotalxArtGlobal,2) ?></span></li>

                <li><span>Envío</span> <span><input type="text" class="styleGrey" name="cost" value="$0" readonly id="txtcost"></span></li>
                <li><span><strong>Total</strong></span> <span><strong><input type="text" class="styleGrey" name="total" value="$0" readonly id="txtcostT"></strong></span></li>
              </ul>
              <!-- <button type="button" href="checkout.php" class="btn karl-checkout-btn" id="btnPay">X</button> -->
              <button type="button" href="checkout.php" class="btn karl-checkout-btn" id="btnPay2">Proceder al pago</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ****** Cart Area End ****** -->

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

  </body>

  </html>

  <script type="text/javascript">

  $(document).ready(function(){

    $("input[name=rbDelivery]").change(function () {
      precioEnvio = $('input:radio[name=rbDelivery]:checked').val();

      if (precioEnvio == 'express') {

        x = 700;
      }
      else{
        x = 250;
      }
      z = <?php echo $TotalxArtGlobal ?>;
      total = x + z;
      getPriceDeli(x,total);

    });

    $('#btnPay2').click(function(){
      valUser ='<?php
      if (isset($_SESSION["ID_USER"])) {
        echo $_SESSION["ID_USER"];
      }else {
        echo $valida = 0;
      } ?>';

      valTotal = <?php echo $TotalxArtGlobal ?>;

      if (valTotal == 0) {
        alert('No cuenta con artículos en el carrito.');

      }
      else {

        if (valUser == 0) {
          $("#ModalLoginVal").modal("show");
        }
        else {
          validaEnvio();
        }
      }
    });

    $('#btnEntrar').click(function(){

      email= $('#txt_Email').val();
      pass= $('#txt_Pass').val();

      if(email == ""){

        alert("Debe ingresar un E-mail...");
      }
      if(pass == ""){

        alert("Debe ingresar una contraseña...");
      }
      if(email != "" && pass != ""){
        login(email, pass);
      }
    });
    $('#btnEntrarVal').click(function(){

      email= $('#txt_EmailVal').val();
      pass= $('#txt_PassVal').val();

      if(email == ""){

        alert("Debe ingresar un E-mail...");
      }
      if(pass == ""){

        alert("Debe ingresar una contraseña...");
      }
      if(email != "" && pass != ""){
        loginValidaCostoEnv(email, pass);
      }
    });
    $('#btnLogOut').click(function(){
      vaciar = 1;

      logOut(vaciar);

    });

    function validaEnvio(){
      if($("#customRadio1").is(':checked') || $("#customRadio2").is(':checked')) {

        precioEnvio = $('input:radio[name=rbDelivery]:checked').val();

        if (precioEnvio == 'express') {
          x = 700;
        }
        else{
          x = 250;
        }
        pruebas(x);
      }else{
        alert('Debe seleccionar un metodo de envío.');
      }

    }

    <?php
    if (isset($_SESSION['ID_ARTICLES'])) {
      $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
      foreach ($ID_ARTICLES as $key => $item) {
        $id = $item['id'];
        ?>
        $('#qty<?php echo $id ?>').val(<?php echo $item['cantidad'] ?>);

        <?php }
      }
      ?>

    });

    </script>

<?php
session_start();
$bagNumber = 0;
$TotalxArtGlobal = 0;
$cantidad = 0;
$key = 1;
$valMin =1;
$valMax = 1;
$queryVal=0;

if (isset($_POST['VaciarFilterP'])) {
  unset($_SESSION['filtro_price']);
}

// se crea la sesion para el filtro por precio
if (isset($_POST['MinVal']) && isset($_POST['MaxVal']) && isset($_POST['QUERY'])) {
  $_SESSION['filtro_price'][0]=
  array(
    "min" => $_POST['MinVal'],
    "max" => $_POST['MaxVal'],
    "material" => $_POST['Material'],
    "accesorio" => $_POST['Accesorio'],
    "query" => $_POST['QUERY']);
  }

  if (isset($_SESSION['filtro_price'])) {
    $queryVal = $_SESSION['filtro_price'][0]['query'];
  }

  if (isset($_SESSION['ID_ARTICLES'])) {
    $bagNumber = count($_SESSION['ID_ARTICLES']);
    $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
  }

  if (isset($_POST['VACIAR_LOGIN'])) {
    unset($_SESSION['ID_USER']);
    unset($_SESSION['Email']);
  }

  //Imprimiendo datos globales del carrito
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
  $p =   $key+1;

  // anydando articulos al carrito
  if(isset($_POST['ID']) && isset($_POST['PRECIO']) && isset($_POST['CANTIDAD'])) {
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
      <title>Silver - Evolution | Joyas - Hombre</title>

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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

      <script src="js/jquery/jquery-2.2.4.min.js"></script>
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

            <a class="center"><strong>Usuario:</strong>
              <?php
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
                  <a href="cart.php"><span class="cart_quantity"> <?php echo $bagNumber ?> </span> <i class="ti-bag"></i><strong> Carrito:</strong>  $<?php echo number_format($TotalxArtGlobal,2) ?></a>
                  <!-- Cart List Area Start -->

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
                        <a class="dropdown-item" href="cart.php">Carrito</a>
                        <a class="dropdown-item" href="checkout.html">Resiva</a>
                      </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#"><span class="karl-level">hot</span>Dresses</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#ModalRegistroUsuarios">Sign In</a></li>
                  </ul>
                </div>
              </nav>
            </div>
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
            <!-- Modal para registro de Usuarios -->
            <div class="modal fade" id="ModalRegistroUsuarios" tabindex="-1" role="dialog" aria-labelledby="ModalRegistroUsuarios" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="ModalRegistroUsuarios">Registro de Usuario...</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="txtNombre">Nombre(s)</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtNombre" value="" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="txtApellidoP">Apellido Paterno</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtApellidoP" value="" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="txtApellidoM">Apellido Materno</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtApellidoM" value="" required>
                      </div>
                    </div>
                    <h6>Datos de envío...</h6>

                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="txtCalle">Calle</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtCalle" value="" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="txtNumCalle">Núm(#)</label>
                        <input type="number" class="form-control" id="txtNumCalle" value="" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="txtCp">C.P.</label>
                        <input type="number" class="form-control" id="txtCp" value="" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4" "mb-3">
                        <label for="txtCiudad">Ciudad</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtCiudad" value="" required>
                      </div>
                      <div class="col-md-4" "mb-3">
                        <label for="txtEstado">Estado</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtEstado" value="" required>
                      </div>
                      <div class="col-md-4" "mb-3">
                        <label for="txtCel">Celular</label>
                        <input type="number" class="form-control" id="txtCel" value="" required>
                      </div>
                    </div>
                    <br/>
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="txtNombre">Nombre(s) quien recibe</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtNombre_Recibe" value="" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="txtApellidoP">Apellido P. Quien recibe</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtApellidoP_Recibe" value="" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="txtApellidoM">Apellido M. Quien recibe</label>
                        <input type="text" onkeyup="mayus(this);" class="form-control" id="txtApellidoM_Recibe" value="" required>
                      </div>
                    </div>
                    <br/>
                    <h6>Datos de cuenta...</h6>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="txtEmail">E-MaiL</label>
                        <input type="email" onkeyup="minus(this);" class="form-control" id="txtEmail" value="" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="txtPass">Contraseña</label>
                        <input type="password" class="form-control" id="txtPass" value="" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 mb-3">
                        <label id="lbRoll" for="cbmRoll">Roll</label>
                        <select id="cbmRoll"  class="form-control" name="state">
                          <option value="0">Selecciona...</option>
                          ...
                          <option value="ADMIN">ADMINISTRADOR</option>
                          ...
                          <option value="COMUN">COMÚN</option>form-control
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardar">Registrarse</button>
                  </div>
                </div>
              </div>
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
  <!-- <P><?php   var_dump($_SESSION['filtro_price']); ?></P> -->
  <section class="top-discount-area d-md-flex align-items-center">
    <!-- Single Discount Area -->
    <div class="single-discount-area">
      <h5>Apresurate &amp; Atrevete a ganar mas</h5>
      <h6><a href="#">Compra ya</a></h6>
    </div>
    <!-- Single Discount Area -->
    <div class="single-discount-area">
      <h5>Silver Evolution</h5>
      <h6>Tu mejor opción</h6>
    </div>
    <!-- Single Discount Area -->
    <div class="single-discount-area">
      <h5>Empresa 100% Mexicana</h5>
      <h6>Crecé con nosotros</h6>
    </div>
  </section>

  <?php
  require_once "php/Conexion.php";
  $con = conexion();
  $pagina = 0;
  if (isset($_GET['p'])) {
    $pagina = $_GET['p'];
  }
  $cantidadRegistros = 50;
  $Reg_Ignorados = $pagina * $cantidadRegistros;

  if($queryVal == 2) {
    // if (isset($_SESSION['filtro_price'])) {
    $valMin = $_SESSION['filtro_price'][0]['min'];
    $valMax = $_SESSION['filtro_price'][0]['max'];
    $material = $_SESSION['filtro_price'][0]['material'];
    $accesorio = $_SESSION['filtro_price'][0]['accesorio'];

    if ($material == 1) {
      $material = '___________';
    }
    elseif($material == 'ACERO'){
      $material = '%AC';
    }

    if ($accesorio == 1) {
      $accesorio = '___________';
    }

    $sql="SELECT
    CVE_ART,
    DESCR as Nombre,
    COSTO_PROM,
    CVE_IMAGEN,
    DESCR as Descripcion
    FROM INVE13 i
    WHERE CVE_ART LIKE '$material' AND
    CVE_ART  LIKE '$accesorio' AND
    COSTO_PROM BETWEEN $valMin AND $valMax AND
    STATUS = 'A'
    ORDER BY COSTO_PROM
    OFFSET $Reg_Ignorados ROWS
    FETCH NEXT  $cantidadRegistros ROWS ONLY";
  }
  else {

    $sql="SELECT
    CVE_ART,
    DESCR as Nombre,
    COSTO_PROM,
    CVE_IMAGEN,
    DESCR as Descripcion
    FROM INVE13
    ORDER BY CVE_ART
    OFFSET $Reg_Ignorados ROWS
    FETCH NEXT  $cantidadRegistros ROWS ONLY";

  }
  // print_r($sql);
  $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
  if (0 !== sqlsrv_num_rows($res)){
    while ($category = sqlsrv_fetch_array($res)) {
      ?>
      <!-- ****** Quick View Modal Area Start ****** -->
      <div class="modal fade" id="quickview<?php echo $category['CVE_ART'] ?>" tabindex="-1" role="dialog" aria-labelledby="quickview" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <div class="modal-body">
              <div class="quickview_body">
                <div class="container">

                  <div class="row">

                    <div class="col-12 col-lg-5">
                      <div class="quickview_pro_img">
                        <img src="img/product-img/<?php echo $category['CVE_IMAGEN'] ?>.JPG" alt="">
                      </div>
                    </div>
                    <div class="col-12 col-lg-7">
                      <div class="quickview_pro_des">
                        <h4 class="title"><?php echo $category['Nombre'] ?></h4>
                        <div class="top_seller_product_rating mb-15">
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
                        </div>
                        <h5 class="price">$<?php echo number_format($category['COSTO_PROM'],2) ?> <span>$624</span></h5>
                        <p>Marca: SILVER</p>
                        <p><?php echo $category['Descripcion'] ?></p>
                      </div>
                      <div class="row">
                        <!-- Add to Cart Form -->
                        <!-- <form id="formEnvio" class="cart" method="post"> -->
                        <div class="quantity">
                          <button type="button" class="qty-minus" id="btnMenos<?php echo $category['CVE_ART'] ?>">-</button>
                          <input type="number" class="qty-text" id="qty<?php echo $category['CVE_ART'] ?>" name="CANTIDAD" value="1">
                          <button type="button" class="qty-minus" id="btnMas<?php echo $category['CVE_ART'] ?>">+</button>

                        </div>
                        <input type="hidden" name="ID" id="txtid<?php echo $category['CVE_ART'] ?>" value="<?php echo $category['CVE_ART'] ?>">
                        <input type="hidden" name="NOMBRE" id="txtnombre<?php echo $category['CVE_ART'] ?>" value="<?php echo $category['Nombre'] ?>">
                        <input type="hidden" name="PRECIO" id="txtprecio<?php echo $category['CVE_ART'] ?>" value="<?php echo $category['COSTO_PROM'] ?>">
                        <input type="hidden" name="URL" id="txturl<?php echo $category['CVE_ART'] ?>" value="<?php echo $category['CVE_IMAGEN'] ?>">
                        <button type="button" class="btn cart-submit" id="btnSendPost<?php echo $category['CVE_ART'] ?>"> + CARRITO</button>
                        <script type="text/javascript">
                        $(document).ready(function(){
                          $('#btnSendPost<?php echo $category['CVE_ART'] ?>').click(function(){

                            id= $('#txtid<?php echo $category['CVE_ART'] ?>').val();
                            nombre= $('#txtnombre<?php echo $category['CVE_ART'] ?>').val();
                            precio= $('#txtprecio<?php echo $category['CVE_ART'] ?>').val();
                            url= $('#txturl<?php echo $category['CVE_ART'] ?>').val();
                            cantidad= $('#qty<?php echo $category['CVE_ART'] ?>').val();

                            AddCart(id,
                              nombre,
                              precio,
                              url,
                              cantidad);

                            });
                            $('#btnMenos<?php echo $category['CVE_ART'] ?>').click(function(){
                              valor = document.getElementById("qty<?php echo $category['CVE_ART'] ?>");
                              valor.value --;

                            });
                            $('#btnMas<?php echo $category['CVE_ART'] ?>').click(function(){
                              valor = document.getElementById("qty<?php echo $category['CVE_ART'] ?>");
                              valor.value ++;

                            });

                            var input = document.getElementById("qty<?php echo $category['CVE_ART'] ?>");
                            // Execute a function when the user releases a key on the keyboard
                            input.addEventListener("keyup", function(event) {
                              // Number 13 is the "Enter" key on the keyboard
                              if (event.keyCode === 13) {
                                // Cancel the default action, if needed
                                event.preventDefault();
                                // Trigger the button element with a click
                                document.getElementById("btnSendPost<?php echo $category['CVE_ART'] ?>").click();
                              }
                            });
                          });
                          </script>

                        </div>
                        <!-- END ENVIO DE DATOS POR URL ESCONDIDA -->
                        <div class="share_wf mt-30">
                          <p>Comparte con tus amigos</p>
                          <div class="_icon">
                            <a href="https://es-la.facebook.com/newsilverevolution/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      sqlsrv_close($con);
    }
    ?>
    <!-- ****** Quick View Modal Area End ****** -->

    <section class="shop_grid_area section_padding_100">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-4 col-lg-3">
            <div class="shop_sidebar_area">

              <div class="widget catagory mb-50">
                <!--  Side Nav  -->
                <div class="nav-side-menu">
                  <h6 class="mb-0">Categorías</h6>
                  <div class="menu-list">
                    <ul id="menu-content2" class="menu-content collapse out">
                      <!-- Single Item -->
                      <li data-toggle="collapse" data-target="#women2">
                        <a href="#">Joyeria</a>
                        <ul class="sub-menu collapse show" id="women2">
                          <li><a href="joyas-m.php">Mujer</a></li>
                        </ul>
                      </li>

                      <!-- Single Item -->
                      <li data-toggle="collapse" data-target="#Bolsas" class="collapsed">
                        <a href="#">Bolsas</a>
                        <ul class="sub-menu collapse" id="Bolsas">
                          <li><a href="#">Hombre</a></li>
                          <li><a href="#">Mujer</a></li>
                        </ul>
                      </li>

                      <!-- Single Item -->
                      <li data-toggle="collapse" data-target="#Perfumes" class="collapsed">
                        <a href="#">Perfumes</a>
                        <ul class="sub-menu collapse" id="Perfumes">
                          <li><a href="#">Hombre</a></li>
                          <li><a href="#">Mujer</a></li>
                        </ul>
                      </li>

                      <!-- Single Item -->
                      <li data-toggle="collapse" data-target="#Ropa" class="collapsed">
                        <a href="#">Ropa</a>
                        <ul class="sub-menu collapse" id="Ropa">
                          <li><a href="#">Hombre</a></li>
                          <li><a href="#">Mujer</a></li>
                        </ul>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="widget price mb-50">
                <h6 class="widget-title mb-30">Filtro por precio</h6>
                <button type="button" id="btnLimpiarPriceFilter" class="btn btn-danger btnDel">X</button>
                <div class="widget-desc">
                  <div class="slider-range">
                    <div class="slidecontainer">
                      <label class="range-price">Min: $
                        <input type="number" id="minVal" class="sinBordeRangePrice" name="" value="<?php
                        if(isset($_SESSION['filtro_price'])){
                          echo $_SESSION['filtro_price'][0]['min'];
                        } ?>" min="0">
                        <input type="range" min="1" max="1000" step="0.01" value="0" class="ui-slider-range ui-widget-header ui-corner-all" id="myRangeMin">
                      </label>
                      <label class="range-price">Max: $
                        <input type="number" id="maxVal" class="sinBordeRangePrice" name="" value="<?php
                        if(isset($_SESSION['filtro_price'])){
                          echo $_SESSION['filtro_price'][0]['max'];
                        } ?>" min="0">
                        <input type="range" min="1" max="1000" step="0.01" value="0" class="ui-slider-range ui-widget-header ui-corner-all" id="myRangeMax">
                      </label>
                      <button type="button" class="btn btnSearch" id="btnBusPrecio">Filtrar</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="widget color mb-70">
                <h6 class="widget-title mb-30">Filtro por Material: <label style="color:#FF0000;">
                  <?php
                  if (isset($_SESSION['filtro_price'])) {

                    if ($_SESSION['filtro_price'][0]['material'] == 1) {
                      echo " ";
                    }
                    else {
                      echo $_SESSION['filtro_price'][0]['material'];
                    }
                  }
                  ?>
                </label>

              </h6>
              <div class="widget-desc">
                <select id="cbmMaterial"  class="form-control" name="material">
                  <option value="0">Selecciona...</option>
                  <option value="%OL">ORO LAMINADO</option>
                  <option value="%PL">PLATA</option>
                  <option value="ACERO">ACERO</option>
                  <option value="%RD">RODIO</option>
                </select>
                <br/>
                <ul class="d-flex justify-content-between">
                  <li class="yellow"><a href="#"></a></li>
                  <li class="gray"><a href="#"></a></li>
                  <li class="red"><a href="#"></a></li>
                  <li class="green"><a href="#"></a></li>
                  <li class="teal"><a href="#"></a></li>
                  <li class="cyan"><a href="#"></a></li>
                </ul>
              </div>
              <br/><br/>
              <button type="button" class="btn btnSearch" id="btnBusMaterial">Filtrar</button>

            </div>

            <div class="widget color mb-70">
              <h6 class="widget-title mb-30">Filtro por Accesorio: <label style="color:#FF0000;">
                <?php
                if (isset($_SESSION['filtro_price'])) {

                  if ($_SESSION['filtro_price'][0]['accesorio'] == 1) {
                    echo " ";
                  }
                  else {
                    echo $_SESSION['filtro_price'][0]['accesorio'];
                  }
                }
                ?>
              </label>

            </h6>
            <div class="widget-desc">

              <select id="cbmAccesorio"  class="form-control" name="accesorio">
                <option value="0">Selecciona...</option>
                <option value="ALI%">ALINZA</option>
                <option value="ANI%">ANILLOS</option>
                <option value="ARO%">AROS</option>
                <option value="ARR%">ARRACADA</option>
                <option value="ART%">ARETE</option>
                <option value="BRO%">BROQUEL</option>
                <option value="BRZ%">BRAZALETE</option>

                <option value="CDN%">CADENA</option>
                <option value="COL%">COLLAR</option>
                <option value="DIJ%">DIJE</option>

                <option value="ESC%">ESCAPULARIO</option>
                <option value="FIN%">FIN DE SEMANA</option>
                <option value="GRG%">GARGANTILLA</option>
                <option value="GRP%">GRAPAS</option>
                <option value="JGS%">JUEGOS</option>
                <option value="LLV%">LLAVERO</option>
                <option value="OMG%">OMEGA</option>
                <option value="PIS%">PISA CORBATA</option>
                <option value="PLS%">PULSERA</option>
                <option value="PRE%">PRENDEDOR</option>
                <option value="REJ%">RELOJ</option>
                <option value="ROS%">ROSARIO</option>
                <option value="SMR%">SEMANARIO</option>
                <option value="TOB%">TOBILLERA</option>
                <option value="VIO%">VIOLADOR</option>

              </select>
              <br/><br/>
              <ul class="d-flex justify-content-between">
                <li class="yellow"><a href="#"></a></li>
                <li class="gray"><a href="#"></a></li>
                <li class="red"><a href="#"></a></li>
                <li class="green"><a href="#"></a></li>
                <li class="teal"><a href="#"></a></li>
                <li class="cyan"><a href="#"></a></li>
              </ul>
            </div>
            <br/><br/>
            <button type="button" class="btn btnSearch" id="btnBusAcs">Filtrar</button>

          </div>
        </div>
      </div>
      <div class="col-12 col-md-8 col-lg-9">
        <div class="shop_grid_product_area">
          <div class="row">
            <?php
            require_once "php/Conexion.php";
            $con = conexion();
            $pagina = 0;
            if (isset($_GET['p'])) {
              $pagina = $_GET['p'];
            }
            $cantidadRegistros = 50;
            $Reg_Ignorados = $pagina * $cantidadRegistros;

            if($queryVal == 2) {
              // if (isset($_SESSION['filtro_price'])) {
              $valMin = $_SESSION['filtro_price'][0]['min'];
              $valMax = $_SESSION['filtro_price'][0]['max'];
              $material = $_SESSION['filtro_price'][0]['material'];
              $accesorio = $_SESSION['filtro_price'][0]['accesorio'];

              if ($material == 1) {
                $material = '___________';
              }
              elseif($material == 'ACERO'){
                $material = '%AC';
              }

              if ($accesorio == 1) {
                $accesorio = '___________';
              }

              $sql="SELECT
              CVE_ART,
              DESCR as Nombre,
              COSTO_PROM,
              CVE_IMAGEN,
              DESCR as Descripcion
              FROM INVE13 i
              WHERE CVE_ART LIKE '$material' AND
              CVE_ART  LIKE '$accesorio' AND
              COSTO_PROM BETWEEN $valMin AND $valMax AND
              STATUS = 'A'
              ORDER BY COSTO_PROM
              OFFSET $Reg_Ignorados ROWS
              FETCH NEXT  $cantidadRegistros ROWS ONLY";
            }
            else {

              $sql="SELECT
              CVE_ART,
              DESCR as Nombre,
              COSTO_PROM,
              CVE_IMAGEN,
              DESCR as Descripcion
              FROM INVE13
              ORDER BY CVE_ART
              OFFSET $Reg_Ignorados ROWS
              FETCH NEXT  $cantidadRegistros ROWS ONLY";

            }
            $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
            if (0 !== sqlsrv_num_rows($res)){
              while ($category = sqlsrv_fetch_array($res)) {

                ?>

                <!-- Single gallery Item -->
                <div class="col-12 col-sm-6 col-lg-4 single_gallery_item wow fadeInUpBig" data-wow-delay="0.2s">
                  <!-- Product Image -->
                  <div class="product-img">
                    <img src="img/product-img/<?php echo $category['CVE_IMAGEN'] ?>.jpg" alt="">
                    <div class="product-quicview">
                      <a href="#" data-toggle="modal" data-target="#quickview<?php echo $category['CVE_ART'] ?>"><i class="ti-plus"></i></a>
                    </div>
                  </div>
                  <!-- Product Description -->
                  <div class="product-description">
                    <h4 class="product-price">$<?php echo number_format($category['COSTO_PROM'],2) ; ?></h4>
                    <p><?php echo $category['Nombre'] ?></p>
                    <!-- Add to Cart -->
                    <!-- <a href="#" class="add-to-cart-btn">ADD TO CART</a> -->
                  </div>
                </div>
                <?php
              }
              sqlsrv_close($con);
            }
            ?>

            <div>
            </div>
          </div>
        </div>
        <div class="shop_pagination_area wow fadeInUp" data-wow-delay="1.1s">

          <nav aria-label="Page navigation">
            <!-- <ul class="pagination pagination-sm"> -->
            <ul class="pagination">
              <?php
              $i=0;
              require_once "php/Conexion.php";
              $con = conexion();
              $pagina = 0;
              if (isset($_GET['p'])) {
                $pagina = $_GET['p'];
              }
              $cantidadRegistros = 5;
              $Reg_Ignorados = $pagina * $cantidadRegistros;

              $sql="SELECT 0
              FROM INVE13
              WHERE STATUS = 'A'
              ORDER BY CVE_ART
              OFFSET $Reg_Ignorados ROWS
              FETCH NEXT  $cantidadRegistros ROWS ONLY";
              ?>
              <li><a href="joyas-h.php?p=<?php echo $pagina-1?>">«</a></li>
              <?php
              $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
              if (0 !== sqlsrv_num_rows($res)){
                while ($category = sqlsrv_fetch_array($res)) {
                  ?>

                  
                  <!-- <li class="page-item active"><a class="page-link" href="joyas-h.php?p=<?php echo $i?>"><?php echo $i ?></a></li> -->
                  <li><a href="joyas-h.php?p=<?php echo $i?>"><?php echo $i ?></a></li>
                  <?php
                  $i++;
                }
                ?>
                  <li><a href="joyas-h.php?p=<?php echo $pagina+1?>">»</a></li>
                <?php
                sqlsrv_close($con);
              } ?>
              <!-- <li class="page-item"><a class="page-link" href="#">02</a></li>
              <li class="page-item"><a class="page-link" href="#">03</a></li> -->
            </ul>
          </nav>
        </div>

      </div>

    </div>
  </div>
</section>

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
              <a href="https://es-la.facebook.com/newsilverevolution/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
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

  $('#btnLogOut').click(function(){
    vaciar = 1;

    logOut(vaciar);
  });

  $('#btnGuardar').click(function(){


    nombre = $('#txtNombre').val();
    apellidoP = $('#txtApellidoP').val();
    apellidoM = $('#txtApellidoM').val();
    calle = $('#txtCalle').val();
    numCalle = $('#txtNumCalle').val();
    cp = $('#txtCp').val();
    ciudad = $('#txtCiudad').val();
    estado = $('#txtEstado').val();
    cel = $('#txtCel').val();
    nombre_Recibe = $('#txtNombre_Recibe').val();
    apellidoP_Recibe = $('#txtApellidoP_Recibe').val();
    apellidoM_Recibe = $('#txtApellidoM_Recibe').val();
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
    roll = $("#cbmRoll option:selected").val();

    if(nombre == ""){

      alert("Debe ingresar un nombrel...");
    }
    if(apellidoP == ""){

      alert("Debe ingresar un apellido paterno...");
    }if(apellidoM == ""){

      alert("Debe ingresar un apellido Materno...");
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

    if(nombre_Recibe == ""){

      alert("Debe ingresar un nombre de quien recibirá el producto...");
    }
    if(apellidoP_Recibe == ""){

      alert("Debe ingresar un apellido paterno de quien recibirá el producto...");
    }if(apellidoM_Recibe == ""){

      alert("Debe ingresar un apellido Materno de quien recibirá el producto...");
    }

    if (txtCel.value.length != 10) {
      alert('El número celular es incorrecto ya que tiene ' + txtCel.value.length + ' caracteres y debe contener 10...');
      txtCel.focus();
    }

    if(email == ""){

      alert("Debe ingresar un E-mail...");
    }
    if(pass == ""){

      alert("Debe ingresar una contraseña...");
    }
    if(roll == 0){

      alert("Debe seleccionar un roll de usuario...");
    }
    if(nombre != "" &&
    apellidoP != "" &&
    apellidoM != "" &&
    calle != "" &&
    numCalle != "" &&
    cp != "" &&
    ciudad != "" &&
    estado != "" &&
    cel != "" &&
    nombre_Recibe != "" &&
    apellidoP_Recibe != "" &&
    apellidoM_Recibe != "" &&
    txtCel.value.length == 10  && email != "" && email !=1 && pass != "" && roll !=0){
      agregarUsuarios(nombre,
        apellidoP,
        apellidoM,
        calle,
        numCalle,
        cp,ciudad,
        estado,
        cel,
        nombre_Recibe,
        apellidoP_Recibe,
        apellidoM_Recibe,
        email,
        pass,
        roll);
      }
    });

    $('#btnBusPrecio').click(function(){
      query=0;
      minval = parseInt($('#minVal').val());
      maxval = parseInt($('#maxVal').val());
      material = 1;
      accesorio = 1;
      if (minval != 0 && maxval != 0) {
        query = 2;
      }
      if (minval > maxval) {
        alert('El monto mínimo no puede ser mayor que el monto máximo.')
      }
      if (minval < maxval && maxval > minval ) {
        filtros(minval,maxval,material,accesorio,query);
      }
    });

    $('#btnLimpiarPriceFilter').click(function(){
      vaciar=1;
      limpiarPriceFilter(vaciar);
    });

    $('#btnBusMaterial').click(function(){
      minval = 0;
      maxval = 100000;
      material = $("#cbmMaterial option:selected").val();
      accesorio = 1;
      query = 0;

      if(material == 0){
        alert("Debe seleccionar un material...");
      }else{
        query = 2;
        filtros(minval,maxval,material,accesorio,query);
      }
    });

    $('#btnBusAcs').click(function(){
      query = 0;

      minval = 0;
      maxval = 100000;
      material = 1;
      accesorio = $('#cbmAccesorio option:selected').val();
      if(accesorio == 0){
        alert("Debe seleccionar un accesorio...");
      }else{
        query = 2;
        filtros(minval,maxval,material,accesorio,query);
      }

    });

    // Enter de inicio de sesion
    var input = document.getElementById("txt_Pass");
    input.addEventListener("keyup", function(event) {
      // Number 13 is the "Enter" key on the keyboard
      if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("btnEntrar").click();
      }
    });

  });

  function mayus(e) {
    e.value = e.value.toUpperCase();
  }
  function minus(e) {
    e.value = e.value.toLowerCase();
  }

  function validar_email( email )
  {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
  }

  var slider = document.getElementById("myRangeMin");
  var sliderMax = document.getElementById("myRangeMax");
  // $('#minVal').val(slider.value);
  // $('#maxVal').val(sliderMax.value);

  slider.oninput = function() {
    $('#minVal').val(slider.value);
  }
  sliderMax.oninput = function() {
    $('#maxVal').val(sliderMax.value);
  }


</script>

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
    <title>Silver - Evolution | Inicio</title>

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
        <div class="col-md-4 error">
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
      <button type="button" class="btn btn-link" data-toggle="modal" data-target="#ModalRegistroUsuarios">Regístrate</button>
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
<header class="header_area">
  <!-- Top Header Area Start -->
  <div class="top_header_area">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-end">

        <div class="col-12 col-lg-7">
          <div class="top_single_area d-flex align-items-center">
            <!-- Logo Area -->
            <div class="top_logo">
              <a href="#"><img src="img/core-img/logo_silv.png" alt=""></a>
            </div>


            <!-- Cart & Menu Area -->
            <div class="header-cart-menu d-flex align-items-center ml-auto">
              <!-- Cart Area -->
              <div class="cart">
                <a href="cart.php"><span class="cart_quantity"> <?php
                if(isset($_SESSION['ID_ARTICLES'])){

                  $bagNumber = count($_SESSION['ID_ARTICLES']);

                }
                else{
                  $bagNumber=0;
                }

                echo $bagNumber ?> </span> <i class="ti-bag"></i><strong> Carrito:</strong>  $<?php echo number_format($TotalxArtGlobal,2) ?></a>
                <!-- Cart List Area Start -->
                <!-- <ul class="cart-list">

                <?php foreach ($aCarrito as $key => $value) {

                $TotalxArt = $value['PRECIO'] * $value['CANTIDAD'];
                ?>
                <li>
                <a href="#" class="image"><img src="<?php echo $value['URL'] ?>" class="cart-thumb" alt=""></a>
                <div class="cart-item-desc">
                <h6><a href="#"><?php echo $value['NOMBRE'] ?></a></h6>
                <p> <?php echo $value['CANTIDAD'] ?>  x - <span class="price">$<?php echo $TotalxArt ?></span></p>
              </div>
              <span class="dropdown-product-remove"><i class="icon-cross"></i></span>
            </li>
          <?php } ?>
          <li class="total">
          <span class="pull-right">Total: $<?php echo $TotalxArtGlobal ?></span>
          <a href="cart.php" class="btn btn-sm btn-cart">Carrito</a>
          <a href="checkout.php" class="btn btn-sm btn-checkout">Pagar</a>
        </li>
      </ul> -->
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
          <!-- <a href="#"><span class="karl-level">Comparte</span> <i class="fa fa-pinterest" aria-hidden="true"></i></a> -->
          <a href="https://es-la.facebook.com/newsilverevolution/"><span class="karl-level">Comparte</span><i class="fa fa-facebook" aria-hidden="true"></i></a>
          <!-- <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a> -->
          <!-- <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a> -->
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
        <!-- Menu Area -->
        <div class="main-menu-area">
          <nav class="navbar navbar-expand-lg align-items-start">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#karl-navbar" aria-controls="karl-navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"><i class="ti-menu"></i></span></button>

            <div class="collapse navbar-collapse align-items-start collapse" id="karl-navbar">
              <ul class="navbar-nav animated" id="nav">
                <li class="nav-item active"><a class="nav-link" href="#"></a></li>
                <li class="nav-item active"><a class="nav-link" href="#"></a></li>
                <li class="nav-item active"><a class="nav-link" href="#"></a></li>
                <li class="nav-item active"><a class="nav-link" href="#"></a></li>

                <div class="<?php
                if (isset($_SESSION["status"]) && $_SESSION["status"] == 'ADMIN') {
                  echo $category = 'inline';
                }else {
                  echo $category = 'none';
                } ?>">

                <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#ModalViewClientes"><span class="karl-level">Seleccione</span>Cliente</a></li>
              </div>
              <li class="nav-item active"><a class="nav-link" href="index.php">Inicio</a></li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="karlDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categorías</a>
                <div class="dropdown-menu" aria-labelledby="karlDropdown">
                  <a class="dropdown-item" href="joyas-m.php">Joyería</a>
                  <a class="dropdown-item" href="#">Bolsas</a>
                  <a class="dropdown-item" href="#">Perfumes</a>
                  <a class="dropdown-item" href="#">Ropa</a>
                </div>
              </li>


              <div class="<?php
              if (isset($_SESSION["Email"])) {
                echo $ocultar = 'none';
              }else {
                echo $mostrar = 'inline';
              } ?> ">
              <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#ModalRegistroUsuarios">Regístrate</a></li>
            </div>

            <div class="<?php
            if (isset($_SESSION["status"]) && $_SESSION['status'] == 'ADMIN') {
              echo $mostrar = 'inline';
            }else {
              echo $ocultar = 'none';
            } ?> ">
            <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#ModalRegistroCliente">Registrar Cliente</a></li>
          </div>

        </ul>
      </div>
    </nav>
  </div>

  <!-- Modal para View Status -->
  <div class="modal fade" id="ModalViewStatus" tabindex="-1" role="dialog" aria-labelledby="ModalViewStatus" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalViewStatus">Mensaje del sistema...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <br/>
          <!-- <br/> -->
          <!-- <br/> -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <h6><strong>Su pedido fue generado de forma correcta...</strong></h6>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal para View StatusLoginError -->
  <div class="modal fade" id="ModalViewStatusLoginError" tabindex="-1" role="dialog" aria-labelledby="ModalViewStatusLoginError" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalViewStatusLoginError">Mensaje del sistema...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <br/>
          <!-- <br/> -->
          <!-- <br/> -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <h6><strong>Usuario o contraseña incorrecto...</strong></h6>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal para View Status remision -->
  <div class="modal fade" id="ModalViewStatusRemision" tabindex="-1" role="dialog" aria-labelledby="ModalViewStatusRemision" aria-hidden="true">
    <div class="modal-dialog modal-smd" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalViewStatusRemision">Mensaje del sistema...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <br/>
          <!-- <br/> -->
          <!-- <br/> -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <h5><strong>El pago se aprobó de forma correcta...</strong></h5>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para View Clientes -->
  <div class="modal fade" id="ModalViewClientes" tabindex="-1" role="dialog" aria-labelledby="ModalViewClientes" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalViewClientes">Clientes Registrados...</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-5">
            </div>
            <div class="col-md-5">
              <input type="text" name="txtBusc" id="txtBus" value="" class="form-control" placeholder="Nombre del cliente">
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-warning" id="btnBus" name="button">Buscar</button>
            </div>
          </div>
          <br/>
          <br/>
          <div class="<?php
          if ($_SESSION["status"] == 'ADMIN' && isset($_SESSION["BUS_CLIENTE"])) {
            echo 'inline';
          }else {
            echo 'none';
          } ?>">
          <div class="row">
            <div class="col-md-5 mb-3">
              <p id=""><strong>Cliente:</strong></p>
            </div>
            <div class="col-md-5 mb-3">
              <p><strong>E-MAIL:</strong></p>
            </div>
            <div class="col-md-1 mb-3">
            </div>
          </div>
        </div>
        <div class="scroll-div">
          <?php
          if ($_SESSION['status'] == 'ADMIN') {

            if(isset($_SESSION['BUS_CLIENTE'])){

              require_once "php/Conexion.php";
              $con = conexion();
              $ID = $_SESSION['ID_USER'];
              $sql = "SELECT
              CLAVE,
              rtrim(ltrim(CLAVE)) AS CLAVE2,
              CRUZAMIENTOS_ENVIO AS CORREO,
              NOMBRE
              FROM CLIE" .$BD. "
              WHERE CVE_VEND='$ID' AND NOMBRE LIKE '%".$_SESSION['BUS_CLIENTE']."%'";

              $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
              if (0 !== sqlsrv_num_rows($res)){
                while ($user = sqlsrv_fetch_array($res)) {
                  ?>
                  <div class="row">
                    <div class="col-md-5 mb-3">
                      <p><?php echo $user['NOMBRE'] ?></p>
                    </div>

                    <div class="col-md-5 mb-3">
                      <p><?php echo $user['CORREO'] ?></p>
                    </div>
                    <div class="col-md-1 mb-3">
                      <button type="button" class="btn btn-success" id="btnGetClient<?php echo $user['CLAVE2'] ?>"></button>
                    </div>
                    <input type="hidden" name="clave" value="<?php echo $user['CLAVE'] ?>" id="txtClave<?php echo $user['CLAVE2'] ?>" >
                  </div>
                  <script type="text/javascript">
                  $(document).ready(function(){

                    $('#btnGetClient<?php echo $user['CLAVE2'] ?>').click(function(){
                      debugger;
                      id_cliente = $('#txtClave<?php echo $user['CLAVE2'] ?>').val();
                      getCliente(id_cliente);
                    });
                  });
                  </script>
                  <?php
                }
              }
              sqlsrv_close($con);
            }
          }
          ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary" id="btnGuardarC">Registrarse</button> -->
      </div>
    </div>
  </div>
</div>

<!-- Modal para View Status $_SESSION BUS_CLIENTE -->
<div class="modal fade" id="ModalViewStatusBUS_CLIENTE" tabindex="-1" role="dialog" aria-labelledby="ModalViewStatusBUS_CLIENTE" aria-hidden="true">
  <div class="modal-dialog modal-smd" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalViewStatusBUS_CLIENTE">Mensaje del sistema...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <br/>
        <!-- <br/> -->
        <!-- <br/> -->
        <div class="row">
          <div class="col-md-12 mb-3">
            <h5><strong>Debe seleccionar un cliente......</strong></h5>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para registro de Clientes -->
<div class="modal fade" id="ModalRegistroCliente" tabindex="-1" role="dialog" aria-labelledby="ModalRegistroCliente" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalRegistroCliente">Registro de Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="txtNombreC">Nombre(s)</label>
            <input type="text" onkeyup="mayus(this);" class="form-control" id="txtNombreC" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtApellidoPC">Apellido Paterno</label>
            <input type="text" onkeyup="mayus(this);" class="form-control" id="txtApellidoPC" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtApellidoMC">Apellido Materno</label>
            <input type="text" onkeyup="mayus(this);" class="form-control" id="txtApellidoMC" value="" required>
          </div>
        </div>
        <h6>Datos de dirección...</h6>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="txtCalleC">Calle</label>
            <input type="text" onkeyup="mayus(this);" class="form-control" id="txtCalleC" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtNumCalleC">Núm(#)</label>
            <input type="number" class="form-control" id="txtNumCalleC" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtCpC">C.P.</label>
            <input type="number" class="form-control" id="txtCpC" value="" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4" "mb-3">
            <label for="txtCiudadC">Ciudad</label>
            <input type="text" onkeyup="mayus(this);" class="form-control" id="txtCiudadC" value="" required>
          </div>
          <div class="col-md-4" "mb-3">
            <label for="txtEstadoC">Estado</label>
            <input type="text" onkeyup="mayus(this);" class="form-control" id="txtEstadoC" value="" required>
          </div>
          <div class="col-md-4" "mb-3">
            <label for="txtCelC">Celular</label>
            <input type="number" class="form-control" id="txtCelC" value="" required>
          </div>
        </div>
        <br/>
        <br/>
        <h6>Datos de cuenta...</h6>
        <div class="row">
          <div class="col-md-12 mb-12">
            <label for="txtEmailC">E-MaiL</label>
            <input type="email" onkeyup="minus(this);" class="form-control" id="txtEmailC" value="" required>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarC">Registrarse</button>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Registrarse</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para registro de Articulos -->
<div class="modal fade bd-example-modal-lg" id="ModalArticulos" tabindex="-1" role="dialog" aria-labelledby="ModalArticulos" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalArticulos">Registro de Artículos...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="txtNameArt">Nombre</label>
            <input type="text" class="form-control" id="txtNameArt" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtDescArt">Descripción</label>
            <input type="text" class="form-control" id="txtDescArt" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtBarCode">Codigo de barra</label>
            <input type="text" class="form-control" id="txtBarCode" value="" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="txtModelo">Modelo</label>
            <input type="text" class="form-control" id="txtModelo" value="" required>
          </div>
          <div class="col-md-4 mb-3">
            <label id="lblcbmMarca" for="cbmMarca">Marca</label>
            <select class="form-control" id="cbmMarca" name="marca">
              <option value="0">Selecciona...</option>
              <?php
              require_once "php/Conexion.php";
              $con = conexion();

              $sql = "SELECT ID_BRAND, NAME_BRAND FROM brand";

              $result = mysqli_query($con,$sql);
              while($marca = mysqli_fetch_row($result)){

                echo '<option value="'.$marca[0].'">'.$marca[1].'</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="txtPrecio">Precio</label>
            <input type="number" class="form-control" id="txtPrecio" value="0" required>
          </div>

        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label id="lbcategoria" for="cbmCategoria">Categoría</label>
            <select id="cbmCategoria"  class="form-control" name="state">
              <option value="0">Selecciona...</option>
              <?php
              require_once "php/Conexion.php";
              $con = conexion();

              $sql = "SELECT ID_CATEGORY, NAME_CAT FROM categories";

              $result = mysqli_query($con,$sql);
              while($marca = mysqli_fetch_row($result)){

                echo '<option value="'.$marca[0].'">'.$marca[1].'</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label id="lbSubcategoria" for="cbmSubcategoria">Subcategoría</label>
            <select id="cbmSubcategoria"  class="form-control" name="state">
              <option value="0">Selecciona...</option>
              <?php
              require_once "php/Conexion.php";
              $con = conexion();

              $sql = "SELECT ID_SUB_CATEGORY, NAME_SUB_CAT FROM sub_categories";

              $result = mysqli_query($con,$sql);
              while($marca = mysqli_fetch_row($result)){

                echo '<option value="'.$marca[0].'">'.$marca[1].'</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label id="lbStatus" for="cbmStatus">Estatus</label>
            <select id="cbmStatus"  class="form-control" name="state">
              <option value="2">Selecciona...</option>
              ...
              <option value="1">Activo</option>
              ...
              <option value="0">Inactivo</option>form-control
            </select>
          </div>

        </div>
        <div class="row">
          <div class="col-md-12 mb-12">
            <label for="txtNameIMG">Carga de Img</label>
            <input id="sortpicture" type="file" class="form-control" name="sortpic" />
            <button id="upload" class="form-control">Upload</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarArt">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- Help Line -->
<div class="help-line">
  <a href="tel:921 119 77 85"><i class="ti-headphone-alt"></i> 921 119 77 85</a>
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
<!-- ****** Top Discount Area End ****** -->

<!-- ****** Welcome Slides Area Start ****** -->
<section class="welcome_area">
  <div class="welcome_slides owl-carousel">
    <!-- Single Slide Start -->
    <div class="single_slide height-800 bg-img background-overlay" style="background-image: url(img/bg-img/bg-1.jpg);">
      <div class="container h-100">
        <div class="row h-100 align-items-center">
          <div class="col-12">
            <div class="welcome_slide_text">
              <h6 data-animation="bounceInDown" data-delay="0" data-duration="500ms">* Excelencia y calidad</h6>
              <h2 data-animation="fadeInUp" data-delay="500ms" data-duration="500ms">Tendencia de moda</h2>
              <a href="joyas-m.php" class="btn karl-btn" data-animation="fadeInUp" data-delay="1s" data-duration="500ms">Compra Ya</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Single Slide Start -->
    <div class="single_slide height-800 bg-img background-overlay" style="background-image: url(img/bg-img/bg-4.jpg);">
      <div class="container h-100">
        <div class="row h-100 align-items-center">
          <div class="col-12">
            <div class="welcome_slide_text">
              <h6 data-animation="fadeInDown" data-delay="0" data-duration="500ms">* Excelencia y calidad</h6>
              <h2 data-animation="fadeInUp" data-delay="500ms" data-duration="500ms">Summer Collection</h2>
              <a href="#" class="btn karl-btn" data-animation="fadeInLeftBig" data-delay="1s" data-duration="500ms">Check Collection</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ****** Welcome Slides Area End ****** -->

<!-- ****** Top Catagory Area Start ****** -->
<section class="top_catagory_area d-md-flex clearfix">
  <!-- Single Catagory -->
  <div class="single_catagory_area d-flex align-items-center bg-img" style="background-image: url(img/bg-img/bg-2.jpg);">
    <div class="catagory-content">
      <!-- <h6>On Accesories</h6> -->
      <h2>precio especial</h2>
      <!-- <a href="#" class="btn karl-btn">SHOP NOW</a> -->
    </div>
  </div>
  <!-- Single Catagory -->
  <div class="single_catagory_area d-flex align-items-center bg-img" style="background-image: url(img/bg-img/bg-3.jpg);">
    <div class="catagory-content">
      <!-- <h6>in Bags excepting the new collection</h6> -->
      <h2>Nuestros diseños</h2>
      <!-- <a href="#" class="btn karl-btn">SHOP NOW</a> -->
    </div>
  </div>
</section>
<!-- ****** Top Catagory Area End ****** -->

<!-- ****** Quick View Modal Area Start ****** -->
<div class="modal fade" id="quickview" tabindex="-1" role="dialog" aria-labelledby="quickview" aria-hidden="true">
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
                  <img src="img/product-img/product-1.jpg" alt="">
                </div>
              </div>
              <div class="col-12 col-lg-7">
                <div class="quickview_pro_des">
                  <h4 class="title">Boutique Silk Dress</h4>
                  <div class="top_seller_product_rating mb-15">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                  </div>
                  <h5 class="price">$120.99 <span>$130</span></h5>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia expedita quibusdam aspernatur, sapiente consectetur accusantium perspiciatis praesentium eligendi, in fugiat?</p>
                  <a href="#">View Full Product Details</a>
                </div>
                <!-- Add to Cart Form -->
                <form class="cart" method="post">
                  <div class="quantity">
                    <span class="qty-minus" onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty ) &amp;&amp; qty &gt; 1 ) effect.value--;return false;"><i class="fa fa-minus" aria-hidden="true"></i></span>

                    <input type="number" class="qty-text" id="qty" step="1" min="1" max="12" name="quantity" value="1">

                    <span class="qty-plus" onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;"><i class="fa fa-plus" aria-hidden="true"></i></span>
                  </div>
                  <button type="submit" name="addtocart" value="5" class="cart-submit">Add to cart</button>
                  <!-- Wishlist -->
                  <div class="modal_pro_wishlist">
                    <a href="wishlist.html" target="_blank"><i class="ti-heart"></i></a>
                  </div>
                  <!-- Compare -->
                  <div class="modal_pro_compare">
                    <a href="compare.html" target="_blank"><i class="ti-stats-up"></i></a>
                  </div>
                </form>

                <div class="share_wf mt-30">
                  <p>Comparte con tus amigos</p>
                  <div class="_icon">
                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
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
<!-- ****** Quick View Modal Area End ****** -->


<!-- ****** Popular Brands Area Start ****** -->
<section class="karl-testimonials-area section_padding_100">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section_heading text-center">
          <h2>Testimonials</h2>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 col-md-8">
        <div class="karl-testimonials-slides owl-carousel">

          <!-- Single Testimonial Area -->
          <div class="single-testimonial-area text-center">
            <span class="quote">"</span>
            <h6>Nunc pulvinar molestie sem id blandit. Nunc venenatis interdum mollis. Aliquam finibus nulla quam, a iaculis justo finibus non. Suspendisse in fermentum nunc.Nunc pulvinar molestie sem id blandit. Nunc venenatis interdum mollis. </h6>
            <div class="testimonial-info d-flex align-items-center justify-content-center">
              <div class="tes-thumbnail">
                <img src="img/bg-img/tes-1.jpg" alt="">
              </div>
              <div class="testi-data">
                <p>Michelle Williams</p>
                <span>Client, Los Angeles</span>
              </div>
            </div>
          </div>

          <!-- Single Testimonial Area -->
          <div class="single-testimonial-area text-center">
            <span class="quote">"</span>
            <h6>Nunc pulvinar molestie sem id blandit. Nunc venenatis interdum mollis. Aliquam finibus nulla quam, a iaculis justo finibus non. Suspendisse in fermentum nunc.Nunc pulvinar molestie sem id blandit. Nunc venenatis interdum mollis. </h6>
            <div class="testimonial-info d-flex align-items-center justify-content-center">
              <div class="tes-thumbnail">
                <img src="img/bg-img/tes-1.jpg" alt="">
              </div>
              <div class="testi-data">
                <p>Michelle Williams</p>
                <span>Client, Los Angeles</span>
              </div>
            </div>
          </div>

          <!-- Single Testimonial Area -->
          <div class="single-testimonial-area text-center">
            <span class="quote">"</span>
            <h6>Nunc pulvinar molestie sem id blandit. Nunc venenatis interdum mollis. Aliquam finibus nulla quam, a iaculis justo finibus non. Suspendisse in fermentum nunc.Nunc pulvinar molestie sem id blandit. Nunc venenatis interdum mollis. </h6>
            <div class="testimonial-info d-flex align-items-center justify-content-center">
              <div class="tes-thumbnail">
                <img src="img/bg-img/tes-1.jpg" alt="">
              </div>
              <div class="testi-data">
                <p>Michelle Williams</p>
                <span>Client, Los Angeles</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>
<!-- ****** Popular Brands Area End ****** -->

<!-- ****** Footer Area Start ****** -->
<footer class="footer_area">
  <div class="container">
    <div class="row">
      <!-- Single Footer Area Start -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="single_footer_area">

        </div>
      </div>
      <!-- Single Footer Area Start -->
      <div class="col-12 col-sm-6 col-md-3 col-lg-2">
        <div class="single_footer_area">
        </div>
      </div>
      <!-- Single Footer Area Start -->
      <div class="col-12 col-md-6 col-lg-3">
        <div class="single_footer_area">
          <div class="footer-logo">
            <img src="img/core-img/logo_silv.png" alt="">
          </div>
          <div class="copywrite_text d-flex align-items-center">
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a> &amp; distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
            </div>
          </div>
        </div>
        <!-- Single Footer Area Start -->
        <div class="col-12 col-lg-5">
          <div class="single_footer_area">
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


// =========================================================================
<?php if(isset($_SESSION['status']) && $_SESSION['status'] == 'ADMIN' && !isset($_SESSION['BUS_CLIENTE'])){ ?>

  $('#ModalViewClientes').modal('toggle');

  <?php } ?>
  // =========================================================================



  // =========================================================================
  <?php if(isset($_GET['Vcs']) && $_GET['Vcs'] == 4) {

    ?>
    $('#ModalViewStatusBUS_CLIENTE').modal('toggle');

    <?php
  }
  ?>
  // =========================================================================




  // =========================================================================
  <?php if(isset($_GET['o_cli']) && $_GET['o_cli'] == 1) {

    ?>
    $('#ModalViewClientes').modal('toggle');

    <?php
  }
  ?>
  // =========================================================================





  // =========================================================================
  <?php if(isset($_GET['vaciar']) && $_GET['vaciar'] == 2) {

    ?>
    $('#ModalViewStatus').modal('toggle');

    <?php
  }
  elseif(isset($_GET['vaciar']) && $_GET['vaciar'] == 3){
    ?>
    $('#ModalViewStatusRemision').modal('toggle');

    <?php } ?>
    // =========================================================================

    $(document).ready(function(){


      var validaImg =0;
      var nameArticulo ="";
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


        roll = 'COMUN';

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

        $('#btnGuardarC').click(function(){
          debugger;
          nombre = $('#txtNombreC').val();
          apellidoP = $('#txtApellidoPC').val();
          apellidoM = $('#txtApellidoMC').val();
          calle = $('#txtCalleC').val();
          numCalle = $('#txtNumCalleC').val();
          cp = $('#txtCpC').val();
          ciudad = $('#txtCiudadC').val();
          estado = $('#txtEstadoC').val();
          cel = $('#txtCelC').val();
          nombre_Recibe = ' ';
          apellidoP_Recibe = ' ';
          apellidoM_Recibe = ' ';
          email= $('#txtEmailC').val();

          if(validar_email( email ))
          {
          }
          else
          {
            alert("El correo: " +email+ " no contiene el formato correcto, verifíquelo...");
            email = 1;
          }
          pass= 'Silver2020';


          roll = 'COMUN';

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

          if (txtCelC.value.length != 10) {
            alert('El número celular es incorrecto ya que tiene ' + txtCelC.value.length + ' caracteres y debe contener 10...');
            txtCelC.focus();
          }

          if(email == ""){

            alert("Debe ingresar un E-mail...");
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
          txtCelC.value.length == 10  && email != "" && email !=1){
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

          $('#btnGuardarArt').click(function(){

            nomArt= $('#txtNameArt').val();
            descArt= $('#txtDescArt').val();
            barCode = $('#txtBarCode').val();
            modelArt = $('#txtModelo').val();
            marcaArt = $("#cbmMarca option:selected").val();
            precioArt = $('#txtPrecio').val();
            categoria = $("#cbmCategoria option:selected").val();
            subCatego = $("#cbmSubcategoria option:selected").val();
            statusArt = $("#cbmStatus option:selected").val();
            nombreImg = $('#txtNameIMG').val();


            if(nomArt == ""){

              alert("Debe ingresar el nombre del artículo...");
            }
            if(descArt == ""){

              alert("Debe ingresar la descripción del artículo...");
            }
            if(barCode == ""){

              alert("Debe ingresar el codigo de barra del artículo...");
            }

            if (txtBarCode.value.length != 11) {
              alert('El codigo de barra es incorrecto ya que tiene una longitud de ' + txtBarCode.value.length + ' y debe contener 11 caracteres...');
              txtBarCode.focus();
            }

            if(modelArt == ""){

              alert("Debe ingresar el modelo del artículo...");
            }
            if(marcaArt == 0){

              alert("Debe seleccionar una marca...");
            }
            if(precioArt == "" && precioArt !=0){

              alert("Debe ingresar el precio del artículo...");
            }
            if(categoria == 0){

              alert("Debe seleccionar una categoría...");
            }
            if(subCatego == 0){

              alert("Debe seleccionar una subcategoría...");
            }
            if(statusArt == 2){

              alert("Debe seleccionar un estatus del artículo...");
            }

            if (validaImg == 0) {

              alert("Debe cargar la imagen del artículo...");
            }

            if (nomArt != "" && descArt != "" && barCode != "" && txtBarCode.value.length == 11 && modelArt != "" && precioArt != "" && precioArt != 0 && nameArticulo != "" && marcaArt !=0 && statusArt !=2 && validaImg != 0){
              guardarArt(nomArt,
                descArt,
                barCode,
                modelArt,
                marcaArt,
                precioArt,
                categoria,
                subCatego,
                statusArt,
                nameArticulo);
                validaImg=0;
                nameArticulo = "";
              }

            });
            $('#upload').on('click', function() {

              var file_data = $('#sortpicture').prop('files')[0];
              var form_data = new FormData();
              form_data.append('file', file_data);
              $.ajax({
                url: 'cargaIMG.php', // point to server-side PHP script
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(result){
                  if (result != "") {
                    validaImg =1;
                    nameArticulo = result;
                    document.getElementById("sortpicture").value = "";
                  }else{
                    validaImg=0;
                    document.getElementById("sortpicture").value = "";
                  }
                }
              });
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


            $('#btnLogOut').click(function(){
              vaciar = 1;

              logOut(vaciar);
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


            $('#btnBus').click(function(){

              nombreCliente = $('#txtBus').val();

              if(nombreCliente == ""){
                alert("Debe ingresar un nombre o apellido...");
              }
              if(nombreCliente != "" ){
                busClienteConsigna(nombreCliente);
              }
            });

            // Enter buscador
            var txtBus = document.getElementById("txtBus");
            txtBus.addEventListener("keyup", function(event) {
              // Number 13 is the "Enter" key on the keyboard
              if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("btnBus").click();
              }
            });

            // Enter guardar clientes Consigna
            var txtEmailC = document.getElementById("txtEmailC");
            txtEmailC.addEventListener("keyup", function(event) {
              // Number 13 is the "Enter" key on the keyboard
              if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("btnGuardarC").click();
              }
            });

            // Enter guardar clientes
            var txtEmail = document.getElementById("txtEmail");
            txtEmail.addEventListener("keyup", function(event) {
              // Number 13 is the "Enter" key on the keyboard
              if (event.keyCode === 13) {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("btnGuardar").click();
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
          </script>

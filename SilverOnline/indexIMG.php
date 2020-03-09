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

  <body>



    <div id="wrapper">
      <div class="row">
        <div class="col-md-2">
          <a href="#" data-toggle="modal" data-target="#ModalViewAccount"><i class="ti-user"></i><strong> Mi cuenta</strong></a>
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
            <div class="col-md-4">

            </div>
            <div class="col-md-3">
              <input type="text" name="txtBuscName" id="txtBusName" value="" class="form-control" placeholder="Nombre">

            </div>
            <div class="col-md-3">
              <input type="text" name="txtBuscApe" id="txtBusApe" value="" class="form-control" placeholder="Apellido">
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
                    <input type="hidden" name="nombre" value="<?php echo $user['NOMBRE'] ?>" id="txtNombreCh<?php echo $user['CLAVE2'] ?>" >

                  </div>
                  <script type="text/javascript">
                  $(document).ready(function(){

                    $('#btnGetClient<?php echo $user['CLAVE2'] ?>').click(function(){
                      id_cliente = $('#txtClave<?php echo $user['CLAVE2'] ?>').val();
                      nombre = $('#txtNombreCh<?php echo $user['CLAVE2'] ?>').val();
                      debugger;

                      getCliente(id_cliente,nombre);
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

<!-- Modal view account -->
<div class="modal fade" id="ModalViewAccount" tabindex="-1" role="dialog" aria-labelledby="ModalViewAccount" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalViewAccount">Datos de mi cuenta...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <br/>
        <div class="row">
          <div class="col-md-12 mb-3">
            <h4>Usuario:</h4>
            <p><?php
            if (isset($_SESSION["Email"])) {
              echo $_SESSION["Email"];
            }else {
              echo $invitado = 'Invitado...';
            } ?></p>
          </div>

        </div>
        <div class="<?php
        if (isset($_SESSION["status"]) && $_SESSION["status"] == 'ADMIN' && isset($_SESSION["BUS_CLIENTE"]) && strlen($_SESSION['BUS_CLIENTE']) <= 10) {
          echo 'inline';
        }else {
          echo 'none';
        } ?>">
        <h4>Seleccionó el cliente:</h4>
        <a class="center"><?php
        if (isset($_SESSION["BUS_CLIENTE"]) && strlen($_SESSION['BUS_CLIENTE']) > 9) {
          echo $_SESSION["BUS_CLIENTE"];
        }?>
      </a>
    </div>
  </div>
  <div class="modal-footer">

    <div class="<?php
    if (isset($_SESSION["Email"])) {

      echo $mostrar = 'inline';
    }else {
      echo $ocultar = 'none';
    } ?> ">
    <button type="button" class="btn btn-warning" id="btnLogOut">Salir</button>
  </div>

  <div class="<?php
  if (isset($_SESSION["Email"])) {

    echo $ocultar = 'none';
  }else {
    echo $mostrar = 'inline';
  } ?>">
  <button type="button" id="btnEntrarModal" class="btn btn-success" data-toggle="modal" data-target="#ModalLogin">Entrar</button>
</div>
<div class="<?php
if (isset($_SESSION["Email"])) {

  echo $ocultar = 'none';
}else {
  echo $mostrar = 'inline';
} ?>">
<button type="button" id="btnRegistrateModal" class="btn btn-info" data-toggle="modal" data-target="#ModalRegistroUsuarios">Regístrate</button>
</div>
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




<!-- ****** Footer Area End ****** -->
</div>
<!-- /.wrapper end -->

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
I.EXIST,
I.CVE_ART,
I.DESCR as Nombre,
PP.PRECIO AS ULT_COSTO,
I.CVE_IMAGEN,
I.DESCR as Descripcion
FROM INVE" .$BD. " I
INNER JOIN PRECIO_X_PROD" .$BD. " PP ON PP.CVE_ART = I.CVE_ART
WHERE I.EXIST > 0 AND
PP.CVE_PRECIO = $ID_PRECIO AND
I.CVE_ART = '".$_GET['SKU']. "'";

$res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
if (0 !== sqlsrv_num_rows($res)){
  while ($category = sqlsrv_fetch_array($res)) {
    $EXISTENCIA = $category['EXIST'];

    $precio = $category['ULT_COSTO'];


    ?>
    <div class="row">

      <div class="col-12 col-lg-4" align="center" >
        <img id="img<?php echo $_GET['SKU']?>" src="images/small/<?php echo $_GET['SKU']?>.jpg" height="200px" width="110px" data-zoom-image="images/large/<?php echo $_GET['SKU']?>-.jpg"/>
        <br/>
        <img id="img<?php echo $_GET['SKU']?>2" src="images/small/<?php echo $_GET['SKU']?>.1.jpg" height="200px" width="110px" data-zoom-image="images/large/<?php echo $_GET['SKU']?>-1.jpg"/>
        <br/>
        <img id="img<?php echo $_GET['SKU']?>3" src="images/small/<?php echo $_GET['SKU']?>.2.jpg" height="200px" width="110px" data-zoom-image="images/large/<?php echo $_GET['SKU']?>-2.jpg"/>

      </div>
      <br/>
      <br/>
      <br/>
      <br/>
      <div class="col-12 col-lg-7">

        <div class="quickview_pro_des">
          <div id="showIMG">

          </div>
          <h4 class="title"><?php echo $category['Nombre'] ?></h4>
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
          <p style="color: #ff084e;"><strong>STOCK DISPONIBLE: <?php echo $category['EXIST'] ?></strong></p>
        </div>

        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        

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
    <?php
  }
  sqlsrv_close($con);
}
?>
<!-- </section> -->
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
  zoomWindowHeight: 400,
  zoomWindowWidth:430,
  borderSize: 1,
  easing:true
});
$("#img<?php echo $_GET['SKU']?>2").elevateZoom({
  zoomWindowPosition: "showIMG ",
  scrollZoom : true,
  cursor: "crosshair",
  zoomWindowHeight: 400,
  zoomWindowWidth:430,
  borderSize: 1,
  easing:true
});
  $("#img<?php echo $_GET['SKU']?>3").elevateZoom({
    zoomWindowPosition: "showIMG ",
    scrollZoom : true,
    cursor: "crosshair",
    zoomWindowHeight: 400,
    zoomWindowWidth:430,
    borderSize: 1,
    easing:true
  });





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
    pass= $('#txtPass').val();
    roll = 'COMUN';

    if(nombre == ""){

      alertify.error("Debe ingresar un nombre.");

      return false;
    }
    if(apellidoP == ""){

      alertify.error("Debe ingresar un apellido paterno.");

      return false;

    }if(apellidoM == ""){

      alertify.error("Debe ingresar un apellido Materno.");

      return false;
    }
    if(calle == ""){
      alertify.error("Debe ingresar una calle.");

      return false;

    }if(numCalle == ""){
      alertify.error("Debe ingresar un número de la ubicación.");

      return false;
    }
    if(cp == ""){
      alertify.error("Debe ingresar un código postal.");

      return false;
    }
    if(ciudad == ""){
      alertify.error("Debe ingresar una ciudad.");

      return false;
    }
    if(estado == ""){
      alertify.error("Debe ingresar un estado.");

      return false;
    }
    if(cel == ""){
      alertify.error("Debe ingresar un número de contacto.");

      return false;
    }

    if (txtCel.value.length != 10) {
      alertify.error("El número celular es incorrecto ya que tiene " + txtCel.value.length + " caracteres y debe contener 10.");
      txtCel.focus();
      return false;
    }

    if(nombre_Recibe == ""){
      alertify.error("Debe ingresar un nombre de quien recibirá el producto.");

      return false;
    }
    if(apellidoP_Recibe == ""){
      alertify.error("Debe ingresar un apellido paterno de quien recibirá el producto.");

      return false;
    }

    if(apellidoM_Recibe == ""){
      alertify.error("Debe ingresar un apellido Materno de quien recibirá el producto.");

      return false;
    }

    if(email == ""){
      alertify.error("Debe ingresar un E-mail.");

      return false;
    }
    if(validar_email( email ) )
    {
    }
    else
    {
      alertify.error("El correo: " + email + " no contiene el formato correcto, verifíquelo.");

      email = 1;
      return false;
    }

    if(pass == ""){
      alertify.error("Debe ingresar una contraseña.");

      return false;
    }

    if(roll == 0){
      alertify.error("Debe seleccionar un roll de usuario.");

      return false;
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
    txtCel.value.length == 10  &&
    email != "" &&
    email !=1 &&
    pass != "" &&
    roll !=0){
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

      // =================================================
      pass= 'Silver2020';
      roll = 'COMUN';
      // =================================================

      if(nombre == ""){

        alertify.error("Debe ingresar un nombre.");

        return false;
      }
      if(apellidoP == ""){

        alertify.error("Debe ingresar un apellido paterno.");

        return false;

      }if(apellidoM == ""){

        alertify.error("Debe ingresar un apellido Materno.");

        return false;
      }
      if(calle == ""){
        alertify.error("Debe ingresar una calle.");

        return false;

      }if(numCalle == ""){
        alertify.error("Debe ingresar un número de la ubicación.");

        return false;
      }
      if(cp == ""){
        alertify.error("Debe ingresar un código postal.");

        return false;
      }

      if(ciudad == ""){
        alertify.error("Debe ingresar una ciudad.");

        return false;
      }
      if(estado == ""){
        alertify.error("Debe ingresar un estado.");

        return false;
      }
      if(cel == ""){
        alertify.error("Debe ingresar un número de contacto.");

        return false;
      }

      if (txtCelC.value.length != 10) {
        alertify.error("El número celular es incorrecto ya que tiene " + txtCelC.value.length + " caracteres y debe contener 10.");
        txtCelC.focus();
        return false;
      }

      if(email == ""){
        alertify.error("Debe ingresar un E-mail.");

        return false;
      }
      if(validar_email( email ) )
      {
      }
      else
      {
        alertify.error("El correo: " + email + " no contiene el formato correcto, verifíquelo.");

        email = 1;
        return false;
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
      txtCelC.value.length == 10  &&
      email != "" &&
      email !=1){
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


      $('#btnEntrarModal').click(function(){

        $('#ModalViewAccount').hide();
      });


      $('#btnRegistrateModal').click(function(){

        $('#ModalViewAccount').hide();
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
          alertify.error("Debe ingresar un E-mail.");
          return false;
        }
        if(pass == ""){
          alertify.error("Debe ingresar una contraseña.");
          return false;
        }
        if(email != "" && pass != ""){
          login(email, pass);
        }
      });


      $('#btnBus').click(function(){
        nombreCliente = $('#txtBusName').val();
        apellidoCliente = $('#txtBusApe').val();
        debugger;
        if(nombreCliente == ""){
          alertify.error("Debe ingresar un nombre.");
          return false;
        }
        if(apellidoCliente == ""){
          alertify.error("Debe ingresar un apellido.");
          return false;
        }
        if(nombreCliente != "" && apellidoCliente !=""){
          nombreCompleto = nombreCliente + ' ' + apellidoCliente;
          busClienteConsigna(nombreCompleto );
        }
      });

      // Enter buscador
      var txtBus = document.getElementById("txtBusApe");
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

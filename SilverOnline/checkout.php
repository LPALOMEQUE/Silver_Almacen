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
$BD = '01';

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

$ID = '';


$array_stock = [];

// PRECIO CON DESCUENTO (SUPER PRECIO)
$ID_PRECIO = 2;

// FILTRADO POR PRECIO DEPENDIENDO DEL TIPO DE USUARIO
if(isset($_SESSION['status'])){
  if($_SESSION["status"] == 'ADMIN'){
    // PRECIO NORMAL
    $ID_PRECIO = 1;
  }
}

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


if (isset($_SESSION['ID_ARTICLES'])) {
  $bagNumber = count($_SESSION['ID_ARTICLES']);
  $ID_ARTICLES=$_SESSION['ID_ARTICLES'];
}

//Vaciamos el la session
if (isset($_POST['VACIAR_LOGIN'])) {
  unset($_SESSION['ID_USER']);
  unset($_SESSION['Email']);
  unset($_SESSION['status']);
  unset($_SESSION['ID_CLIENTE']);
  unset($_SESSION['BUS_CLIENTE']);
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
  <title>Siler - Evolution | Revisa</title>

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
  <script src="alertifyjs/alertify.js"></script>
  <script src="js/funciones.js"></script>
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
    <div class="row">
      <div class="col-md-2">
        <a href="#" data-toggle="modal" data-target="#ModalViewAccount"><i class="ti-user"></i><strong> Mi cuenta</strong></a>
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
                <a href="#"><img src="img/core-img/logo_silv.png" alt=""></a>
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
                  if (isset($_GET["Del"]) && $_GET["Del"] == 8) {
                    echo $category = 'inline';
                  }else {
                    echo $category = 'none';
                  } ?>">
                  <li class="nav-item"><a class="nav-link" href="#" data-toggle="modal" data-target="#ModalDelArt"><span class="karl-level">*****</span>Verifica Stock</a></li>
                </div>
                </ul>
              </div>
            </nav>
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
                  if (isset($_SESSION["status"]) && $_SESSION["status"] == 'ADMIN' && isset($_SESSION["BUS_CLIENTE"]) && strlen($_SESSION['BUS_CLIENTE']) > 9) {
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

<!-- Modal para ELIMINAR ARTICULO -->
<div class="modal fade" id="ModalDelArt" tabindex="-1" role="dialog" aria-labelledby="ModalDelArt" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalDelArt">Mensaje del sistema...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="scroll-divDel">

        <div class="modal-body">
          <h4 style="color:#FF0000;">Stock no disponible.</h4>
          <table class="table table-responsive">
            <thead>
              <tr>
                <th>Artículo</th>
                <!-- <th>Status</th> -->
                <th>Stock</th>
                <th>Cart</th>
                <th>C.N</th>

                <th> </th>
              </tr>
            </thead>
            <tbody>

              <?php
              require_once "php/Conexion.php";
              $con = conexion();
              if (isset($_SESSION['ID_ARTICLES'])) {
                foreach ($ID_ARTICLES as $key => $item) {
                  $id= $item['id'];

                  $sql = "SELECT
                  M.EXIST,
                  I.DESCR
                  FROM INVE" .$BD. " I
                  LEFT JOIN MULT" .$BD. " M ON M.CVE_ART = I.CVE_ART
                  where
                  I.EXIST >0 AND
			            M.CVE_ALM=1 AND
			            M.EXIST >0 AND
                  I.CVE_ART= '$id'";

                  $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
                  if (0 !== sqlsrv_num_rows($res)){
                    while ($arti = sqlsrv_fetch_array($res)) {
                      $exist_bd = $arti['EXIST'];
                      $exist_cart = $item['cantidad'];

                      if ( $exist_cart > $exist_bd  ) {
                        // code...


                        ?>
                        <tr>
                          <td class="cart_product_img d-flex align-items-center">
                            <a href="#"><img src="img/product-img/product-12.jpg" alt="Product"></a>
                            <h6 id="h6Nombre<?php echo $id ?>"><?php echo $arti['DESCR'] ?></h6>
                          </td>
                          <!-- <td><h6 style="color:#FF0000;"><br/>No disponible</h6></td> -->
                          <td>
                            <br/>
                            <h6 id="h6Stock<?php echo $id ?>"><br/><?php echo $arti['EXIST'] ?></h6></td>
                          <td>
                            <br/>
                            <h6 id="h6Stock<?php echo $id ?>"><br/><?php echo $exist_cart ?></h6></td>
                          <td>
                            <br/>
                            <input type="number" class="stockNumber" id="qty<?php echo $id ?>" name="CANTIDAD" autocomplete="off"
                            <?php if ($exist_bd == 0){
                              echo 'disabled'
                              ?>



                            <?php } ?>
                            ></td>

                          <td>
                            <br/>
                            <button type="button" class="btn btn-danger" id="btnDel<?php echo $id ?>">X</button>
                          </td>

                        </tr>

                        <script type="text/javascript">
                        $(document).ready(function(){
                          $('#btnDel<?php echo $id ?>').click(function(){
                            debugger;
                            id = '<?php echo $id ?>';
                            posicion = <?php echo $key ?>;
                            valida = 1;
                            eliminarArticuloCheck(id, posicion, valida);

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
                              if (cantidad <= <?php echo $exist_bd ?>) {
                                cartModPriceCheck(id,
                                  cantidad,
                                  posicion);
                                }
                                else {
                                  alertify.error("No hay stock disponible, solo puede agregar la cantidad máxima de: " + <?php echo $exist_bd ?>)
                                  valor.value = "<?php echo $item['cantidad'] ?>";
                                }

                              }
                            });

                        });
                        </script>
                        <?php
                      }
                    }
                  }
                }
                sqlsrv_close($con);
              }
              ?>
            </tbody>
          </table>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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


            if ($_SESSION['status'] == 'ADMIN') {
              $ID = $_SESSION['ID_CLIENTE'];

            }
            else if($_SESSION['status'] == 'COMUN'){
              $ID = $_SESSION["ID_USER"];
            }

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
            WHERE CLAVE='$ID'";

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
          <li>
            <span>Artículos</span>
            <span>CANTIDAD</span>
          </li>
          <div class="scroll-divCheckout">
            <?php
            require_once "php/Conexion.php";
            $con = conexion();
            if (isset($_SESSION['ID_ARTICLES'])) {
              foreach ($ID_ARTICLES as $key => $item) {
                $id= $item['id'];
                $sql = "SELECT DESCR as Nombre FROM INVE" .$BD. " where CVE_ART='$id'";

                $res =  sqlsrv_query($con, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET ));
                if (0 !== sqlsrv_num_rows($res)){
                  while ($arti = sqlsrv_fetch_array($res)) {

                    ?>
                    <li>
                      <span><?php echo $arti['Nombre'] ?></span>
                      <span><?php echo $item['cantidad'] ?> pz(s)</span>
                    </li>

                  <?php }
                }
              }
            }?>
          </div>
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
              <div class="<?php
              if (isset($_SESSION["status"]) && $_SESSION["status"] == 'COMUN') {
                echo 'inline';
              }else {
                echo 'none';
              } ?>">
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
                              amount: {total: '<?php echo number_format($vtaTotal,2); ?>', currency: 'MXN'},
                              description: 'Compra de artículos a Silver Evolution:$<?php echo number_format($vtaTotal,2);?>'
                            }
                          ]
                        });
                      },
                      onAuthorize: function (data, actions) {
                        return actions.payment.execute().then(function () {
                          // console.log(data);
                          window.location="prueba.php?paymentToken="+ data.paymentToken +
                          "&paymentID=" + data.paymentID +
                          "&EMAIL=" + '<?php echo $email ?>';
                        });
                      }
                    }, '#paypal-button-container');
                    </script>

                  </div>
                </div>
              </div>
            </div>
            <div class="<?php
            if (isset($_SESSION["status"]) && $_SESSION["status"] == 'ADMIN') {
              echo 'inline';
            }else {
              echo 'none';
            } ?>">
            <button type="button" class="btn karl-checkout-btn" id="btnConsigna">Pedir</button>

            <!-- <div class="card">
              <div class="card-header" role="tab" id="headingTwo">
                <h6 class="mb-0">
                  <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fa fa-circle-o mr-3"></i>Consigna</a>
                </h6>
              </div>

              <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                  <button type="button" class="btn karl-checkout-btn" id="btnConsigna">Pedir</button>
                </div>
              </div>
            </div> -->
          </div>

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

<script src="https://smtpjs.com/v3/smtp.js"></script>

</body>

</html>

<script type="text/javascript">
$(document).ready(function(){

  alertify.set('notifier','position', 'top-right');



  <?php if(isset($_GET['Del']) && $_GET['Del'] == 8){ ?>

    $('#ModalDelArt').modal('toggle');

    <?php } ?>






    $('#btnLogOut').click(function(){
      vaciar = 1;

      logOut(vaciar);

    });

    $('#btnConsigna').click(function(){

      valStock();

    });


    $('#btnEntrarModal').click(function(){

      $('#ModalViewAccount').hide();

    });

    $('#btnRegistrateModal').click(function(){

      $('#ModalViewAccount').hide();

    });

    $('#btnActualizarDatos').click(function(){
      nombre = $('#txtName').val();
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

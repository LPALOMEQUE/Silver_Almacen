<?php

$aCarrito = array();
$sHTML = '';
$fPrecioTotal = 0;

//Vaciamos el carrito

if(isset($_GET['vaciar'])) {
	unset($_COOKIE['carrito']);
}

//Obtenemos los productos anteriores

if(isset($_COOKIE['carrito'])) {
	$aCarrito = unserialize($_COOKIE['carrito']);
}

//Anyado un nuevo articulo al carrito

if(isset($_GET['nombre']) && isset($_GET['precio'])) {
	$iUltimaPos = count($aCarrito);
	$aCarrito[$iUltimaPos]['nombre'] = $_GET['nombre'];
	$aCarrito[$iUltimaPos]['precio'] = $_GET['precio'];
}

//Creamos la cookie (serializamos)

$iTemCad = time() + (60 * 60);
setcookie('carrito', serialize($aCarrito), $iTemCad);



//Imprimimos el contenido del array

foreach ($aCarrito as $key => $value) {
	$sHTML .= '-> ' . $value['nombre'] . ' ' . $value['precio'] . '<br>';
	$fPrecioTotal += $value['precio'];
}

//Imprimimos el precio total

$sHTML .= '<br>------------------<br>Precio total: ' . $fPrecioTotal;

?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
	<meta charset="UTF-8">
	<title>Ejemplo de carrito</title>
</head>
<body>
	<div>
		<?php echo $sHTML; ?>
	</div>
	<ul>
		<li><a href="test.php?nombre=zapato&precio=32">Zapato</a></li>
		<li><a href="test.php?nombre=vino&precio=10">Vino.</a></li>
		<li><a href="test.php?nombre=curso&precio=30">Curso online</a></li>
		<li><a href="test.php?nombre=reloj&precio=400">Reloj</a></li>
		<li><a href="test.php?nombre=gafas&precio=20">Gafas</a></li>
		<li><a href="test.php?nombre=ordenador&precio=500">Ordenador</a></li>
		<li><a href="test.php?vaciar=1">vaciar carrito</a></li>
	</ul>
</body>
</html>

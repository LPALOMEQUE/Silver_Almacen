<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'/>
	<title>jQuery elevateZoom Demo</title>

  <!-- Favicon  -->
  <link rel="icon" href="img/core-img/favicon.ico">

  <!-- Core Style CSS -->
  <link rel="stylesheet" href="css/core-style.css">
  <link rel="stylesheet" href="style.css">

  <!-- Responsive CSS -->
  <link href="css/responsive.css" rel="stylesheet">

</head>
<body>
<h1>Basic Zoom Example</h1>
<img id="zoom_01" src='images/small/image1.png' data-zoom-image="images/large/image1.jpg"/>



<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src='js/jquery/jquery.elevatezoom.js'></script>
<script>
    $('#zoom_01').elevateZoom({
    zoomType: "inner",
cursor: "crosshair",
zoomWindowFadeIn: 500,
zoomWindowFadeOut: 750
   });
</script>
</body>
</html>

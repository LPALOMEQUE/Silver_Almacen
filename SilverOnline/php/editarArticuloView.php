<?php

require_once "Conexion.php";

$con = conexion();

$barCode = $_POST['BarCode'];
$nameArticulo = "";
$sql ="SELECT NAME_ART, DESCRIPTION FROM articles where BARCODE='$barCode'";
// EL echo NOS RETORNA UN 0 O UN 1 DEPENDIENDO SI EJECUTA O NO EL COMANDO
$result = mysqli_query($con,$sql);

while($category = mysqli_fetch_row($result)){
  $nameArticulo = $category[0];
  // echo $category[1];



  ?>
  <script type="text/javascript">
  $(document).ready(function(){
    $('#txtNameArt').val(<?php echo $$nameArticulo ?>);
  });
  </script>
  <?php
}
?>

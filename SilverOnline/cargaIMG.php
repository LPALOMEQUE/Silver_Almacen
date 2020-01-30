<?php
$validaIMG = 0;
$name ="";

if(isset($_FILES["file"])){
  if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
  }
  else {
    move_uploaded_file($_FILES['file']['tmp_name'], 'img\product-img//' . $_FILES['file']['name']);
    echo $name = $_FILES['file']['name'];
  }
}
?>

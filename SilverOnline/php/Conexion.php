<?php

function conexion(){

  // $serverName = "DESKTOP-9Q38T8N\SQLEXPRESS";
  $serverName = "192.168.3.110";
  $connectionInfo = array( "Database"=>"SAE61", "UID"=>"sa", "PWD"=>"Silver2020");
  $conn = sqlsrv_connect( $serverName, $connectionInfo);
  // if( $conn ) {
  //   echo "Conexión establecida.<br />";
  // }else{
  //   echo "Conexión no se pudo establecer.<br />";
  //   die( print_r( sqlsrv_errors(), true));
  // }
  return $conn;

}
?>

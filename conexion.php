<?php
// Carga la configuración
$config = parse_ini_file('config.ini');

$conexion = mysqli_connect( $config['serverJavi'],$config['usernameJavi'],$config['passwordJavi']) or die ("No se ha podido conectar al servidor de Base de datos");
$db = mysqli_select_db( $conexion,$config['dbnameJavi']) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
if($conexion === false) {
 echo 'Ha habido un error <br>'.mysqli_connect_error();
} else {
 echo 'Conectado a la base de datos';
}


?>

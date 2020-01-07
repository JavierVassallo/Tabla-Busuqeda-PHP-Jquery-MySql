<?php
//Archivo de conexión a la base de datos
require('conexion.php');

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

//Filtro anti-XSS
$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("&lt;", "&gt;", "&quot;", "&#x27;", "&#x2F;", "&#060;", "&#062;", "&#039;", "&#047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

$verificado = '<img src="bien.png" width="20" height="20">';
$cruz = '<img src="mal.png" width="20" height="20">';

$mensaje = "";

if (isset($consultaBusqueda)) {


$stmt = $conexion->prepare("SELECT CONTRATO,GRUPO,PERIODO, email,timestamp AS 'FECHA_ENVIO', ASUNTO,
										CASE
												WHEN ESTADO = '1' AND FECHA_LEIDO IS NOT NULL THEN 'LEIDO'
												ELSE 'NO LEIDO'
										END AS 'LECTURA',
										CASE
												WHEN ESTADO = '1' AND FECHA_LEIDO IS NOT NULL THEN FECHA_LEIDO
												ELSE ''
										END AS 'FECHA_LECTURA',
										CASE
												WHEN ESTADO = '1'  THEN '$verificado'
												ELSE '$cruz'
										END AS ESTADO,
										CASE
												WHEN ESTADO != '1' THEN RAZON_NO_ENVIO
												ELSE ''
										END AS RAZON,
										DISTRIBUIDORA,FECHA_VTO,LINK1,FACTURA
										FROM Eventos_reporte
										WHERE email = ?
										OR CONTRATO = ?
										ORDER BY PERIODO DESC");

$stmt->bind_param('ss', $consultaBusqueda, $consultaBusqueda);

// 's' specifies the variable type => 'string' 
$stmt->execute();
$consulta = $stmt->get_result();

	$filas = mysqli_num_rows($consulta);

	if ($filas === 0) {
		$mensaje = "<p>No hay ningún usuario con el siguiente e-mail o N° de contrato: '".$consultaBusqueda."'</p>";

	} else {
		
		echo '  : Resultados para <strong>'.$consultaBusqueda.'</strong>';
		$mensaje = '
		<link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
		<link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
				<table id="example1" class="table table-bordered table-striped" style="width:auto; white-space: nowrap;">
					<thead>
						<tr>
							<th>CONTRATO</th>
							<th>MAIL</th>
							<th>FACTURA</th>
							<th>PERIODO</th>
							<th>FECHA_ENV</th>
							<th>ASUNTO</th>
							<th>FECHA_LECT</th>
							<th>ESTADO</th>
							<th>DISTRIBUIDORA</th>
							<th>FECHA_VTO</th>
							<th>LINK1</th>
						</tr>
				   </thead>
				   <tbody>';
		while($resultados = mysqli_fetch_array($consulta)) {
			$contrato = $resultados['CONTRATO'];
			$email = $resultados['email'];
			$factura = $resultados['FACTURA'];
			$periodo = $resultados['PERIODO'];
			$fecha_envio = $resultados['FECHA_ENVIO'];
			$asunto = $resultados['ASUNTO'];
			$fecha_lectura = $resultados['FECHA_LECTURA'];
			$estado = $resultados['ESTADO'];
			$distribuidora = $resultados['DISTRIBUIDORA'];
			$fecha_vto = $resultados['FECHA_VTO'];
			$link1 = $resultados['LINK1'];

			$mensaje .= '
			<tr>
		        <td> '.$contrato.' </td>
				<td> '.$email.' </td>
				<td> '.$factura.' </td>
		        <td> '.$periodo.' </td>
		        <td> '.$fecha_envio.' </td>
		        <td> '.$asunto.' </td>
		        <td> '.$fecha_lectura.' </td>
		        <td> '.$estado.' </td> 
		        <td> '.$distribuidora.' </td>
		        <td> '.$fecha_vto.' </td>
				<td> <a  href=https://'.$link1.' target="_blank">descargar</a></td>
       		</tr>';
		};
	$mensaje .= '
				</tbody>
	   				<tfoot>
					   <tr>
							<th>CONTRATO</th>
							<th>MAIL</th>
							<th>FACTURA</th>
							<th>PERIODO</th>
							<th>FECHA_ENV</th>
							<th>ASUNTO</th>
							<th>FECHA_LECT</th>
							<th>ESTADO</th>
							<th>DISTRIBUIDORA</th>
							<th>FECHA_VTO</th>
							<th>LINK1</th>
					   </tr>
	   			</tfoot>
			</table> 
			<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
			<script src="librerias/jquery-3.2.1.min.js"></script>
			<!-- Bootstrap 3.3.7 -->
			<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
			<!-- DataTables -->
			<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
			<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
			<script src="funcionalidad.js"></script>';
		
	};

};
$stmt -> close();
echo $mensaje;
?>

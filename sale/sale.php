<?php
require_once '../conn.php';
date_default_timezone_set('America/Mexico_City');

$function = False;
$response  =array();

if (isset($_GET['getTC'])){
	$function = 'getTC';
}elseif (isset($_GET['ventas'])){
	$ventas = $_GET['ventas'];
	$function = $_GET['function'];
}elseif (isset($_GET['setPedido'])){
	$params = $_GET['setPedido'];
	$function = $_GET['function'];
}elseif(isset($_GET['getlastVenta'])){
	$function = 'getlastVenta';
}elseif (isset($_GET['venta'])){
	$ventas = $_GET['venta'];
	$function = $_GET['function'];
}elseif(isset($_GET['getOrders'])){
	$function = 'getOrders';
}elseif(isset($_GET['getOrdersLine'])){
	$function = 'getOrdersLine';
}

#var_dump($_GET);

$connection = new connnectionAdvantage();

if ($function == 'getTC' ){
	#GET TC FROM CRESCENDO	
	$response = $connection->getTC();
}elseif ($function == 'state') {
	$response = $connection->getstateVentas($ventas);
}elseif ($function == 'setPedido') {
	$response = $connection->setPedido($params);
}elseif ($function == 'getlastVenta') {
	$response = $connection->getLastVenta();
}elseif ($function == 'getDataVenta') {
	$response = $connection->getDataVenta($ventas);
}elseif ($function == 'getOrders') {
	$all_orders = $_GET['getOrders'];
	$fecha_inicial = isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial']:'';
	$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final']:'';

	$response = $connection->getOrdersperMonth($all_orders,$fecha_inicial,$fecha_final);
}elseif ($function == 'getOrdersLine') {
	$all_orders = $_GET['getOrdersLine'];
	$fecha_inicial = isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial']:'';
	$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final']:'';

	$response = $connection->getOrdersLineperMonth($all_orders,$fecha_inicial,$fecha_final);
}

echo ($response );

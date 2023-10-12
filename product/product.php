<?php
require_once '../conn.php';
date_default_timezone_set('America/Mexico_City');

$product = False;
$customer = False;
$function = False;
$moneda = False;

if (isset($_GET['product'])){
	$product = $_GET['product'];
}if (isset($_GET['function'])){
	$function = $_GET['function'];
}if (isset($_GET['customer'])){
	$customer = $_GET['customer'];
}if (isset($_GET['moneda'])){
	$moneda = $_GET['moneda'];
}
#var_dump($moneda);die();

function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}

$connection = new connnectionAdvantage();

if ($function == False){
	#GET PRODUCTS AVAILBLE
	#var_dump($product);die();
	if ($customer != False){
		$produc_ids = $connection->getProductsAvailable($product,$customer,False);
	}else{
		$produc_ids = $connection->getProductsAvailable($product, False,$moneda);
	}
	echo json_encode($produc_ids );die();
}elseif ($function == 'getProducts') {
	$fecha_inicial = isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial']:'';
	$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final']:'';
	$marca = isset($_GET['marca']) ? $_GET['marca']:'';

	$produc_ids = $connection->getProducts($marca,$fecha_inicial,$fecha_final);

	# code...
}

echo ($produc_ids );

#echo json_encode($produc_ids );


?>
<?php
require_once '../conn.php';
date_default_timezone_set('America/Mexico_City');

function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}

$connection = new connnectionAdvantage();

$customer = False;
$function = False;

if (isset($_GET['customer'])){
	$customer = $_GET['function'];
	$function = $_GET['customer'];
}
if ($customer == False){
	$arr = array('NoRows' => 0,
             		 'Status'=>False,
               		 'Data'=>[],
               		 'Error'=>True
               		); 
	echo json_encode($arr );die();
}

#GET ROWS FROM QUERY
if ($function == 'invoice'){
	$params_ids = $connection->getFacturas($customer, 'GET');
}elseif ($function == 'detail') {
	$params_ids = $connection->getCustomer($customer, 'GET');	
}

echo $params_ids;die();

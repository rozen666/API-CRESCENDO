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

if (isset($_GET['function'])){
	$customer = $_GET['customer'];
	$function = $_GET['function'];
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
	$params_ids = $connection->getFacturas($customer);
}elseif ($function == 'detail') {
	$params_ids = $connection->getCustomer($customer);	
}elseif ($function == 'validate') {
	$params_ids = $connection->getCustomerValidate($customer);
}elseif ($function == 'update'){
	$params = $_GET['params'];
	$params_ids = $connection->updateCustomer($params,$customer);
}elseif ($function == 'getlastClient') {
	$params_ids = $connection->getLastCustomer($customer);
}elseif ($function == 'createPool') {
	$date = $_GET['date'];
	$time = $_GET['time'];
	$params_ids = $connection->creaPoolCustomer($customer,$date,$time);
}elseif ($function == 'getPartners') {
	$date = $_GET['date'];	
	$params_ids = $connection->getPartners($date);
}else{
	$arr = array('NoRows' => 0,
             		 'Status'=>False,
               		 'Data'=>[],
               		 'Error'=>'Dont found function'
               		); 
	echo json_encode($arr );die();
}

echo $params_ids;

<?php
require_once 'conn.php';
date_default_timezone_set('America/Mexico_City');

function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}

$connection = new connnectionAdvantage();

$params = False;

if (isset($_GET['params'])){
	$params = $_GET['params'];
}
if ($params == False){
	$arr = array('NoRows' => 0,
             		 'Status'=>False,
               		 'Data'=>[],
               		 'Error'=>True
               		); 
	echo json_encode($arr );die();
}
#var_dump(base64_decode($params));

#GET ROWS FROM QUERY
$params_ids = $connection->executeQuery(base64_decode($params), 'GET');
echo $params_ids;die();

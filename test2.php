<?php
require_once 'conn.php';
date_default_timezone_set('America/Mexico_City');

function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}

$connection = new connnectionAdvantage();

#GET PRODUCTS AVAILBLE
#$produc_ids = $connection->getProductsAvailable();
$produc_ids = $connection->getProductsAvailable('T664120-AL');
#$produc_ids = json_decode($con, true);

#GET TC
// $conTc = $connection->getTC();
// $tc = json_decode($conTc, true);

// var_dump($produc_ids);

// $i = 0 ;
// foreach ($produc_ids['Data'] as $produc_id) {
// 	#var_dump();
// 	$skuCrescendo = trim($produc_id['PRODUCTO']);
// 	$exis = floatval($produc_id['EXIS0']) + floatval($produc_id['EXIS2']) + floatval($produc_id['EXIS3']) + floatval($produc_id['EXIS4']) + floatval($produc_id['EXIS5']);
// 	$produc_ids['Data'][$i]['EXIS'] = $exis;
// 	$connCosp = $connection->executeQuery("SELECT * FROM D_COSP WHERE CLIENTE ='*' AND '2023-05-30' BETWEEN DESDE AND HASTA AND PRODUCTO = '{$skuCrescendo}'", 'GET');
// 	$jsonCosp = json_decode($connCosp, true);
// 	if (count($jsonCosp['Data'])>0){
// 		$produc_ids['Data'][$i]['COST_ORI'] = $produc_id['PRECIO1'];
// 		$produc_ids['Data'][$i]['cPRECIO1'] = $jsonCosp['Data'][0]['PRECIO1'];
// 		$produc_ids['Data'][$i]['cPRECIO2'] = $jsonCosp['Data'][0]['PRECIO2'];
// 		$produc_ids['Data'][$i]['cPRECIO3'] = $jsonCosp['Data'][0]['PRECIO3'];
// 		$produc_ids['Data'][$i]['cPRECIO4'] = $jsonCosp['Data'][0]['PRECIO4'];
// 		$produc_ids['Data'][$i]['cPRECIO5'] = $jsonCosp['Data'][0]['PRECIO5'];
// 		$produc_ids['Data'][$i]['cPRECIO6'] = $jsonCosp['Data'][0]['PRECIO6'];
// 	}
// 	$i+=1;
// 	#var_dump($jsonCosp);
// }
echo json_encode($produc_ids );

// if ($jsondata['Data'][0]['LASTV']== null){
//     //CREAR PULL 10 VENTAS
//     $connmax = $connection->executeQuery("SELECT MAX(VENTA) as LASTV FROM D_VENT", 'GET');
//     $jsonMaxVenta = json_decode($connmax, true);
//     $lastV = (int)$jsonMaxVenta['Data'][0]['LASTV'];
//     $date = date("Y-m-d");
//     $time = date("h:i:s");
//     $date_time = ($date) . 'T' . $time;

//     for ($i = 0; $i < 20; $i++) {       
//         $lastV +=1;
//         $venta = zero_fill($lastV,7);
//         $connection->creaPoolVentas($venta,$date,$time);   
//     }

//     $lastV = $jsonMaxVenta['Data'][0]['LASTV']+1;
//     $lastV = zero_fill($lastV,7);
// }else{
//     //USAMOS LA ULTIMA VENTA VACIA
//     $lastV = $jsondata['Data'][0]['LASTV'];
// }

// var_dump($lastV);


// $con = $connection->executeQuery("SELECT MIN(CLIENTE) as LASTC FROM D_CLIE WHERE CLIENTE = '10000000' AND ID = 'WEB'", 'GET');
// $jsondata = json_decode($con, true);
// //var_dump($jsondata);die();

// if ($jsondata['Data'][0]['LASTC']== null){
//     //CREAR PULL 10 VENTAS
//     $connmax = $connection->executeQuery("SELECT MAX(CLIENTE) as LASTC FROM D_CLIE", 'GET');
//     $jsonMaxVenta = json_decode($connmax, true);
//     $lastV = (int)$jsonMaxVenta['Data'][0]['LASTC'];
//     $date = date("Y-m-d");
//     $time = date("h:i:s");
//     $date_time = ($date) . 'T' . $time;

//     for ($i = 0; $i < 20; $i++) {       
//         $lastV +=1;
//         $venta = zero_fill($lastV,8);
//         $connection->creaPoolCustomer($venta,$date,$time);   
//     }

//     $lastV = $jsonMaxVenta['Data'][0]['LASTC']+1;
//     $lastV = zero_fill($lastV,8);
// }else{
//     //USAMOS LA ULTIMA VENTA VACIA
//     $lastV = $jsondata['Data'][0]['LASTC'];
// }
////////////////////////////////////////////////////////////////////////////////////////////////////77


// $connect_string =  "Driver={Advantage StreamlineSQL ODBC};".
//                    "CommLinks=tcpip(Host=localhost);".
//                    "ServerName='localhost';".
//                    "DatabaseName=ADA;";
    
//     $start = microtime();
 
// #    $connection = odbc_connect("ADA", "", "");
//     $connection = odbc_connect( $connect_string, '', '' );

//     if ($connection){
//     	echo "Yes, were connected!\n";
//     }
//     else{
//         echo(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));

//     }

//     $sql = "SELECT * FROM D_VENT WHERE ID_FECHA>'2022-01-01';";
//     $rs = odbc_exec($connection, $sql);
//     $i = 0;
//     $data=[];
//     while(odbc_fetch_row($rs)){
//         $clie = odbc_result($rs, 'VENTA');
// 	$name = odbc_result($rs, 'ID_FECHA');
//         $state = odbc_result($rs, 'ESTATUS');
//         //echo "$name"."-"."$clie"."\n";
// 	$data[$i] = array('cliente'=>$clie,'name'=>$name, 'estado'=>$state);
//         $i++;
        
//     }
    
//     var_dump($data);
//     echo "Total Ventas: " . count($data) . "\n";
    
//     $final = microtime();
//     echo ($final - $start);




?>
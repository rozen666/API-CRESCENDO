<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('America/Mexico_City');

/**
 * CLASS CONECTION
 */
class connnectionAdvantage
{

	public function connection()
	{
		$connect_string =  "DRIVER={Advantage StreamlineSQL ODBC};
					DataDirectory=X:\GRUPO;
					DefaultType=FoxPro;
					ServerTypes=3;
					CharSet=ANSI;
					MemoBlockSize=64";

		// Connect to DB
		$conn = odbc_connect( $connect_string,'', '' );

		if ($conn){
			return $conn;
		}else{
			echo(json_encode(array('outcome' => false, 'message' => 'No se pudo conectar')));die();

		}
	}

	#CREAR SQL
	public function createSqlLine($type,$tbl,$params,$noCustomer, $field){
		$values= base64_decode($params);			
		#var_dump($values);
		$values2 = explode(",", $values);
		if ($type == 'UPDATE'){
			$sqlLine = 'UPDATE ' . $tbl . ' SET ';
		

			foreach ($values2 as $key) {
				if (strpos($key, ':')){
					#var_dump($key);
					$key = str_replace("{","",$key);
					$key = str_replace("}","",$key);
					$key = str_replace(";",",",$key);
					$key = trim($key);

					$item = str_replace("'",'',trim(explode(":", $key)[0]));
					$val =  str_replace("'",'',trim(explode(":", $key)[1]));
					#$val =  str_replace(";",',',trim(explode(":", $key)[1]));
					
					$sqlLine .= strtoupper($item) . "='" . strtoupper($val) . "',";
				}

			}

			$sqlLine = substr($sqlLine, 0, -1);
			$sqlLine.= " WHERE " . $field . " ='" . $noCustomer . "'";

			echo "***" . " \n";
			echo $sqlLine . "\n";
			echo "***" . " \n";


		}elseif ($type == 'INSERT') {
			$sqlLine = 'INSERT INTO ' . $tbl . '(';

			$keys = '';
			$values = '';
			foreach ($values2 as $key) {
				if (strpos($key, ':')){
					#var_dump($key);
					$key = str_replace("{","",$key);
					$key = str_replace("}","",$key);
					$key = str_replace(";",",",$key);
					$key = trim($key);

					$item = str_replace("'",'',trim(explode(":", $key)[0]));
					$val =  str_replace("'",'',trim(explode(":", $key)[1]));
					#$val =  str_replace(";",',',trim(explode(":", $key)[1]));
					
					$keys .= strtoupper($item). ",";
					$values .= "'" . strtoupper($val) . "',";
				}
			}
			$sqlLine .= $keys .") VALUES (" . $values . ")";
		}
		#var_dump($sqlLine);die();
		return $sqlLine;

	}

	// ********************************+CUSTOMERS*************************************
	#CREATE PULL CLIENTES
	public function updateCustomer($params,$noCustomer){
		$conn = $this->connection();
		$sqlLine = $this->createSqlLine('UPDATE','D_CLIE', $params,$noCustomer, 'CLIENTE' );
		#var_dump($sqlLine);die();
		$values = $this->executeQuery($sqlLine, 'GET' );
		return $values;
	}

	#CREATE PULL CLIENTES
	public function creaPoolCustomer($noCustomer,$date,$time){
		$conn = $this->connection();

		$sql = "INSERT INTO D_CLIE(CLIENTE,NOMBRE,DIAS,CLASIFICA,BLOQUEADO,ID,ID_FECHA,ID_HORA,ID_ALTA,USOCFDI) VALUES('$noCustomer','10000000',0,'3','N','WEB','$date','$time','WEB','G01')";
		
		$this->executeQuery($sql, 'POST' );
	}

	#GET LAST ID CLIENTE
	public function getLastCustomer($noCustomer){
		$conn = $this->connection();

		$sql = "SELECT MIN(CLIENTE) AS LAST_ID FROM D_CLIE WHERE NOMBRE ='{$noCustomer}'";		
		$values = $this->executeQuery($sql, 'GET' );
		#var_dump($values);die();
		return $values;
	}

	#GET CLIENT DATA
	public function getCustomer($noCustomer){
		$conn = $this->connection();

		$sql = "SELECT * FROM D_CLIE WHERE CLIENTE ='{$noCustomer}'";		
		$values = $this->executeQuery($sql, 'GET' );
		return $values;
	}

	#GET DATA FROM REG FISCAL
	public function getCustomerValidate($noCustomer){
		$conn = $this->connection();

		$sql = "SELECT CLIENTE, (SELECT RFC1 FROM D_REGI WHERE CLIENTE = D_CLIE.CLIENTE) AS RFC, RFC AS RFC_1 FROM D_CLIE WHERE CLIENTE ='{$noCustomer}'";		
		$values = $this->executeQuery($sql, 'GET' );
		return $values;
	}

	#GET INVOICES FROM CLIENTS 60 DAYS BEFORE
	public function getFacturas($partner){	
		$conn = $this->connection();

		$hoy = date("Y-m-d");
		$ayer = date("Y-m-d",strtotime($hoy."- 2 month"));

		$sql = "SELECT VENTA,FECHA,VENCE,CARGO,ABONO,RESTANTE FROM D_PAGO WHERE CLIENTE = '{$partner}' AND FECHA BETWEEN '{$ayer}' AND '{$hoy}' AND VENTA IS NOT NULL AND CARGO >0 ORDER BY FECHA DESC";
		
		$values = $this->executeQuery($sql, 'GET' );
		return $values;
	}

	public function getPartners($datePartner){

		$sql = "SELECT * FROM D_CLIE";
		
		if ($datePartner !='' ){
			$sql = "SELECT * FROM D_CLIE WHERE ID_FECHA = '{$datePartner}'";
		}

		$values = $this->executeQuery($sql, 'GET' );
		return $values;

	}

	// ********************************+CUSTOMERS*************************************
	

	// ********************************+VENTAS*************************************

	#CREATE PULL VENTAS
	public function creaPoolVentas($noVenta,$date,$time){	
		$conn = $this->connection();

		$sql = "INSERT INTO D_VENT(VENTA,CLIENTE,PEDIDO,PAGO,VENDEDOR,SUMA,IMPUESTO,TOTAL,SALDO,SUCURSAL,NOTA1,ORIGEN,CONDICION,CONDICION2,TDESC,PENDIENTE,VENTAXWEB,COSTO,COSTOUS,TC,SUMAUS,IMPUESTOUS,TOTALUS,ESTATUS,F_CERRADO,H_CERRADO,ID,ID_FECHA,ID_HORA,ID_VENTA,PESO,PESOVOL,FLETE,VALFLETE,ENTREGAR) VALUES ('" . $noVenta . "','10000000','$date','$date','WEB',0,0,0,0,0,'','','','','N','S',True,0,0,0,0,0,0,'P','$date','$time','WEB','$date','$time','WEB',0,0,'',0,'N')";
		
		$this->executeQuery($sql, 'POST' );
	}

	#GET TYPE CHANGE USD-MX
	public function getTC(){	
		$conn = $this->connection();

		$sql = "SELECT TC FROM D_CTRL";
		
		$values = $this->executeQuery($sql, 'GET' );
		return $values;
	}

	#GET STATUS FOR SELL
	public function getstateVentas($noVentas){	
		$conn = $this->connection();

		$sql = "SELECT LIBERADO,BWCOLOR,AUTORIZO,ESTATUS,SUCURSAL,GUIA,FACTURA FROM D_VENT WHERE VENTA IN ($noVentas)";
					
		$values = $this->executeQuery($sql, 'GET' );
		return $values;

	}

	#GET LAST ID VENTA
	public function getLastVenta(){
		$conn = $this->connection();

		$sql = "SELECT MIN(VENTA) AS LAST_ID FROM D_VENT WHERE CLIENTE ='10000000'";		
		$values = $this->executeQuery($sql, 'GET' );
		return $values;
	}

	#GET INFO VENTA
	public function getDataVenta($venta){
		$conn = $this->connection();

		$sql = "SELECT * FROM D_VENT WHERE VENTA ='{$venta}'";
		$values = $this->executeQuery($sql, 'GET' );
		return $values;
	}

	public function getOrdersperMonth($all_orders,$fecha_inicial,$fecha_final){

		$hoy = date("Y-m-d");
		$ayer = date("Y-m-d",strtotime($hoy."- 1 month"));

		$sql = "SELECT VENTA,ESTATUS,FACTURA,F_FACTURA,PAGO,ID,ID_FECHA,ID_HORA,F_CERRADO,H_CERRADO,LIBERADO,F_LIBERADO,H_LIBERADO,AUTORIZO,ESTAMPA  FROM D_VENT WHERE ID_FECHA BETWEEN '{$ayer}' AND '{$hoy}'";
		
		if ($fecha_inicial !='' && $fecha_final !=''){
			$sql = "SELECT * FROM D_VENT WHERE PEDIDO BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";
			if($all_orders =='no'){
				$sql = $sql." AND (ESTATUS = 'F' or  ESTATUS = 'C')";
			}
		}

		$values = $this->executeQuery($sql, 'GET' );
		return $values;

	}
	public function getOrdersLineperMonth($all_orders,$fecha_inicial,$fecha_final){

		$hoy = date("Y-m-d");
		$ayer = date("Y-m-d",strtotime($hoy."- 1 month"));

		$sql = "SELECT VENTA,CLIENTE,CANTIDAD,PRODUCTO,VENDEDOR,TIPO,CLASIFICA,PRECIO,ESTATUS,FECHA,IMPORTE,ID,ID_FECHA,ID_HORA,TC  FROM D_VEDE WHERE ID_FECHA BETWEEN '{$ayer}' AND '{$hoy}'";
		
		if ($fecha_inicial !='' && $fecha_final !=''){
			$sql = "SELECT * FROM D_VEDE WHERE FECHA BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";
			if($all_orders =='no'){
				$sql = $sql." AND (ESTATUS = 'F' or  ESTATUS = 'C' or  ESTATUS = 'D')";
			}
		}

		$values = $this->executeQuery($sql, 'GET' );
		return $values;

	}


	#SET ORDER TO CRESCENDO
	public function setPedido($params){

		date_default_timezone_set('America/Mexico_City');
		$dateToday = date("Y-m-d");
		$hourToday = date("h:i:s");

		$values= json_decode(base64_decode($params));
		$product_details = $values->detalle;

		#var_dump($values);
		var_dump($product_details);die();

		$totalVenta  = 0;
		$tc  = $values->encabezado->tc;
		$flete  = $values->encabezado->costoflete;

		if ($flete ==''){
			$flete = 0;
		}

		foreach ($product_details as $product) {			
			$totalVenta += ($product->precio*$product->cantidad);
		}

		$totalVentaCimpuesto = round($totalVenta*1.16,2);
		$impuestos = round($totalVentaCimpuesto - $totalVenta,2);
		$sumaUs = round($totalVenta/$tc,2);
		$impuestosUs = round($impuestos/$tc,2);
		$totalVentaCimpuestoUs = round($totalVentaCimpuesto/$tc,2);

		var_dump("...............{$dateToday} - {$hourToday}..................");
		var_dump("EL TOTAL ES: {$totalVenta}");
		var_dump("EL TOTAL DE IMPUESTOS ES: {$impuestos}");
		var_dump("EL TOTAL VENTA C/IVA ES: {$totalVentaCimpuesto}");
		var_dump("EL TOTALUS ES: {$sumaUs}");
		var_dump("EL TOTAL DE IMPUESTOS US ES: {$impuestosUs}");
		var_dump("EL TOTAL VENTA C/IVA US ES: {$totalVentaCimpuestoUs}");
		var_dump("EL Valor del Flete es: {$flete}");
		var_dump(".................................");

		// SAVE COMPUTES VALUES
		$values->encabezado->SUMA = $totalVenta;
		$values->encabezado->IMPUESTO = $impuestos;
		$values->encabezado->TOTAL = $totalVentaCimpuesto;
		$values->encabezado->SALDO = $totalVentaCimpuesto;
		$values->encabezado->SUMAUS = $sumaUs;
		$values->encabezado->IMPUESTOUS = $impuestosUs;
		$values->encabezado->TOTALUS = $totalVentaCimpuestoUs;
		$values->encabezado->ESTATUS = 'P';
		$values->encabezado->ID = $values->encabezado->vendedor;
		$values->encabezado->ID_VENTA = $values->encabezado->vendedor;
		$values->encabezado->ID_FECHA = date("Y-m-d");
		$values->encabezado->ID_HORA = date("H:i:s");


		if (array_key_exists('pesovolumetrico', $values->encabezado)) {
			$values->encabezado->PESOVOL = $values->encabezado->pesovolumetrico; // rename "a" to "b", somehow!
			unset($values->encabezado->pesovolumetrico); // remove the original one
		}
		if (array_key_exists('costoflete', $values->encabezado)) {
			$values->encabezado->VALFLETE = $values->encabezado->costoflete; // rename "a" to "b", somehow!
			unset($values->encabezado->costoflete); // remove the original one
		}

		#var_dump($values->encabezado);die();

		$values_json = base64_encode( json_encode( $values->encabezado ) );

		$sqlLine = $this->createSqlLine('UPDATE','D_VENT',$values_json,'00000001', 'VENTA');
		$sqlLines = $this->createSqlLine('INSERT','D_VEDE',$values_json,'00000001', 'VENTA');
		
		var_dump($sqlLine);

		die();
		#$values = array();
		return $values;

	}


	// ********************************+VENTAS*************************************

	// ******************************** PRODUCTS *************************************

	#GET PRODUCTS TO WEB STORE
	public function getProductsAvailable($product = FALSE, $customer = FALSE,  $moneda = FALSE){	
		#var_dump($moneda);die();
		$conn = $this->connection();
		if (empty($product) &&  empty($moneda)){
			$sql = "SELECT PRODUCTO,EXIS0,EXIS2,EXIS3,EXIS4,EXIS5,EXIS8,ABC,MONEDA,PRECIO1,PRECIO2,PRECIO3,PRECIO4,PRECIO5,PRECIO6,PESO,ANCHO,LARGO,ALTO,MARCA,PARTNUMBER FROM D_PROD WHERE PRODUCTO NOT LIKE '%VAR%' AND MARCA NOT IN ('VAR')  AND ABC IN ('A','B','C','S','F','R') ORDER BY EXIS DESC";
			
			//$sql = "SELECT PRODUCTO,EXIS0,EXIS2,EXIS3,EXIS4,EXIS5,EXIS8,ABC,MONEDA,PRECIO1,PRECIO2,PRECIO3,PRECIO4,PRECIO5,PRECIO6,PESO,ANCHO,LARGO,ALTO,MARCA,PARTNUMBER FROM D_PROD WHERE PRODUCTO NOT LIKE '%VAR%' AND MARCA NOT IN ('VAR')  AND ABC = 'I' ORDER BY EXIS DESC";
			
 		}else if (empty($product) && $moneda != False){
 			$sql = "SELECT PRODUCTO,EXIS0,EXIS2,EXIS3,EXIS4,EXIS5,EXIS8,ABC,MONEDA,PRECIO1,PRECIO2,PRECIO3,PRECIO4,PRECIO5,PRECIO6,PESO,ANCHO,LARGO,ALTO,MARCA,PARTNUMBER FROM D_PROD WHERE PRODUCTO NOT LIKE '%VAR%' AND MARCA NOT IN ('VAR')  AND ABC IN ('A','B','C','S','F','R')  AND MONEDA='US' ORDER BY EXIS DESC";
 			#var_dump('BUSCAR ITEMS WITH MONEDA = US');die();
 		}
 		else{
 			$sql = "SELECT PRODUCTO,EXIS0,EXIS2,EXIS3,EXIS4,EXIS5,EXIS8,ABC,MONEDA,PRECIO1,PRECIO2,PRECIO3,PRECIO4,PRECIO5,PRECIO6,PESO,ANCHO,LARGO,ALTO,DESC1,DESC2,DESC3,COSTO_BASE,MARCA,PARTNUMBER FROM D_PROD WHERE PRODUCTO NOT LIKE '%VAR%' AND MARCA NOT IN ('VAR') AND PRODUCTO LIKE '%{$product}' AND ABC NOT IN ('D') ORDER BY EXIS DESC";
 		}
		
		$values = $this->executeQuery($sql, 'GET' );
		$produc_ids = json_decode($values, true);

		$conTc = $this->getTC();
		$tc = json_decode($conTc, true)['Data'][0]['TC'];
		$hoy = date("Y-m-d");  

		$i = 0 ;
		foreach ($produc_ids['Data'] as $produc_id) {
			$tcdaily  = 1;
			if ($produc_ids['Data'][$i]['MONEDA'] == 'US'){
				$tcdaily = $tc;
				$produc_ids['Data'][$i]['PRECIO1'] = $produc_ids['Data'][$i]['PRECIO1'] * $tcdaily;
				$produc_ids['Data'][$i]['PRECIO2'] = $produc_ids['Data'][$i]['PRECIO2'] * $tcdaily;
				$produc_ids['Data'][$i]['PRECIO3'] = $produc_ids['Data'][$i]['PRECIO3'] * $tcdaily;
				$produc_ids['Data'][$i]['PRECIO4'] = $produc_ids['Data'][$i]['PRECIO4'] * $tcdaily;
				$produc_ids['Data'][$i]['PRECIO5'] = $produc_ids['Data'][$i]['PRECIO5'] * $tcdaily;
				$produc_ids['Data'][$i]['PRECIO6'] = $produc_ids['Data'][$i]['PRECIO6'] * $tcdaily;
			}
			$skuCrescendo = trim($produc_id['PRODUCTO']);
			$exis = floatval($produc_id['EXIS0']) + floatval($produc_id['EXIS2']) + floatval($produc_id['EXIS3']) + floatval($produc_id['EXIS4']) + floatval($produc_id['EXIS5']);
			$produc_ids['Data'][$i]['EXIS'] = $exis;
			$produc_ids['Data'][$i]['TC'] = $tc;
			$produc_ids['Data'][$i]['COST_ORI'] = 0;
			if ($customer == False){
				#var_dump("SELECT * FROM D_COSP WHERE CLIENTE ='*' AND '{$hoy}' BETWEEN DESDE AND HASTA AND PRODUCTO = '{$skuCrescendo}'");die();
				$connCosp = $this->executeQuery("SELECT * FROM D_COSP WHERE CLIENTE ='*' AND '{$hoy}' BETWEEN DESDE AND HASTA AND PRODUCTO = '{$skuCrescendo}'", 'GET');
			}else{
				$connCosp = $this->executeQuery("SELECT * FROM D_COSP WHERE CLIENTE ='{$customer}' AND '{$hoy}' BETWEEN DESDE AND HASTA AND PRODUCTO = '{$skuCrescendo}'", 'GET');
				$jsonCosp = json_decode($connCosp, true);
				if ($jsonCosp['NoRows']<=0){
					$connCosp = $this->executeQuery("SELECT * FROM D_COSP WHERE CLIENTE ='*' AND '{$hoy}' BETWEEN DESDE AND HASTA AND PRODUCTO = '{$skuCrescendo}'", 'GET');
				}
				#var_dump("SELECT * FROM D_COSP WHERE CLIENTE ='{$customer}' AND '{$hoy}' BETWEEN DESDE AND HASTA AND PRODUCTO = '{$skuCrescendo}'");die();
			}
			$jsonCosp = json_decode($connCosp, true);
			if (count($jsonCosp['Data'])>0){
				$produc_ids['Data'][$i]['COST_ORI'] = $produc_id['PRECIO1'] * $tcdaily;
				$produc_ids['Data'][$i]['cPRECIO1'] = $jsonCosp['Data'][0]['PRECIO1'] * $tcdaily ;
				$produc_ids['Data'][$i]['cPRECIO2'] = $jsonCosp['Data'][0]['PRECIO2'] * $tcdaily ;
				$produc_ids['Data'][$i]['cPRECIO3'] = $jsonCosp['Data'][0]['PRECIO3'] * $tcdaily ;
				$produc_ids['Data'][$i]['cPRECIO4'] = $jsonCosp['Data'][0]['PRECIO4'] * $tcdaily ;
				$produc_ids['Data'][$i]['cPRECIO5'] = $jsonCosp['Data'][0]['PRECIO5'] * $tcdaily ;
				$produc_ids['Data'][$i]['cPRECIO6'] = $jsonCosp['Data'][0]['PRECIO6'] * $tcdaily ;
				$produc_ids['Data'][$i]['FINPROMO'] = $jsonCosp['Data'][0]['HASTA'];
			}
			$i+=1;

		}
		return $produc_ids;
	}

	#GET STATUS FOR SELL
	public function getProducts($marca,$fecha_inicial,$fecha_final){	
		$conn = $this->connection();
		if($marca == '' && $fecha_inicial=='' && $fecha_final==''){
			return json_encode(array('NoRows' => 0,
             		 'Status'=>False,
               		 'Data'=>array(),
               		 'Error'=>'No se ingreso ningun valor(MARCA,FECHA INICIAL/FECHA FINAL)'
               		)); 
		}
		$sql = "SELECT * FROM D_PROD WHERE PRODUCTO <> '0'";

		if($marca != ''){
			$sql = $sql." AND MARCA ='{$marca}'";
		}if($fecha_inicial!='' && $fecha_final!=''){
			$sql = $sql." AND ID_FECHA BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";			
		}
					
		$values = $this->executeQuery($sql, 'GET' );
		#var_dump($values);die();
		return $values;

	}

	// ******************************** PRODUCTS *************************************


	#GET QUERYS
	public function executeQuery($sql, $type){
		$conn = $this->connection();
		#$rs = odbc_exec($conn, $sql);
		set_error_handler(function($errno, $errstr, $errfile, $errline ) {
		    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
		 });
		try{
			$rs = odbc_exec($conn, $sql);
		}catch (Exception $e){
		 	$arr = array('NoRows' => 0,
         		 'Status'=>False,
           		 'Data'=>[],
           		 'Error'=>True,
           		 'Mesage'=>(string)$e
           		); 
    
    		return json_encode( $arr );
		}

		//CREATE JSON
		$i=0;
		$status = False;
		$error = False;
		$data = [];
		if ($type =='GET'){
			try{
				while(odbc_fetch_row($rs)){
					for ($j = 1; $j <= odbc_num_fields($rs); $j++)
		        	{       
		            	$field_name = odbc_field_name($rs, $j);

		             	$ar[$field_name] = trim(odbc_result($rs, $field_name));
		        	}
		       
		        	$data[$i] = $ar;
		        	$i++; 
		        	$status = True;     
		    	}
		    }catch(Throwable $e) {
				  $data=[];
				  $status = False;
				  $error = $e;
				}
			}

    	$arr = array('NoRows' => count($data),
             		 'Status'=>$status,
               		 'Data'=>$data,
               		 'Error'=>$error
               		); 
    
    	return json_encode( $arr );

	}

}



?>

<?php

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://kameleonagency-virtualdemand-app-crm-phonecall-plann-8365044.dev.odoo.com/api/orders/create',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{"purchase_order_number":"codexworld","warehouse":"CDMX","products":[{"sku":"SM-T220NZSAMXO","quantity":1}]}',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Bearer XrseUTQbyDH1gm_7Juhi9x90T2LDAWdJ6mPqUntY',
	    'Accept: application/json'
	  ),
	));

	$response = curl_exec($curl);
	var_dump($response);
	curl_close($curl);
	echo $response;

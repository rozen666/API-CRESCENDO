<?php    
	$year = '';
	$month = '';
	$invoiceName = '';

    if (isset($_GET['company'])){
		$company_name = $_GET['company'];
	}else{
		$company_name = 'daisytek';
	}
	if (isset($_GET['year']) && $_GET['year']){
		$year = $_GET['year'];
	}
	if (isset($_GET['month']) && $_GET['month']){
		$month = $_GET['month'];
	}
	if (isset($_GET['invoiceName']) && $_GET['invoiceName']){
		$invoiceName = $_GET['invoiceName'];
	}

	if($year == '' || $invoiceName == '' || $month ==''){

		$arr = array('NoRows' => 0,
               'Status'=>false,
               'Data'=>array('month'=>$month,'year'=>$year,'invoiceName'=>$invoiceName),
              ); 
    
  		echo json_encode( $arr );die();
	}
    #$url  = '\\10.130.8.224\GrupoDZTK_Documentos\Documentos\XML\FACTURA\2023\09\FACTURA_GCM_045228.xml';

    #SET SERVER DIR AND PATHS
    if ($company_name == 'tonivisa'){
    	$ip = '10.130.7.4';
    	$server_dir = "TONIVISA_Documentos";
    	$pathDocs = "/XML/FACTURA/";
    }else{
		$ip = '10.130.8.224';
    	if($company_name == 'daisytek'){
    		$server_dir = "GrupoDZTK_Documentos";
            $pathDocs = "/Documentos/XML/FACTURA/";
        }else{
        	$server_dir = "ImaldiMEX_Documentos";
        	$pathDocs = "/XML/FACTURA/";
        }
    	
    }

	$host = gethostbyaddr( $ip );
	if ( $ip == $host )
		die( 'Unable to resolve hostname from ip '.$ip );

	$path = '//'.$host.'/' . $server_dir . $pathDocs . $year . '/' . $month;
	
	if ( !is_dir($path) )
		die( $path. ' is not a directory' );  

	$dir = opendir($path);
	if ( $dir == FALSE )
		die( 'Cannot read '.$path );
	
	$data = False;
	while (($file = readdir($dir)) !== FALSE){
		if ($file == $invoiceName ){
			$im = file_get_contents($path."/".$file);
			$data = base64_encode($im); 			
		}
	}
		
	closedir( $dir );
	$arr = array('NoRows' => 1,
               'Status'=>True,
               'Data'=>$data
              ); 
    
  	echo json_encode( $arr );


?>
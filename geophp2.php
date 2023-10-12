<?php
	#date_default_timezone_set('America/Mexico_City');
	$x1 = $_GET['x1'];
	$x2 = $_GET['x2'];
	$y1 = $_GET['y1'];
	$y2 = $_GET['y2'];
	$command = escapeshellcmd("python geopy.py $x1 $x2 $y1 $y2" );
	$output = shell_exec($command);
	$data = array('distance'=>$output);

	$arr = array('NoRows' => 1,
               'Status'=>True,
               'Data'=>$data
              ); 
    
  	echo json_encode( $arr );
?>
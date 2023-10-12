<?php

  date_default_timezone_set('America/Mexico_City');

  function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'miles') {
    $theta = $longitude1 - $longitude2; 
    $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))); 
    $distance = acos($distance); 
    $distance = rad2deg($distance); 
    $distance = $distance * 60 * 1.1515; 
    switch($unit) { 
      case 'miles': 
        break; 
      case 'kilometers' : 
        $distance = $distance * 1.609344; 
    } 
    return (round($distance,2)); 
  }

  $latitudex =$_GET['x1'] ;
  $longitudex = $_GET['x2'];

  $latitudey = $_GET['y1'];
  $longitudey = $_GET['y2'];
  $total = getDistanceBetweenPointsNew($_GET['x1'] ,$_GET['x2'] ,$_GET['y1'],$_GET['y2'] ,'kilometers' );

  $arr = array('NoRows' => 1,
               'Status'=>True,
               'Data'=>$total,
              ); 
    
  echo json_encode( $arr );

?>
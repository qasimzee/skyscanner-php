<?php 

include_once 'SkyscannerFlights.php';
include_once 'SkyscannerCars.php';

define ('API_KEY', 'tr059372395409930573607329916761');

function printFlights() {

  $params = array(
    'market' => 'US',
    'currency' => 'PKR', 
    'locale' => 'en-US',
    'pickupplace' => 'JFK-sky',
    'dropoffplace' => 'IAD-sky',
    'pickupdatetime' => '2015-10-10',
    'dropoffdatetime' => '2015-10-10',
    'driverage' => '25',
    'adults' => '1',
  );
  $sc_flights = new SkyscannerCars(API_KEY); 
  
  $session_response = $sc_flights->createSession($params);
  print_r($session_response);
}

printFlights();

?>

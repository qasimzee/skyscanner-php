<?php 

include_once 'SkyscannerFlights.php';
include_once 'SkyscannerCars.php';

//define ('API_KEY', 'tr059372395409930573607329916761');
//define ('API_KEY', 'tr466745184696522911149195491151');
define ('API_KEY', 'prtl6749387986743898559646983194');

function printCars() {

  $params = array(
    'market' => 'US',
    'currency' => 'PKR', 
    'locale' => 'en-US',
    'pickupplace' => 'JFK-sky',
    'dropoffplace' => 'IAD-sky',
    'pickupdatetime' => '2015-12-11T00:00',
    'dropoffdatetime' => '2015-12-15T00:00',
    'driverage' => '26',
    'adults' => '1',
  );
  $sc_flights = new SkyscannerCars(API_KEY); 

  $session_response = $sc_flights->createSession($params);
  if (isset($session_response['location'])) {
    print_r($sc_flights->pollSession('http://partners.api.skyscanner.net' . $session_response['location'])); 
  }
  
}

function printAutoSuggest($query) {

  $params = array(
    'market' => 'US',
    'currency' => 'PKR', 
    'locale' => 'en-US',
    'query' => $query, 
  );
  $sc_cars = new SkyscannerCars(API_KEY); 
  print_r($sc_cars->autoSuggest($params));
}
//printCars();
printAutoSuggest('pari');

?>

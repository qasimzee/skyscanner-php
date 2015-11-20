
<?php
/*!
 * SkyscannerFlights class
 * 
 * It implements all the flights related functionality
 *  
 */

include_once 'SkyscannerClient.php';

define('SKYSCANNER_CARS_SESSION_PATH', 'http://partners.api.skyscanner.net/apiservices/carhire/liveprices/v2');
define('SKYSCANNER_LOCALES_PATH', 'reference/v1.0/locales');

define('SKYSCANNER_CARS_HTTP_SUCCESS_CODE', 200);
// A service client for Skyscanner API.

class SkyscannerCars {
  var $api_key;
  var $client;
  public $locale = 'en-US';
  function __construct($api_key) {
    $this->client = new SkyscannerClient($api_key);
  } 

  /**
   * Get itineraries according to the given criteria under $params
   */
  function getItineraries($params) {
    $session = $this->createSession($params);
    if ($this->client->error_code != SKYSCANNER_HTTP_SUCCESS_CODE) {
      throw new Exception($session);
    }
    sleep(1);
    $itineraries = $this->pollSession($session['location']);
    return $itineraries;
  }

  /**
   * POST http://partners.api.skyscanner.net/apiservices/carhire/liveprices/v2/{market}/{currency}/{locale}/{pickupplace}/{dropoffplace}/{pickupdatetime}/{dropoffdatetime}/{driverage}?apiKey={apiKey}&userip={userip}
   */
  function createSession($params) {
    $path = SKYSCANNER_CARS_SESSION_PATH;
    $path .= '/' . $params['market'];
    $path .= '/' . $params['currency'];
    $path .= '/' . (isset($params['locale']) ? $params['locale'] : $locale);
    $path .= '/' . $params['pickupplace'];
    $path .= '/' . $params['dropoffplace'];
    $path .= '/' . $params['pickupdatetime'];
    $path .= '/' . $params['dropoffdatetime'];
    $path .= '/' . $params['driverage'];
    $response = $this->client->api($path, array('userip' => urlencode($_SERVER['REMOTE_ADDR'])));
    //$response = $this->client->api($path);
    print_r($this->client);
    if ($this->client->error_code != SKYSCANNER_CARS_HTTP_SUCCESS_CODE) {
      return $this->client->response_body;
    } 
    return $this->client->response_header;
  }

  function pollSession($polling_url) {
    $this->client->get($polling_url);
  }

  function getLocales() {
    return $this->client->api(SKYSCANNER_LOCALES_PATH, 'GET');
  }
}

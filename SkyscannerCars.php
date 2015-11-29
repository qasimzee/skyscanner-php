
<?php
/*!
 * SkyscannerFlights class
 * 
 * It implements all the flights related functionality
 *  
 */

include_once 'SkyscannerClient.php';

define('SKYSCANNER_CARS_SESSION_PATH', 'http://partners.api.skyscanner.net/apiservices/carhire/liveprices/v2');
define('SKYSCANNER_CARS_AUTO_SUGGEST_PATH', 'http://partners.api.skyscanner.net/apiservices/carhire/autosuggest/v2');
define('SKYSCANNER_LOCALES_PATH', 'reference/v1.0/locales');

define('SKYSCANNER_CARS_HTTP_SUCCESS_CODE', 302);
// A service client for Skyscanner API.

class SkyscannerCars {
  var $api_key;
  var $client;
  public $locale = 'en-US';
  function __construct($api_key) {
    $this->client = new SkyscannerClient($api_key);
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
    $response = $this->client->api($path, 'GET', array('userip' => '127.0.0.1')); 
    if ($this->client->error_code != SKYSCANNER_CARS_HTTP_SUCCESS_CODE) {
      return $this->client->response_body;
    } 
    return $this->client->response_header;
  }

  function autoSuggest($params) {
    $path = SKYSCANNER_CARS_AUTO_SUGGEST_PATH; 
    $path .= '/' . $params['market'];
    $path .= '/' . $params['currency'];
    $path .= '/' . (isset($params['locale']) ? $params['locale'] : $locale);
    $path .= '/' . rawurlencode($params['query']);
    $response = $this->client->api($path, 'GET');
    if ($this->client->error_code != 200) {
      return $this->client->response_header;
    } 
    return $response; 
  }
  function pollSession($polling_url) { 
    return $this->client->api($polling_url, 'GET');
  }

  function getLocales() {
    return $this->client->api(SKYSCANNER_LOCALES_PATH, 'GET');
  }
}

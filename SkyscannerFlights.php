
<?php
/*!
 * SkyscannerFlights class
 * 
 * It implements all the flights related functionality
 *  
 */

include_once 'SkyscannerClient.php';

define('SKYSCANNER_SESSION_PATH', 'pricing/v1.0');
define('SKYSCANNER_LOCALES_PATH', 'reference/v1.0/locales');

define('SKYSCANNER_HTTP_SUCCESS_CODE', 201);
// A service client for Skyscanner API.

class SkyscannerFlights {
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
    //After creating the session please allow at least one second 
    //before polling the session. A response will be available immediately,
    //but time must be given to allow the price updates to take place.
    sleep(1);
    $itineraries = $this->pollSession($session['location']);
    return $itineraries;
  }

  /**
   * POST http://partners.api.skyscanner.net/apiservices/pricing/v1.0
   */
  function createSession($params) {
    $params['locale'] = $this->locale;
    $response = $this->client->post(SKYSCANNER_SESSION_PATH, $params);
    if ($this->client->error_code != SKYSCANNER_HTTP_SUCCESS_CODE) {
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

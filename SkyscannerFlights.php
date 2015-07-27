
<?php
/*!
 * SkyscannerFlights class
 * 
 * It implements all the flights related functionality
 *  
 */

include_once 'SkyscannerClient.php';

define(SKYSCANNER_SESSION_PATH, 'pricing/v1.0');
define(SKYSCANNER_LOCALES_PATH, 'reference/v1.0/locales');
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
    $itineraries = $this->pollSession();
    return $itineraries;
  }

  /**
   * POST http://partners.api.skyscanner.net/apiservices/pricing/v1.0
   */
  function createSession($params) {
    $params['locale'] = $this->locale;
    $this->client->api(SKYSCANNER_SESSION_PATH, 'POST', $params);
    return $this->client->response_header;
  }

  function pollSession() {
    
  }

  function getLocales() {
    return $this->client->api(SKYSCANNER_LOCALES_PATH, 'GET');
  }
}

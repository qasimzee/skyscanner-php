<?php
/*!
 * 
 *  
 */

// A service client for Skyscanner API.

class SkyscannerClient {
  public $api_base_url     = "http://partners.api.skyscanner.net/apiservices/";

  public $api_key                  = '';
  public $decode_json              = true;
  public $curl_time_out            = 30;
  public $curl_connect_time_out    = 30;
  public $curl_ssl_verifypeer      = false;
  public $curl_header              = array();
  public $curl_useragent           = "SkyscannerClient PHP Client v0.1; SkyscannerPHP"; 
  public $curl_authenticate_method = "GET";
  public $curl_proxy               = null;
  public $http_code                = '';
  public $http_info                = ''; 
  public $response_header          = '';
  public $response_body            = '';
  public $error_code               = '';
  public function __construct($api_key = false) {
    $this->api_key = $api_key; 
  }

  /** 
   * Simple Skyscanner API call
   */
  public function api($url, $method = "GET", $parameters = array()) {
    if ( strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0 ) {
      $url = $this->api_base_url . $url;
    }
    if (strpos($url, 'apikey') === FALSE) { 
      $parameters['apiKey'] = $this->api_key; 
    }
    
    $response = null;
    
    switch( $method ) {
    case 'GET':  
      $response = $this->request($url, $parameters, "GET");  
      break; 
    case 'POST': 
      $response = $this->request($url, $parameters, "POST"); 
      break;
    }

    if( $this->response_body && $this->decode_json ) {
      $response = json_decode( $this->response_body ); 
    }
    return $response; 
  }

  /** 
   * GET wrappwer for provider apis request
   */
  function get($url, $parameters = array()) {
    return $this->api($url, 'GET', $parameters);
  } 

  /** 
   * POST wreapper for provider apis request
   */
  function post($url, $parameters = array()) {
    return $this->api($url, 'POST', $parameters);
  }

  private function request($url, $params=null, $type="GET") {
    
    if ($type == "GET" && count($params)) {
      $url = $url . (strpos( $url, '?' ) ? '&' : '?') . http_build_query($params);
    }
    echo $url; 
    $this->http_info = array();
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL            , $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
    curl_setopt($ch, CURLOPT_TIMEOUT        , $this->curl_time_out);
    curl_setopt($ch, CURLOPT_USERAGENT      , $this->curl_useragent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $this->curl_connect_time_out); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , $this->curl_ssl_verifypeer);
    curl_setopt($ch, CURLOPT_HTTPHEADER     , $this->curl_header);

    curl_setopt($ch, CURLOPT_HEADER         , 1);
    if($this->curl_proxy) {
      curl_setopt( $ch, CURLOPT_PROXY       , $this->curl_proxy);
    }

    if( $type == "POST" ) {
      curl_setopt($ch, CURLOPT_POST, 1); 
      if($params) curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
    }

    $response = curl_exec($ch);

    $this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ch));
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $this->response_header = self::parseHeaders(substr($response, 0, $header_size)); 
    $this->response_body = substr($response, $header_size);
    $this->error_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $response; 
  }
  static function parseHeaders($header) {
    $response_header = explode(PHP_EOL, $header);
    $arr_headers = array();

    foreach($response_header as $line) {
      if (strpos($line, ':') !== FALSE) {
        list($key, $val) = explode(':', $line, 2);
        $arr_headers[strtolower($key)] = trim($val);  
      }      
    }

    return $arr_headers;
  }
}

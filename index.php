<?php

// redirections if urls aren't well-formed for our purposes -------------------------------------------------
// redirect & strip if path has a trailing slash
if( substr( $_SERVER["REQUEST_URI"], -1 ) == "/" ) {
  header( "Location: " . substr( $_SERVER["REQUEST_URI"], 0, strlen( $_SERVER["REQUEST_URI"] ) - 1 ) );
} 
// redirect & strip if path has a www
if( substr( $_SERVER["REQUEST_URI"], 0, 9 ) == "http://www" ) {
  header( "Location: " . str_replace( "http://www.", "http://", $_SERVER["REQUEST_URI"] )  );
} 

// utils ----------------------------------------------------------------------------------------------------
class StringUtils {
  function __construct() {}
  
  public function protectYaText( $text ) {
    // security against naughty foreign or local includes. limit to alphanumeric + '/'?
    $text = str_replace( "http", " ", $text );
    $text = str_replace( "../", " ", $text );
    $text = preg_replace("/[^a-zA-Z0-9-+.\/\s]/", "", $text );
    return $text;
  }

  public function makeFriendlyText( $text ) {
    $text = strtolower( $text );
    // $text = substr( $text, 0, FRIENDLY_TITLE_CUTOFF ); // shorten it?
    $text = $this->stripAllExceptAlphanumeric( $text );
    $text = str_replace( " ", '-', $text );
    while( strpos( $text, '--' ) !== false ) { $text = str_replace( "--", '-', $text ); } // make sure dashes in title don't mess up our pretty url
    return $text;
  }

  public function stripAllExceptAlphanumeric( $str ) {
    return preg_replace("/[^a-zA-Z0-9-+\s]/", "", $str);
  }

}

// main app request/response objects -----------------------------------------------------------------------------------
class RequestProperties {
  function __construct() {
    $this->_query = '';
    $this->_isAjax = false;
    
    $this->getPath();
    $this->setOutputType();
  }
  
  function path() { return $this->_query; }
  function isAjax() { return $this->_isAjax; }

  function getPath() {
    global $string_utils;
    // get page/mode and set to empty string if none
    $serverPath = explode( "?", $_SERVER["REQUEST_URI"] );
    $this->_serverPath = $string_utils->protectYaText( $serverPath[0] );
    $this->_query = $this->_serverPath;// protectYaText( $_SERVER['QUERY_STRING'] ); //substr(, 1); // $_REQUEST['path'];
  }

  function setOutputType() {
    // check whether it's an ajax request
    if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
      $this->_isAjax = true;
    }
  }
}

class Response {
  function __construct( $request ) {
    $this->request = $request;
    $this->initEnvironment();
    $this->compressHtmlOutput();
    $this->renderPageRequest();
  }
  
  function initEnvironment() {
    date_default_timezone_set('America/Denver');
  }
    
  function compressHtmlOutput() {
    // compress this bitch
    if( !@ini_set('zlib.output_compression',TRUE) && !@ini_set('zlib.output_compression_level',2) ) {
      ob_start('ob_gzhandler');
    }
    header ("content-type: text/html; charset: UTF-8");
    header ("cache-control: must-revalidate");
    $offset = 1 * -1;
    $expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
    header ($expire);
  }

  function renderPageRequest() {
    // determine if output is an ajax snippet or fully-rendered page
    if( $this->request->isAjax() == true ) {
      include './php/response/data.php';
    } else {
      include './php/response/html.php';
    }
  }
  
  function __destruct() {
    $this->request = null;
  }
}

// init ---------------------------------------------------------------------------------------------------
$string_utils = new StringUtils();
$request_props = new RequestProperties();
$website = new Response( $request_props );

?>
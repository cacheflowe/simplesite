<?php

class Request {
  function __construct($routes) {
    $this->routes = $routes;
    $this->_query = '';
    $this->_isAjax = false;
    $this->isDev = false;

    $this->getPath();
    $this->setOutputType();
  }

  function routes() { return $this->routes; }
  function server() { return $this->_serverPath; }
  function path() { return $this->_query; }
  function pathComponents() { return $this->_pathComponents; }
  function isAjax() { return $this->_isAjax; }
  function isDev() { return $this->isDev; }

  function getPath() {
    global $string_utils;
    // get page/mode and set to empty string if none
    $serverPath = explode( "?", $_SERVER["REQUEST_URI"] );
    $this->_serverPath = $string_utils->protectYaText( $serverPath[0] );
    $this->_query = $this->_serverPath; // protectYaText( $_SERVER['QUERY_STRING'] ); //substr(, 1); // $_REQUEST['path'];
    if($this->_query == '' || $this->_query == '/') $this->_query = '/home';
    $this->_pathComponents = explode( '/', substr( $this->_query, 1 ) );	// strip first slash and get array of path components

    if(strpos($_SERVER["SERVER_NAME"], "localhost") === 0) $this->isDev = true;
    if(isset( $_REQUEST['notDev'] )) $this->isDev = false;
  }

  function setOutputType() {
    // check whether it's an ajax request
    // if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
      $this->_isAjax = true;
    }
  }
}

?>

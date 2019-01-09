<?php

class Request {
  function __construct($routes) {
    $this->routes = $routes;
    $this->_path = '';
    $this->_postBody = '';
    $this->_isAjax = false;
    $this->isDev = false;
    $this->_isAPI = false;

    $this->getPath();
    $this->setOutputType();
  }

  function routes() { return $this->routes; }
  function host() { return $this->_host; }
  function path() { return $this->_path; }
  function pathComponents() { return $this->_pathComponents; }
  function postBody() { return $this->_postBody; }
  function isAjax() { return $this->_isAjax; }
  function isAPI() { return $this->_isAPI; }
  function isDev() { return $this->isDev; }

  function setAPI($isAPI) {
    $this->_isAPI = $isAPI;
    if($isAPI == true) $this->_isAjax = true;
  }

  function getPath() {
    global $string_utils;
    // get page/mode and set to empty string if none
    $this->_host = "http://$_SERVER[HTTP_HOST]"; // "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
    $pathWithoutQuery = explode( "?", $_SERVER["REQUEST_URI"] );
    $this->_path = $string_utils->protectYaText( $pathWithoutQuery[0] ); // protectYaText( $_SERVER['QUERY_STRING'] ); //substr(, 1); // $_REQUEST['path'];
    if($this->_path == '' || $this->_path == '/') $this->_path = '/home';
    $this->_pathComponents = explode( '/', substr( $this->_path, 1 ) );	// strip first slash and get array of path components
    $this->_postBody = file_get_contents('php://input');

    if(strpos($_SERVER["SERVER_NAME"], "localhost") === 0) $this->isDev = true;
    if(isset( $_REQUEST['notDev'] )) $this->isDev = false;
    if(isset( $_REQUEST['isDev'] )) $this->isDev = true;
  }

  function setOutputType() {
    // check whether it's an ajax request
    // if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
      $this->_isAjax = true;
    }
    if(isset( $_REQUEST['ajax'] )) {
      $this->_isAjax = true;
    }
    if(isset( $_REQUEST['api'] )) {
      $this->_isAPI = true;
    }
  }
}

?>

<?php
// don't return html fragment
global $request;
$request->setAPI(true);

$method = $_SERVER['REQUEST_METHOD'];
if ($_GET && $_GET['url']) {
  $headers = getallheaders();
  $headers_str = [];
  $url = $_GET['url'];

  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');

  echo file_get_contents($url);
}

?>

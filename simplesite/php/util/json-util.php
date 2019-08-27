<?php

class JsonUtil {

    public static function isValidJSON($str) {
      if(strlen($str) == 0) return false;
      json_decode($str);
      return json_last_error() == JSON_ERROR_NONE;
    }

    public static function getJsonFromFile($path) {
      $jsonStr = file_get_contents($path);
      return json_decode($jsonStr, true);
    }

    public static function writeJsonToFile($path, $data) {
      file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
    }

    public static function jsonDataObjToString($data) {
      return json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

    public static function setJsonOutput() {
      // don't return html fragment (requires Simplesite)
      global $request;
      $request->setAPI(true);

      // set headers to return json data type
      header('Content-Type: application/json');
      header('Access-Control-Allow-Origin: *');
    }
}

?>

<?php
include('./php/app/app.php');

// set response type as json
JsonUtil::setJsonOutput();

// get data from form submit
$formSubmitJson = $request->postedJson();

// get existing data to manipulate
$configJsonPath = $constants["jsonPath"] . $constants["configJson"];
$configJson = JsonUtil::getJsonFromFile($configJsonPath);

// validate form data
if(isset($formSubmitJson['config'])) {
  $config = $formSubmitJson['config'];

  // update config property with form data
  $configJson['config'] = $config;

  // save the data file back to disk
  JsonUtil::writeJsonToFile($configJsonPath, $configJson);

  // output success
  echo "{\"success\": \"Updated config data\"}";
} else {
  echo "{\"fail\": \"Invalid JSON posted\"}";
}

?>

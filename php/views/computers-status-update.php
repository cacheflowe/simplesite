<?php
include('./php/app/app.php');
Login::setAuthRequired(false);

// get data from form submit & set response type as json
$formSubmitJson = $request->postedJson();
JsonUtil::setJsonOutput();

// get dashboard data
$dataPath = "data/json/computers.json";
$computersDB = JsonUtil::getJsonFromFile($dataPath);

// validate form data
if(isset($formSubmitJson['ipAddress'])) {
  // find ipAddress and set status
  $ipAddress = $formSubmitJson['ipAddress'];
  $onStatus = $formSubmitJson['onStatus'];
  $computerConfigs = &$computersDB["slides"];
  foreach ($computerConfigs as &$config) {
    if($config['ipAddress'] == $ipAddress) {
      $config['onStatus'] = $onStatus;
    }
  }

  // store back to disk
  if($computerConfigs != null) {
    JsonUtil::writeJsonToFile($dataPath, $computersDB);
    JsonUtil::printSuccessMessage("Computer status updated: $onStatus");
  } else {
    JsonUtil::printFailMessage("Error: computerConfigs is null");
  }
} else {
  // output fail
  JsonUtil::printFailMessage("No valid checkin JSON posted");
}

?>

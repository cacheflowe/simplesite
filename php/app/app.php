<?php

// includes/globals
include './php/app/data.php';
include './php/app/templates.php';
include './php/app/json-edit.php';
include './php/app/dashboard.php';
global $request;
global $constants;

if(isset($constants["password"])) {
  Login::setAuthRequired();
}


// Below is legacy for schedule CMS forms
// schedule data handling
function getScheduleJsonData() {
  global $constants;
  $scheduleJsonPath = $constants["jsonPath"] . $constants["scheduleJson"];
  return JsonUtil::getJsonFromFile($scheduleJsonPath);
}

function writeScheduleJsonData($data) {
  global $constants;
  $scheduleJsonPath = $constants["jsonPath"] . $constants["scheduleJson"];
  JsonUtil::writeJsonToFile($scheduleJsonPath, $data);
  // also backup!
  /*
  $timeStamp = DateUtil::createTimestamp();
  $backupDir = $constants["jsonPath"] . "schedule-bak/";
  FileUtil::makeDirs($backupDir);
  $backupFileName = str_replace(".json", "-" . $timeStamp . ".json", $constants["scheduleJson"]);
  $scheduleBackupPath = $backupDir . $backupFileName;
  JsonUtil::writeJsonToFile($scheduleBackupPath, $data);
  */
}

?>

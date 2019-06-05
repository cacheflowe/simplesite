<?php
///////////////////////////////
// Config
///////////////////////////////
include('./php/app/app.php');

// api json response
JsonUtil::setJsonOutput();

// save data from post
$formSubmitJson = json_decode($request->postBody(), true);
if(isset($formSubmitJson['imagekey'])) {
  $imageToActivate = $formSubmitJson['imagepath'];
  $imageKey = $formSubmitJson['imagekey'];

  // get schedule json to to save activated image
  $scheduleData = getScheduleJsonData();
  $scheduleData[$imageKey] = $imageToActivate;
  writeScheduleJsonData($scheduleData);

  echo "{\"success\": \"Activated image: $imageToActivate\"}";
} else {
  echo "{\"fail\": \"Activate image failed\"}";
}
?>

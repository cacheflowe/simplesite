<?php
include('./php/app/app.php');

// api json response
JsonUtil::setJsonOutput();

// save data from post
$formSubmitJson = json_decode($request->postBody(), true);
// validate form data
if(isset($formSubmitJson['dates'])) {
  $dates = $formSubmitJson['dates'];

  // set dates property but leave other data intact
  $scheduleData = getScheduleJsonData();
  $scheduleData[$scheduleSaveKey] = $dates;

  // save the data file back to disk
  writeScheduleJsonData($scheduleData);

  // output success
  echo "{\"success\": \"Updated Schedule\"}";
} else {
  echo "{\"fail\": \"Invalid JSON posted\"}";
}

?>

<?php
///////////////////////////////
// Config
///////////////////////////////
include('./php/app/app.php');

// api json response
JsonUtil::setJsonOutput();

// save data from post
$formSubmitJson = json_decode($request->postBody(), true);
if(isset($formSubmitJson['imagepath'])) {
  $imageToDelete = $formSubmitJson['imagepath'];

  if(explode("/", $imageToDelete)[0] == "uploads") {
    // check to see that the image file exists in the path that holds images
    FileUtil::deleteFile($imageToDelete);

    // if deleted upload was set as the takeover or mentor value in schedule.json, deactivate it there
    // load schedule json
    $scheduleData = getScheduleJsonData();

    if($scheduleData['takeovers'] == $imageToDelete) {
      $scheduleData['takeovers'] = null;
      writeScheduleJsonData($scheduleData);
    }
    if($scheduleData['mentors'] == $imageToDelete) {
      $scheduleData['mentors'] = null;
      writeScheduleJsonData($scheduleData);
    }

    echo "{\"success\": \"Deleted image: $imageToDelete\"}";
  } else {
    echo "{\"fail\": \"Invalid delete request\"}";
  }
} else {
  echo "{\"fail\": \"No image specified to delete\"}";
}
?>

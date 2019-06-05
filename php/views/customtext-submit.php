<?php
///////////////////////////////
// Config
///////////////////////////////
include('./php/app/app.php');

// get form json data
$formSubmitJson = json_decode($request->postBody(), true);
$customtext = $formSubmitJson['customtext'];
$customtextLine2 = $formSubmitJson['customtext-line-2'];

// overwrite json field on disk
$scheduleData = getScheduleJsonData();
$scheduleData["customtext"] = $customtext;
$scheduleData["customtext-line-2"] = $customtextLine2;

// save the data file back to disk
writeScheduleJsonData($scheduleData);

// output success
echo "{\"success\": \"Updated customtext: $customtext\"}";

?>

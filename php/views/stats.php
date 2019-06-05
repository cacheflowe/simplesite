<?php
///////////////////////////////
// Config
///////////////////////////////
include('./php/app/app.php');

// get json to build form & to save form
$countJsonPath = $constants["jsonPath"] . $constants["countJson"];
$countData = JsonUtil::getJsonFromFile($countJsonPath);
$curCount = $countData["count"];

// get today's timestamp for day tracking
$daysKey = 'days';
$dateStamp = DateUtil::getCurrentYearMonthDay();

// get daily history array
$daysArray = $countData[$daysKey];
$dayArrayKeys = array_keys($daysArray);

// sort
natsort($dayArrayKeys); // sort.
$dayArrayKeys = array_reverse($dayArrayKeys, true);


?>
<div data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Daily Recycle Count</h1>
  <div>
    <?php
      foreach ($dayArrayKeys as $dayKey) {
        if(strlen($dayKey) > 0) {
          print('<div><u>' . $dayKey . '</u> &dash; <string>' . $daysArray[$dayKey] . '</strong></div>');
        }
      }
    ?>
  </div>
</div>

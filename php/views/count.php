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

if (count($request->pathComponents()) > 1 && $request->pathComponents()[1] == 'add') {
  // update json total count
  $addAmount = intval($request->pathComponents()[2]);
  $countData["count"] = $curCount + $addAmount;

  // add daily count
  // if(isset($countData[$daysKey]) == false) $countData[$daysKey] = (object); // create object if it's not there
  if(isset($countData[$daysKey][$dateStamp])) {
    $countData[$daysKey][$dateStamp] += $addAmount;
  } else {
    $countData[$daysKey][$dateStamp] = $addAmount;
  }

  // save the data file back to disk
  JsonUtil::writeJsonToFile($countJsonPath, $countData);

  // also backup data!
  /*
  $timeStamp = DateUtil::createTimestamp();
  $backupDir = $constants["jsonPath"] . "count-bak/";
  FileUtil::makeDirs($backupDir);
  $backupFileName = str_replace(".json", "-" . $timeStamp . ".json", $constants["countJson"]);
  $countBackupPath = $backupDir . $backupFileName;
  JsonUtil::writeJsonToFile($countBackupPath, $countData);
  */

  // output success
  JsonUtil::setJsonOutput();
  echo JsonUtil::jsonDataObjToString($countData);
} else {

?>
<div data-view-type="CountView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Count</h1>
  <div id="counter">
    <h3><span id="count"><?php print($curCount); ?></span></h3>
  </div>
  <div id="count-btns" class="grid-container quarters">
    <button class="btn-count" data-count-add="-10">- 10</button>
    <button class="btn-count" data-count-add="-1">- 1</button>
    <button class="btn-count button-update" data-count-add="1">+ 1</button>
    <button class="btn-count button-update" data-count-add="10">+ 10</button>
  </div>
</div>
<?php
}
?>

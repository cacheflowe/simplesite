<?php
///////////////////////////////
// Config
///////////////////////////////
include('./php/app/app.php');

// get json to build form & to save form
$scheduleData = getScheduleJsonData();
$scheduleCustomText = isset($scheduleData["customtext"]) ? $scheduleData["customtext"] : null;
$scheduleCustomTextLine2 = isset($scheduleData["customtext-line-2"]) ? $scheduleData["customtext-line-2"] : null;
$formVal = ($scheduleCustomText != null && strlen($scheduleCustomText) > 0) ? $scheduleCustomText : "";
$formVal2 = ($scheduleCustomTextLine2 != null && strlen($scheduleCustomTextLine2) > 0) ? $scheduleCustomTextLine2 : "";

?>
<div data-view-type="CustomText" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Custom Text</h1>
  <form id="customtext-form" action="/customtext/submit" target="_blank" method="POST">
    <button type="submit" data-customtext-submit="true" style="display:none;">Dummy button for ENTER press</button>
    <div class="row">
      <input class="six columns" type="text" data-type="customtext" name="customtext" id="customtext" placeholder="Custom Text Line 1" value='<?php echo $formVal; ?>'>
      <input class="six columns" type="text" data-type="customtext" name="customtext-line-2" id="customtext-line-2" placeholder="Line 2 (optional)" value='<?php echo $formVal2; ?>'>
    </div>
    <div class="row">
      <button type="submit" class="button-primary six columns" data-customtext-submit="true" data-customtext-clear="true">Clear</button>
      <button type="submit" class="button-primary six columns" data-customtext-submit="true">Update</button>
    </div>
  </form>
</div>

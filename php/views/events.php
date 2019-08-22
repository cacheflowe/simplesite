<?php

include('./php/app/app.php');

$scheduleData = getScheduleJsonData();
$events = $scheduleData["events"];

?>

<div data-view-type="ScheduleView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Events</h1>
  <div class="row column-headers">
    <div class="two columns">Month</div>
    <div class="two columns">Day</div>
    <div class="two columns">Start</div>
    <div class="two columns">End</div>
    <div class="two columns">Title</div>
    <div class="two columns">Delete</div>
  </div>
  <form id="events-form" action="/events/submit" target="_blank" method="POST">
    <button type="submit" style="display:none;">Dummy button for ENTER press</button>
    <?php
    $index = 0;
    $timestamp = time();
    if(count($events) > 0) {
      foreach ($events as $dateItem) {
        $formId = $index . "-" . $timestamp;
        $timeStart = (isset($dateItem["timeStart"])) ? $dateItem["timeStart"] : -1;
        $timeEnd = (isset($dateItem["timeEnd"])) ? $dateItem["timeEnd"] : -1;
      ?>
        <div class="row">
          <?php print( Templates::monthSelect($formId, $dateItem["month"]) ); ?>
          <?php print( Templates::daySelect($formId, $dateItem["day"]) ); ?>
          <?php print( Templates::timeSelect($formId, $timeStart, "timeStart") ); ?>
          <?php print( Templates::timeSelect($formId, $timeEnd, "timeEnd") ); ?>
          <input class="two columns" type="text" data-type="title" name="title-<?php echo($formId); ?>-" id="title-<?php echo($formId); ?>" placeholder="Event Title" value='<?php echo $dateItem["title"]; ?>'>
          <button class="two columns btn-delete button-primary" type="submit" data-action="delete">Delete</button>
        </div>
      <?php
        $index++;
      }
      print('<button type="submit" class="button-primary">Update</button>');
    } else {
      print("<p>No events stored.</p>");
    }
    $formId = 999 . "-" . $timestamp;; // for add event row
    ?>
    <h1>Add event</h1>
    <div class="row">
      <?php print( Templates::monthSelect($formId, date("m")) ); ?>
      <?php print( Templates::daySelect($formId, date("d")) ); ?>
      <?php print( Templates::timeSelect($formId, 11, "timeStart") ); ?>
      <?php print( Templates::timeSelect($formId, -1, "timeEnd") ); ?>
      <input class="two columns" type="text" data-type="title" name="title-<?php echo($formId); ?>" id="title-<?php echo($formId); ?>" placeholder="New Event Title" value=''>
      <button class="two columns btn-add button-primary" type="submit">Add</button>
    </div>
  </form>
</div>

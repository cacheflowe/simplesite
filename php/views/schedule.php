<?php

include('./php/app/app.php');
$scheduleData = getScheduleJsonData();
$scheduleDates = $scheduleData["schedule"];

?>

<div data-view-type="ScheduleView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Schedule</h1>
  <form id="schedule-form" action="/schedule/submit" target="_blank" method="POST">
    <?php
    $index = 0;
    foreach ($scheduleDates as $dateItem) {
    ?>
      <div class="row">
        <?php echo Templates::monthSelect($index, $dateItem["month"]); ?>
        <?php echo Templates::daySelect($index, $dateItem["day"]); ?>
        <input class="eight columns" type="text" data-type="title" name="title-<?php echo($index); ?>" id="title-<?php echo($index); ?>" placeholder="Event Title" value='<?php echo $dateItem["title"]; ?>'>
      </div>
    <?php
      $index++;
    }
    ?>
    <button type="submit" class="btn-update button-primary">Update</button>
  </form>
</div>

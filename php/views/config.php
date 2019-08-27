<?php

include('./php/app/app.php');

// get json data from disk
$configJsonPath = $constants["jsonPath"] . $constants["configJson"];
$configJData = JsonUtil::getJsonFromFile($configJsonPath);
$configConfig = $configJData["config"];

?>

<div data-view-type="ConfigView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Config</h1>
  <form id="science-chat-form" action="/config/submit" target="_blank" method="POST">
    <!-- Science Chat global config -->
    <div id="form-config">
      <?php
        foreach ($configConfig as $key => $value) {
          print( Templates::configFormElement($key, $value) );
        }
      ?>
    </div>
    <h2>Actions</h2>
    <div class="grid-container quarters">
      <!-- <button type="submit" class="button-primary" data-customtext-submit="true" data-customtext-clear="true">Clear</button> -->
      <button type="submit" class="button-primary" data-form-submit="true">Save</button>
    </div>
  </form>
</div>

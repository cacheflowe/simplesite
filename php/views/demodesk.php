<?php
include('./php/app/app.php');
?>
<div data-view-type="FancyView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Demo Desk Config</h1>
  <p class="grid-container quarters">
    <a class="button button-primary" href="/demodesk/uploads/1">Uploads</a>
    <a class="button button-primary" href="/demodesk/edit/1">Edit</a>
  </p>
</div>

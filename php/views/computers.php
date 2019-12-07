<?php
include('./php/app/app.php');
?>
<div data-view-type="ComputersStatusView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Computers Manager</h1>
  <p class="hardware-info alert-message">
    <span>You don't appear to be in the Science Pyramid, so the buttons below will not work. If you <i>are</i> on the SciPy WiFi, make sure to <a href="https://www.attachmate.com/documentation/gateway-1-1/gateway-admin-guide/data/fxg_add_untrusted_cert.htm" target="_blank">allow the certificate</a> for the local server by visiting it.</span>
    <span>You are in the Science Pyramid. You can directly toggle Computers below.</span>
  </p>
  <h2>Status</h2>
  <div id="hardware-status-table">Loading Computers status &amp; action buttons here...</div>
  <h2>Configure Computers</h2>
  <p class="grid-container quarters">
    <a class="button button-primary" href="/computers/edit">Edit</a>
    <a class="button button-primary" href="/data/json/computers.json" target="_blank">JSON</a>
  </p>
</div>

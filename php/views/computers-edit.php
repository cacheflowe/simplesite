<?php
include('./php/app/app.php');

// we need to load PDUs data before form data so we can build the PDU dropdown to link to a computer
$assetsListPath = "/data/json/pdus.json";

// include common app data & config editor backend
new JsonEdit("Computers", "ComputersFormView", "./data/json/computers.json", JsonEdit::MODE_DEFAULT, null, null, 0, $assetsListPath);
?>

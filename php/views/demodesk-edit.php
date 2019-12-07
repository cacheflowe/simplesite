<?php
include('./php/app/app.php');

// set Demo Desk-specific backend properties
$dataPath = "data/json/demodesk.json";
$assetsListPath = "/demodesk/uploads/json";

// include common app data & config editor backend
new JsonEdit("Demo Desk", "DemoDeskFormView", $dataPath, JsonEdit::MODE_APP, null, null, 0, $assetsListPath, null);
?>

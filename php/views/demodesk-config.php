<?php
include('./php/app/app.php');

// config form
$dataPath = "data/json/demodesk-config.json";
$dataPublishPath = "apps/_data/demodesk-config.json";

// include common app data & config editor backend
new JsonEdit("Demo Desk Config", "ConfigFormView", $dataPath, JsonEdit::MODE_DEFAULT, $dataPublishPath);

?>

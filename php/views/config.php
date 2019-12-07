<?php
include('./php/app/app.php');

// config form
$dataPath = "data/json/config.json";
// $dataPublishPath = "apps/_data/config.json";

// include common app data & config editor backend
new JsonEdit("Generic Config", "ConfigFormView", $dataPath, JsonEdit::MODE_DEFAULT);

?>

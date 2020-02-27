<?php
include('./php/app/app.php');

// create dashboard object & pull in json post data
$dashboard = new Dashboard($request, "data/dashboard/", 24);
$dashboard->storePostedCheckIn();

?>

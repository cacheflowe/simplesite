<?php
include('./php/app/app.php');

// get dashboard data
$dashboard = new Dashboard($request, "data/json/dashboard.json");

?>

<div data-view-type="DashboardView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Dashboard</h1>
    <div class="dashboard-props">
      <?php
        print($dashboard->listCards());
      ?>
    </div>
</div>

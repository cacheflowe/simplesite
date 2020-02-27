<?php
include('./php/app/app.php');

// init dashboard object
$dashboard = new Dashboard($request, "data/dashboard/", 24, true);

?>

<div data-view-type="DashboardView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Dashboard</h1>
    <div class="dashboard-items">
      <?php
        $dashboard->checkActions();
        if(isset($_GET['detail'])) {
          print($dashboard->listProjectCheckins($_GET['detail']));
        } else {
          print($dashboard->listProjects());
        }
      ?>
    </div>
</div>

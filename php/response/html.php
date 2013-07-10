<?php global $request_props; ?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Site Starter</title>
    <meta name="description" content="">
    <meta name="author" content="sitestarter <?php echo date("Y"); ?>">
    <meta name="viewport" content="width=device-width">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!-- facebook open graph data --> 
    <meta property="og:site_name" content="Site Starter"/> 
    <meta property="og:title" content="Site Starter"/> 
    <meta property="og:description" content="The official site of Site Starter."/> 
    <meta property="og:type" content="website"/> 
    <meta property="og:url" content="http://www.sitestarter.com"/> 
    <meta property="og:image" content="http://farm7.static.flickr.com/6110/6343268416_a29b0bf36d_m.jpg"/> 
    <link rel="image_src" href="http://farm7.static.flickr.com/6110/6343268416_a29b0bf36d_m.jpg" /> 

    <!-- css -->
    <?php if( !isset( $_REQUEST['dev'] ) ) { ?>
    <link rel="stylesheet" href="/css/reset/normalize.css">
    <link rel="stylesheet" href="/css/reset/main.css">
    <link rel="stylesheet" href="/css/app/app.css">
    <?php } else { ?>
    <link rel="stylesheet" href="/css/style-min.css" type="text/css" media="all" title="interface" />    
    <?php } ?>
  </head>
  <body>
    <div id="main">
      <header>sitestarter</header>
      <!--[if lt IE 7]>
          <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
      <![endif]-->
      <nav id="main-nav">
        <div class="nav_item">
          <a href="/">Home</a>
        </div>
        <div class="nav_item">
          <a href="/collection">Collection</a>
        </div>
        <div class="nav_item">
          <a href="/about">About</a>
        </div>
        <div class="nav_item">
          <a href="/contact">Contact</a>
        </div>
      </nav>
      <section id="content_holder"><?php include './php/response/data.php'; ?></section>
      <footer id="content_footer">Copyright &copy; sitestarter <?php echo date("Y"); ?></footer>
    </div>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

    <?php if( !isset( $_REQUEST['dev'] ) ) { ?>
    <!-- libs -->
    <script src="/js/vendor/underscore.js"></script>
    <script src="/js/vendor/backbone.js"></script>
    <!-- sitestarter -->
    <script src="/js/ss/area_model.js"></script>
    <script src="/js/ss/tracking.js"></script>
    <!-- custom -->
    <script src="/js/app/views/area_home.js"></script>
    <script src="/js/app/main.js"></script>
    <?php } else { ?>
    <script src="/js/app-min.js"></script>
    <?php } ?>
  </body>
</html>

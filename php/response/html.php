<?php
  global $request;
  global $response;
  global $metadata;
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta content="no" name="imagetoolbar" />
    <?php // <!-- <link rel="alternate" type="application/rss+xml"  href="http://cacheflowe.com/data/xml/news.xml" title="CacheFlowe RSS Feed"> --> ?>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=3" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo $metadata->get_favicon(); ?>">
    <link rel="apple-touch-startup-image" href="<?php echo $metadata->get_favicon(); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo $metadata->get_appTitle(); ?>"> <!-- Make the app title different than the page title. -->
    <meta name="format-detection" content="telephone=no"> <!-- Disable automatic phone number detection. -->

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $metadata->get_favicon(); ?>">

    <title><?php echo $metadata->get_pageTitle(); ?></title>
    <meta name="keywords" content="<?php echo $metadata->get_pageKeywords(); ?>" />

    <meta name="description" content="<?php echo $metadata->get_pageDescription(); ?>" />
    <meta name="author" content="CacheFlowe <?php echo date("Y"); ?>" />
    <meta name="copyright" content="<?php echo $metadata->get_pageSite(); ?>" />

    <meta property="og:site_name" content="<?php echo $metadata->get_pageSite(); ?>"/>
    <meta property="og:title" content="<?php echo $metadata->get_pageTitle(); ?>"/>
    <meta property="og:description" content=""/>
    <meta property="og:type" content="<?php echo $metadata->get_pageType(); ?>"/>
    <meta property="og:url" content="<?php echo $metadata->get_pageURL(); ?>"/>
    <meta property="og:image" content="<?php echo $metadata->get_pageImage(); ?>"/>
    <?php if($metadata->get_pageVideo() !== null) { ?>

    <meta property="og:video:url" content="<?php echo $metadata->get_pageVideo(); ?>"/>
    <meta property="og:video:secure_url" content="<?php echo $metadata->get_pageVideo(); ?>"/>
    <meta property="og:video:type" content="text/html">
    <?php } ?>

    <!-- <meta name="twitter:card" content="summary"> -->
    <meta name="twitter:site" content="<?php echo $metadata->get_twitterUser(); ?>">
    <meta name="twitter:title" content="<?php echo $metadata->get_pageTitle(); ?>">
    <meta name="twitter:description" content="<?php echo $metadata->get_pageDescription(); ?>">
    <meta name="twitter:image" content="<?php echo $metadata->get_pageImage(); ?>">
    <meta name="twitter:image:src" content="<?php echo $metadata->get_pageImage(); ?>">
    <meta name="twitter:domain" content="<?php echo $metadata->get_pageDomain(); ?>">
    <?php if($metadata->get_pageVideo() !== null) { ?>

    <meta name="twitter:player" content="<?php echo $metadata->get_pageVideo(); ?>"/>
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">
    <meta name="twitter:card" value="player">
    <?php } ?>
    <?php if($isGif == true) { ?>

    <meta name="twitter:player" content="<?php echo $metadata->get_pageImage(); ?>"/>
    <!-- <meta name="twitter:player" content="<?php echo preg_replace("/^http:/i", "https:", $metadata->get_pageImage()); ?>"/> -->
    <meta name="twitter:player:width" content="720">
    <meta name="twitter:player:height" content="720">
    <meta name="twitter:card" value="summary_large_image">
    <!-- <meta name="twitter:card" value="player"> -->
    <?php } ?>

    <?php if( $request->isDev() == true ) { ?>
<link rel="stylesheet" href="/css/simplesite-vendor/normalize.css">
    <link rel="stylesheet" href="/css/simplesite-vendor/skeleton.css">
    <link rel="stylesheet" href="/css/simplesite-vendor/main.css">
    <link rel="stylesheet" href="/css/simplesite-vendor/embetter.css">
    <link rel="stylesheet" href="/css/app/app.css">
    <?php } else { ?>
    <link rel="stylesheet" href="/css/style-min.css" type="text/css" media="all" title="interface" />
    <?php } ?>

  </head>
  <body>
    <?php include './php/views/layout.php'; ?>
    <?php if( $request->isDev() == true ) { ?>
    <!-- vendor / simplesite -->
    <script src="/js/simplesite-vendor/embetter.js"></script>
    <script src="/js/simplesite-vendor/easy-scroll.js"></script>
    <script src="/js/simplesite-vendor/page.js"></script>
    <script src="/js/simplesite/area-model.es6.js"></script>
    <script src="/js/simplesite/base-view.es6.js"></script>
    <script src="/js/app/views/area-common.es6.js"></script>
    <script src="/js/app/app.es6.js"></script>
    <?php } else { ?>
    <script src="/js/app-min.js"></script>
    <?php } ?>
  </body>
</html>

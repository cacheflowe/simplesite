<?php
  global $request;
  global $response;
  global $metadata;
  global $serverConfig;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta content="no" name="imagetoolbar" />
    <meta name="viewport" content="<?php echo $metadata->get_viewport(); ?>" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo $metadata->get_favicon(); ?>">
    <link rel="apple-touch-startup-image" href="<?php echo $metadata->get_favicon(); ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo $metadata->get_appTitle(); ?>">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $metadata->get_favicon(); ?>">
    <title><?php echo $metadata->get_pageTitle(); ?></title>
    <meta name="keywords" content="<?php echo $metadata->get_pageKeywords(); ?>" />
    <meta name="description" content="<?php echo $metadata->get_pageDescription(); ?>" />
    <meta name="author" content="CacheFlowe <?php echo date("Y"); ?>" />
    <meta name="copyright" content="<?php echo $metadata->get_pageSite(); ?>" />

    <meta property="og:site_name" content="<?php echo $metadata->get_pageSite(); ?>"/>
    <meta property="og:title" content="<?php echo $metadata->get_pageTitle(); ?>"/>
    <meta property="og:description" content="<?php echo $metadata->get_pageDescription(); ?>"/>
    <meta property="og:type" content="<?php echo $metadata->get_pageType(); ?>"/>
    <meta property="og:url" content="<?php echo $metadata->get_pageURL(); ?>"/>
    <meta property="og:image" content="<?php echo $metadata->get_pageImage(); ?>"/>
    <?php if($metadata->get_pageVideo() !== null) {
    ?><meta property="og:video:url" content="<?php echo $metadata->get_pageVideo(); ?>"/>
    <meta property="og:video:secure_url" content="<?php echo $metadata->get_pageVideo(); ?>"/>
    <meta property="og:video:type" content="text/html">
    <?php } ?><?php //<!-- <meta name="twitter:card" content="summary"> -->
    ?>

    <?php if($metadata->get_twitterUser() != null) { ?><meta name="twitter:site" content="@<?php echo($metadata->get_twitterUser()); ?>"><?php }?>

    <meta name="twitter:title" content="<?php echo $metadata->get_pageTitle(); ?>">
    <meta name="twitter:description" content="<?php echo $metadata->get_pageDescription(); ?>">
    <meta name="twitter:image" content="<?php echo $metadata->get_pageImage(); ?>">
    <meta name="twitter:image:src" content="<?php echo $metadata->get_pageImage(); ?>">
    <meta name="twitter:domain" content="<?php echo $metadata->get_pageDomain(); ?>">
    <?php if($metadata->get_pageVideo() !== null) {
    ?><meta name="twitter:player" content="<?php echo $metadata->get_pageVideo(); ?>"/>
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">
    <meta name="twitter:card" value="player">
    <?php } ?>
    <?php if(isset($isGif) && $isGif == true) { ?><!-- TODO: Currently not in use... -->

    <meta name="twitter:player" content="<?php echo $metadata->get_pageImage(); ?>"/>
    <?php //<!-- <meta name="twitter:player" content="<?php echo preg_replace("/^http:/i", "https:", $metadata->get_pageImage()); ?/>"/> --> ?>
    <meta name="twitter:player:width" content="720">
    <meta name="twitter:player:height" content="720">
    <meta name="twitter:card" value="summary_large_image">
    <?php //<!-- <meta name="twitter:card" value="player"> --> ?>
    <?php } ?>

    <?php if($serverConfig["forceHttps"] == true && isset($serverConfig["forceHttpsUrlMatch"])) { ?><script>
      if (location.protocol != 'https:' && location.href.match(/<?php echo($serverConfig["forceHttpsUrlMatch"]); ?>/i) && !location.href.match('localhost')) location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
    </script><?php } ?>

    <?php
    // include javascripts
    if( $request->isDev() == true ) {
      include './php/includes/css.php';
    } else {
      print('<link rel="stylesheet" href="/css/app.min.css" type="text/css" media="all" title="interface" />');
    }
    ?>
    <?php include './php/includes/head.php'; ?>

    <?php if(isset($serverConfig["gaID"])) { ?><script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', '<?php echo($serverConfig["gaID"]); ?>', 'auto');
      ga('send', 'pageview');
    </script><?php } ?>

  </head>
  <body>
    <?php include './php/layouts/layout.php'; ?>
    <?php
    // include javascripts
    if( $request->isDev() == true ) {
      include './php/includes/js.php';
    } else {
      print('<script src="/js/app.min.js"></script>');
    }
    ?>
  </body>
</html>

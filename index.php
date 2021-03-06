<?php
$serverConfig = [
  "alwaysDev" => false,
  "forceHttps" => true,
  "forceHttpsUrlMatch" => "simplesite.com",
  "gaID" => "UA-XXXXXXXX-10",
  "cssVersion" => "0.001",
  "jsVersion" => "0.001"
];
$metadataProps = [
  "pageSite" => "Simplesite.com",
  "pageTitle" => "Simplesite",
  "appTitle" => "Simplesite",
  "pageURL" => "https://github.com/cacheflowe/simplesite",
  "pageDomain" => "https://github.com/cacheflowe/simplesite",
  "pageDescription" => "Simplesite test site",
  "pageImage" => "http://cacheflowe.com/images/bio2_crop.jpg",
  "favicon" => "/images/icon.png",
  "pageKeywords" => "Simplesite test site",
  "viewport" => "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no",
  "twitterUser" => "TwitterUserName",
  // "pageVideo" => "",
  // "pageType" => "website",
];
$emailConfig = [
  "recipientEmail" => "your@email.com",
  "recipientName" => "First Last"
];
$constants = [
  "password" => "PASSWORD",
  "jsonPath" => "./data/json/",
  "uploadPathMentors" => "uploads/mentors/",
  "uploadPathTakeovers" => "uploads/takeovers/",
  "scheduleJson" => "schedule.json",
  "countJson" => "count.json",
  "configJson" => "config.json",
];
include('./simplesite/php/simplesite.php');
?>

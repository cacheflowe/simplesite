<?php

class Metadata {

  function __construct() {
    $this->pageSite = "CacheFlowe.com";
    $this->pageTitle = "CacheFlowe";
    $this->appTitle = "CacheFlowe";
    $this->pageURL = "http://cacheflowe.com";
    $this->pageDomain = "http://cacheflowe.com";
    $this->pageDescription = "Home of hacker, musican and artist Justin Gitlin, a.k.a. CacheFlowe";
    $this->cacheFloweImage = "http://cacheflowe.com/images/bio2_crop.jpg";
    $this->pageImage = "http://cacheflowe.com/images/bio2_crop.jpg";
    $this->favicon = "/images/icon.png";
    $this->pageKeywords = "cacheflowe code music visual experimental digital art denver";
    $this->pageVideo = null;
    $this->pageType = "website";
    $this->checkGif();
  }

  function checkGif() {
    // if a gif, let's make that embeddable by Facebook
    $isGif = (strpos($pageImage, ".gif") !== false);
    if($isGif == true) {
      $this->set_pageURL($pageImage);
      $this->set_pageType("video.other");
    }
  }

  function get_pageSite() { return $this->pageSite; }
  function get_pageTitle() { return $this->pageTitle; }
  function get_appTitle() { return $this->appTitle; }
  function get_pageURL() { return $this->pageURL; }
  function get_pageDomain() { return $this->pageDomain; }
  function get_pageDescription() { return $this->pageDescription; }
  function get_cacheFloweImage() { return $this->cacheFloweImage; }
  function get_pageImage() { return $this->pageImage; }
  function get_favicon() { return $this->favicon; }
  function get_pageKeywords() { return $this->pageKeywords; }
  function get_pageVideo() { return $this->pageVideo; }
  function get_pageType() { return $this->pageType; }

  function set_pageSite($val) { $this->pageSite = $val; }
  function set_pageTitle($val) { $this->pageTitle = $val; }
  function set_appTitle($val) { $this->appTitle = $val; }
  function set_pageURL($val) { $this->pageURL = $val; }
  function set_pageDomain($val) { $this->pageDomain = $val; }
  function set_pageDescription($val) { $this->pageDescription = $val; }
  function set_cacheFloweImage($val) { $this->cacheFloweImage = $val; }
  function set_pageImage($val) { $this->pageImage = $val; $this->checkGif(); }
  function set_favicon($val) { $this->favicon = $val; }
  function set_pageKeywords($val) { $this->pageKeywords = $val; }
  function set_pageVideo($val) { $this->pageVideo = $val; }
  function set_pageType($val) { $this->pageType = $val; }

  function __destruct() {
    // $this->htmlStr = null;
  }
}

?>

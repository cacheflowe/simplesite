<?php


class Metadata {

  function __construct($options) {
    $this->pageSite = (isset($options['pageSite'])) ? $options['pageSite'] : "### Page Site Name Here ### CacheFlowe.com";
    $this->pageTitle = (isset($options['pageTitle'])) ? $options['pageTitle'] : "### Page Title Here ### CacheFlowe";
    $this->appTitle = (isset($options['appTitle'])) ? $options['appTitle'] : "### Homescreen app title here ### CacheFlowe";
    $this->pageURL = (isset($options['pageURL'])) ? $options['pageURL'] : "### Page URL Here ### http://cacheflowe.com";
    $this->pageDomain = (isset($options['pageDomain'])) ? $options['pageDomain'] : "### Page Domain Here ###http://cacheflowe.com";
    $this->pageDescription = (isset($options['pageDescription'])) ? $options['pageDescription'] : "### Page Description Here ###";
    $this->pageImage = (isset($options['pageImage'])) ? $options['pageImage'] : "http://cacheflowe.com/images/bio2_crop.jpg";
    $this->favicon = (isset($options['favicon'])) ? $options['favicon'] : "/images/icon.png";
    $this->pageKeywords = (isset($options['pageKeywords'])) ? $options['pageKeywords'] : "### Keywords Here ###";
    $this->pageVideo = (isset($options['pageVideo'])) ? $options['pageVideo'] : null;
    $this->pageType = (isset($options['pageType'])) ? $options['pageType'] : "website";
    $this->twitterUser = (isset($options['twitterUser'])) ? $options['twitterUser'] : "@twitterUser";
    $this->isGif = false;
    $this->checkGif();
  }

  function checkGif() {
    // if a gif, let's make that embeddable by Facebook
    $this->isGif = (strpos($this->pageImage, ".gif") !== false);
    if($this->isGif == true) {
      $this->set_pageURL($this->pageImage);
      $this->set_pageType("video.other");
    }
  }

  function get_pageSite() { return $this->pageSite; }
  function get_pageTitle() { global $request; return $this->pageTitle . ucwords(implode(' | ', explode('/', $request->path()))); }
  function get_appTitle() { return $this->appTitle; }
  function get_pageURL() { return $this->pageURL; }
  function get_pageDomain() { return $this->pageDomain; }
  function get_pageDescription() { return $this->pageDescription; }
  function get_pageImage() { return $this->pageImage; }
  function get_favicon() { return $this->favicon; }
  function get_pageKeywords() { return $this->pageKeywords; }
  function get_pageVideo() { return $this->pageVideo; }
  function get_pageType() { return $this->pageType; }
  function get_twitterUser() { return $this->twitterUser; }

  function set_pageSite($val) { $this->pageSite = $val; }
  function set_pageTitle($val) { $this->pageTitle = $val; }
  function set_appTitle($val) { $this->appTitle = $val; }
  function set_pageURL($val) { $this->pageURL = $val; }
  function set_pageDomain($val) { $this->pageDomain = $val; }
  function set_pageDescription($val) { $this->pageDescription = $val; }
  function set_pageImage($val) { $this->pageImage = $val; $this->checkGif(); }
  function set_favicon($val) { $this->favicon = $val; }
  function set_pageKeywords($val) { $this->pageKeywords = $val; }
  function set_pageVideo($val) { $this->pageVideo = $val; }
  function set_pageType($val) { $this->pageType = $val; }
  function set_twitterUser($val) { $this->twitterUser = $val; }

  function __destruct() {
    // $this->htmlStr = null;
  }
}

?>

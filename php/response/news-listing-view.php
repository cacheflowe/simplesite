<?php

class NewsListingView {
  function __construct( $xmlFile, $path, $pathParams, $pathComponents ) {
    $this->xmlFile = $xmlFile;
    $this->path = $path;
    $this->pathParams = $pathParams;
    $this->pathComponents = $pathComponents;
    $this->loadXmlData();
    $this->updateMetaData();
    $this->parseXmlData();
  }

  function loadXmlData() {
    // get data for page
    $this->xml = simplexml_load_file( $this->xmlFile );
    $this->feedChannel = $this->xml->xpath( "/rss/channel" );
    $this->feedTitle = $this->feedChannel[0]->title;
    $this->feedLink = $this->feedChannel[0]->link;
    $this->feedImage = $this->feedChannel[0]->image;
    $this->feedDescription = $this->feedChannel[0]->description;
    $this->feedHeader = $this->feedChannel[0]->pageHeader;
    $this->postItems = $this->xml->xpath( "/rss/channel/item" );
    $this->feedVideo = null;
  }

  function updateMetaData() {
    global $metadata;
    if( !empty($this->feedTitle) ) $metadata->set_pageTitle($this->feedTitle);
    if( !empty($this->feedLink) ) $metadata->set_pageURL($this->feedLink);
    if( !empty($this->feedImage) ) $metadata->set_pageImage($this->feedImage);
    if( !empty($this->feedDescription) ) $metadata->set_pageDescription($this->feedDescription);
    if( !empty($this->feedVideo) ) $metadata->set_pageVideo($this->feedVideo);
  }

  function fillEmptyFriendlyUrl( $item ) {
    // create friendlyUrl from title if one doesn't exist in xml
    global $string_utils;
    if(empty($item->friendlyUrl) == true) {
      $item->friendlyUrl = $string_utils->makeFriendlyText($item->title);
    }
  }

  function parseXmlData() {
    // loop through RSS content and write html
    if( $this->pathParams == '' ) {
      $this->html = $this->feedHeader;
      $this->showListing();
    }
    /* else if( $this->pathComponents[0] == 'page' ) {
      // show paged results
      for( $i = $pathParamsComponents[1] * $itemsPerPage; $i < count($this->postItems); $i++ ) {
        $this->html .= $this->displayNewsItem( $this->postItems[$i], $this->path );
      }
    } */
    else {
      $this->html = '';
      $this->showPost();
    }
    return $this->html;
  }

  function showListing() {
    if(strcmp($this->path, "/newsNoMore") === 0) {
      // show full posts as landing page
      for ( $i = 0; $i < count($this->postItems); $i++ ) {
        $this->html .= $this->displayNewsItem( $this->postItems[$i], $this->path );
      }
    } else {
      // show thumbnails on landing page
      for ( $i = 0; $i < count($this->postItems); $i++ ) {
        if($i % 2 == 0) {
          $this->html .= '<div class="row"><div class="six columns">' . $this->displayNewsItemPreview( $this->postItems[$i], $this->path ) . '</div>';
        } else {
          $this->html .= '<div class="six columns">' . $this->displayNewsItemPreview( $this->postItems[$i], $this->path ) . '</div></div>';
        }
      }
      if($i % 2 == 1) {
        $this->html .= '<div class="six columns"></div></div>';
      }
    }
  }

  function showPost() {
    $friendlyUrl = str_replace("/", "", $this->pathParams);
    // show a single item
    for( $i = 0; $i < count($this->postItems); $i++ ) {
      // find requested item
      $this->fillEmptyFriendlyUrl($this->postItems[$i]);
      if( $friendlyUrl == $this->postItems[$i]->friendlyUrl ) {
        $this->html .= $this->displayNewsItem( $this->postItems[$i], $this->path );
        // add metadata for single post
        $this->feedTitle = $this->postItems[$i]->title;
        $this->feedLink = "http://cacheflowe.com".$this->path.'/'.$this->postItems[$i]->friendlyUrl;
        $this->feedLink = "https://cacheflowe.com".$this->path.'/'.$this->postItems[$i]->friendlyUrl;
        $this->feedImage = $this->postItems[$i]->image;
        if(isset($this->postItems[$i]->metaimage) == true) $this->feedImage = $this->postItems[$i]->metaimage; // override meta image if it exists. useful for gif previews
        if(isset($this->postItems[$i]->description) == true) {
          // make metatag-safe description
          $desc = strip_tags($this->postItems[$i]->description);
          $desc = str_replace(array("\r", "\n"), '', $desc);
          $desc = str_replace('"', "'", $desc);
          $desc = trim($desc);
          $this->feedDescription = $desc;
        }
        // add video metadata if we find one
        // youtube
        $pos = strrpos($this->postItems[$i]->description, "youtube.com/watch?v=");
        if ($pos !== false) {
          preg_match("/watch\?v=([a-zA-Z0-9-_]+)/i", $this->postItems[$i]->description, $output_array);
          $this->feedVideo = "https://www.youtube.com/embed/".$output_array[1]."?autoplay=true";
        }
        // vimeo
        $pos = strrpos($this->postItems[$i]->description, "vimeo.com/");
        if ($pos !== false) {
          preg_match("/vimeo.com\/([a-zA-Z0-9]+)/i", $this->postItems[$i]->description, $output_array);
          $this->feedVideo = "https://player.vimeo.com/video/".$output_array[1]."?autoplay=1";
        }
        /*
        // soundcloud
        $pos = strrpos($this->postItems[$i]->description, "soundcloud.com/");
        if ($pos !== false) {
          preg_match("/data-soundcloud-id=\"([a-zA-Z0-9-_\/]+)\"/i", $this->postItems[$i]->description, $output_array);
          $this->feedVideo = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/".$output_array[1]."&amp;auto_play=true&amp;hide_related=true&amp;color=373737&amp;show_comments=false&amp;show_user=true&amp;show_reposts=false&amp;visual=true";
        }
        */
      }
    }
  }

  function displayNewsItemPreview( $item, $path ) {
    if(empty($item->image) == true) return "";

    // create friendlyUrl from title if one doesn't exist in xml
    $this->fillEmptyFriendlyUrl($item);

    // render item
    $html = '';
    $html .= '<div class="content-preview">';
    if( !empty($item->redirectUrl) ) {
      // redirects for store & news sections
      $html .= '<a href="'.$item->redirectUrl.'">';
    } else {
      $html .= '<a href="'.$path.'/'.$item->friendlyUrl.'">';
    }
    if( !empty( $item->image ) ) $html .= '<div class="content-preview-thumb" style="background-image:url('.$item->image.')"></div>';
    if( !empty( $item->title ) ) $html .= '<div class="content-preview-title">'.$item->title.'</div>';
    $html .= '</a>';
    $html .= '</div>';
    return $html;
  }

  function displayNewsItem( $item, $path ) {
    // create friendlyUrl from title if one doesn't exist in xml
    $this->fillEmptyFriendlyUrl($item);

    // render types
    $isMusicLayout = (strcmp($path, "/music/discography") === 0 || strcmp($path, "/music/mixes") === 0) ? true : false;

    // render item
    $html = '';
    $html .= '<div class="content-post" itemscope itemtype="http://schema.org/Article">';
    $html .= '<h2><span itemprop="name">'.$item->title.'</span></h2>'; // echo '<h1><a href="'.$pageURL."#".$item->friendlyUrl.'">'.$item->title.'</a></h1>';
    // $html .= '<h2><a href="'.$path.'/'.$item->friendlyUrl.'" itemprop="name">'.$item->title.'</a></h2>'; // echo '<h1><a href="'.$pageURL."#".$item->friendlyUrl.'">'.$item->title.'</a></h1>';

    // if( !empty( $item->description ) ) $html .= '<section itemprop="articleBody">' . $item->description . '</section>';
    if($isMusicLayout == false) {
      if( !empty( $item->date ) ) $html .= '<p class="date">'.$item->date.'</p>';
      if( !empty( $item->description ) ) {
        $desc = str_replace('src="http://cacheflowe.com', "src=\"", $item->description);  // force images to be absolute to local server
        $html .= $desc;
      }
      if( !empty( $item->downloadLink ) ) $html .= '<p><a class="button button-primary" href="'.$item->downloadLink.'">Download</a></p>';
      if( !empty( $item->tracklist ) ) $html .= '<h5>Tracklist:</h5>'.str_replace( "<ul>", "<ul class=\"content-tracklist\">", $item->tracklist ).'';
      if( !empty( $item->purchase ) ) $html .= '<p class="content-purchase">'.$item->purchase.'</p>';
      if( !empty( $item->label ) ) $html .= '<p class="content-label">Label: '.$item->label.'</p>';
      if( !empty( $item->format ) ) $html .= '<p class="content-format">Format: '.$item->format.'</p>';
    } else {
      if( !empty( $item->style ) ) $html .= '<p class="date">'.$item->style.'</p>';  // for /music/mixes
      $html .= '<div class="row">';
      $html .= '<div class="five columns">';
      if( !empty( $item->image ) ) {
        $html .= '<img class="content-album-art" src="'.$item->image.'" itemprop="image">';
      }
      if( !empty( $item->purchase ) ) $html .= '<div class="content-purchase">'.$item->purchase.'</div>';
      if( !empty( $item->downloadLink ) ) $html .= '<div class="content-purchase">';
      if( !empty( $item->downloadLink ) ) $html .= '<a class="button button-primary" href="'.$item->downloadLink.'">Download</a>';
      if( !empty( $item->downloadLink ) ) $html .= '<a class="button button-primary stream-audio" href="'.$item->downloadLink.'" data-title="'.$item->title.'">Stream</a>';
      if( !empty( $item->downloadLink ) ) $html .= '</div>';
      if( !empty( $item->date ) ) $html .= '<div class="content-release-date">Released: '.$item->date.'</div>';
      if( !empty( $item->label ) ) $html .= '<div class="content-label">Label: '.$item->label.'</div>';
      if( !empty( $item->format ) ) $html .= '<div class="content-format">Format: '.$item->format.'</div>';
      $html .= '</div>';

      $html .= '<div class="seven columns">';
      if( !empty( $item->description ) ) $html .= '<div class="content-description">'.$item->description.'</div>';
      if( !empty( $item->tracklist ) ) $html .= '<h5>Tracklist:</h5>'.str_replace( "<ul>", "<ul class=\"content-tracklist\">", $item->tracklist ).'';
      if( !empty( $item->embeds ) ) $html .= '<h5>Media:</h5><div class="content-embeds">'.$item->embeds.'</div>';
      $html .= '</div>';
      $html .= '</div>';
    }

    // create grid columns here - keep track of number created and mod for 2 columns
    $infoListCells = 0;
    if(isset($item->buyLinks)) {      $html .= $this->formatListContent($item->buyLinks, "Buy", "content-buy-links content-infolist", $infoListCells); $infoListCells++; }
    if(isset($item->technologies)) {  $html .= $this->formatListContent($item->technologies, "Key Technologies", "content-infolist", $infoListCells); $infoListCells++; }
    if(isset($item->partners)) {      $html .= $this->formatListContent($item->partners, "Partners", "content-infolist", $infoListCells); $infoListCells++; }
    if(isset($item->awards)) {        $html .= $this->formatListContent($item->awards, "Awards", "content-infolist", $infoListCells); $infoListCells++; }
    if(isset($item->press)) {         $html .= $this->formatListContent($item->press, "Press", "content-infolist", $infoListCells); $infoListCells++; }
    if($infoListCells % 2 == 1) $html .= '</div>';

    // add share links
    $html .= file_get_contents('share-links.php', true);
    $html .= '</div>';
    return $html;
  }

  function formatListContent( $itemData, $listTitle, $listClass, $cellIndex ) {
    if( !empty( $itemData ) ) {
      // TODO: probably can get rid of $listClass ?
      $listContent = '<h5>' . $listTitle . ':</h5>';
      $listContent .= str_replace( '<ul>', '<ul class="' . $listClass . '">', $itemData );
      $listContent = str_replace( '<li><a', '<li><a class="button"', $listContent );
      // $listContent = str_replace( '<li>', '<li class="tag">', $listContent );
      // $listContent = str_replace( '<li class="tag"><a class="button"', '<li><a class="button"', $listContent );
      // $listContent = str_replace( '<a class="button"', '<a class="button button-primary"', $listContent );
      // $listContent = str_replace( $listClass . '">', $listClass . '"><li class="list-title"><h5>' . $listTitle . ':</h5></li>', $listContent );
      // add grid wrapper
      $listContent = '<div class="six columns">'.$listContent.'</div>';
      if($cellIndex % 2 == 0) $listContent = '<div class="row">'.$listContent;
      if($cellIndex % 2 == 1) $listContent = $listContent.'</div>';
      return $listContent;
    }
    return '';
  }

  function __destruct() {
    $this->request = null;
  }
}

?>

<?php

class NewsListingView {
  function __construct( $xmlFile, $path, $pathParams, $pathComponents ) {
    $this->xmlFile = $xmlFile;
    $this->path = $path;
    $this->pathParams = $pathParams;
    $this->pathComponents = $pathComponents;
    $this->loadXmlData();
    $this->parseXmlData();
    $this->updateMetaData();
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
    global $metadata;

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
      // update page title if detail view. feedTitle was updated in showPost() - add to title here
      $metadata->set_pageTitle($metadata->get_pageTitle() . ' | ' . $this->feedTitle);
    }
    return $this->html;
  }

  function showListing() {
      // remove column logic - switching to css grid
      $this->html .= '<div class="listing-previews">' . "\n";
      for ( $i = 0; $i < count($this->postItems); $i++ ) {
        if(empty($this->postItems[$i]->hide)) {
          $this->html .= $this->displayNewsItemPreview( $this->postItems[$i], $this->path, $i ) . "\n";
        }
      }
      $this->html .= '</div>' . "\n";
  }

  function metaTagSafeString($str) {
    $str = strip_tags($str);
    $str = str_replace(array("\r", "\n"), '', $str);
    $str = str_replace('"', "'", $str);
    return trim($str);
  }

  function extractVideoLinksFromContent($htmlStr) {
    // youtube
    $pos = strrpos($htmlStr, "youtube.com/watch?v=");
    if ($pos !== false) {
      preg_match("/watch\?v=([a-zA-Z0-9-_]+)/i", $htmlStr, $output_array);
      $this->feedVideo = "https://www.youtube.com/embed/".$output_array[1]."?autoplay=true";
    }
    // vimeo
    $pos = strrpos($htmlStr, "vimeo.com/");
    if ($pos !== false) {
      preg_match("/vimeo.com\/([a-zA-Z0-9]+)/i", $htmlStr, $output_array);
      $this->feedVideo = "https://player.vimeo.com/video/".$output_array[1]."?autoplay=1";
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
        $this->feedTitle = $this->feedTitle . ' | ' . $this->postItems[$i]->title;
        // $this->feedLink = "http://cacheflowe.com".$this->path.'/'.$this->postItems[$i]->friendlyUrl;
        // $this->feedLink = "https://cacheflowe.com".$this->path.'/'.$this->postItems[$i]->friendlyUrl;
        $this->feedImage = $this->postItems[$i]->image;
        if(isset($this->postItems[$i]->metaimage) == true) $this->feedImage = $this->postItems[$i]->metaimage; // override meta image if it exists. useful for gif previews
        if(isset($this->postItems[$i]->description) == true) $this->feedDescription = $this->metaTagSafeString($this->postItems[$i]->description);
        // add video metadata if we find one
        $this->extractVideoLinksFromContent($this->postItems[$i]->description);
        $this->extractVideoLinksFromContent($this->postItems[$i]->embeds);
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

  function displayNewsItemPreview( $item, $path, $index ) {
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
    $linkImg = $item->image;
    // $linkImg = str_replace("https://cacheflowe.com", "", $linkImg);  // force images to be absolute to local server
    // $linkImg = str_replace("http://cacheflowe.com", "", $linkImg);   // force images to be absolute to local server
    // $linkImg = str_replace("http://localhost.cacheflowe.com:3101", "", $linkImg);   // force images to be absolute to local server
    if($index < 4) {  // lazy load images after the 4th
      if( !empty( $linkImg ) ) $html .= '<div class="content-preview-thumb" style="background-image:url('.$linkImg.')"></div>';
    } else {
      if( !empty( $linkImg ) ) $html .= '<div class="content-preview-thumb" data-src="'.$linkImg.'" data-src-bg="true"></div>';
    }
    if( !empty( $item->title ) ) $html .= '<div class="content-preview-title">'.$item->title.'</div>';
    $html .= '</a>';
    $html .= '</div>';
    return $html;
  }

  function displayNewsItem( $item, $path ) {
    // create friendlyUrl from title if one doesn't exist in xml
    $this->fillEmptyFriendlyUrl($item);

    // special render types
    $isMusicLayout = (strcmp($path, "/music/discography") === 0 || strcmp($path, "/music/mixes") === 0) ? true : false;

    // render item
    $html = '';
    $html .= '<div class="content-post" itemscope itemtype="http://schema.org/Article">';
    $html .= '<h2><span itemprop="name">'.$item->title.'</span></h2>';

    // if( !empty( $item->description ) ) $html .= '<section itemprop="articleBody">' . $item->description . '</section>';
    if($isMusicLayout == false) {
      if( !empty( $item->date ) ) $html .= '<p class="date">'.$item->date.'</p>';
      if( !empty( $item->description ) ) {
        // $desc = str_replace('src="http://cacheflowe.com', "src=\"", $item->description);  // force images to be absolute to local server
        // $desc = str_replace('src="https://cacheflowe.com', "src=\"", $desc);  // force images to be absolute to local server
        $desc = str_replace('http://cacheflowe.com/images/', "/images/", $item->description);  // force images to be absolute to local server
        $desc = str_replace('https://cacheflowe.com/images/', "/images/", $desc);  // force images to be absolute to local server
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
      $html .= '<div class="release-info">';
      if( !empty( $item->purchase ) ) $html .= '<div class="content-purchase">'.$item->purchase.'</div>';
      if( !empty( $item->downloadLink ) ) $html .= '<div class="content-purchase">';
      if( !empty( $item->downloadLink ) ) $html .= '<a class="button button-primary" href="'.$item->downloadLink.'">Download</a>';
      if( !empty( $item->downloadLink ) ) $html .= '<a class="button button-primary stream-audio" href="'.$item->downloadLink.'" data-title="'.$item->title.'">Stream</a>';
      if( !empty( $item->downloadLink ) ) $html .= '</div>';
      if( !empty( $item->date ) ) $html .= '<div class="content-release-date">Released: '.$item->date.'</div>';
      if( !empty( $item->label ) ) $html .= '<div class="content-label">Label: '.$item->label.'</div>';
      if( !empty( $item->format ) ) $html .= '<div class="content-format">Format: '.$item->format.'</div>';
      $html .= '</div>';

      $html .= '</div>';  // end five columns

      $html .= '<div class="seven columns">';
      if( !empty( $item->description ) ) $html .= '<div class="content-description">'.$item->description.'</div>';
      if( !empty( $item->tracklist ) ) $html .= '<h5>Tracklist:</h5>'.str_replace( "<ul>", "<ul class=\"content-tracklist\">", $item->tracklist ).'';
      if( !empty( $item->embeds ) ) $html .= '<h5>Media:</h5><div class="content-embeds">'.$item->embeds.'</div>';
      $html .= '</div>';
      $html .= '</div>';
    }

    // create project info grid columns here - keep track of number created and mod for 2 columns
    $projectInfoHTML = "";
    if(isset($item->buyLinks))        $projectInfoHTML .= $this->formatListContent($item->buyLinks, "Buy", "content-buy-links content-infolist", true );
    if(isset($item->partners) || isset($item->technologies)) {
      $projectInfoHTML .= '<div class="row">';
      if(isset($item->partners))        $projectInfoHTML .= '<div class="six columns">' . $this->formatListContent($item->partners, "Partners", "content-infolist", false ) . '</div>';
      if(isset($item->technologies))    $projectInfoHTML .= '<div class="six columns">' . $this->formatListContent($item->technologies, "Key Technologies", "content-infolist", false ) . '</div>';
      $projectInfoHTML .= '</div>';
    }
    if(isset($item->awards))          $projectInfoHTML .= $this->formatListContent($item->awards, "Awards", "content-infolist", false );
    if(isset($item->press))           $projectInfoHTML .= $this->formatListContent($item->press, "Press", "content-infolist", false );
    if(strlen($projectInfoHTML) > 0) {
      $html .= '<div class="project-info">' . $projectInfoHTML . '</div>' . "\n";
    }

    // add share links
    $html .= '<div class="share-out-links">';
    $html .= '  <span>Share this:</span>';
    $html .= '    <a href="#" class="icon-link" data-network="email">' . file_get_contents('./simplesite/images/icons/mail.svg', true) . '</a>';
    $html .= '    <a href="#" class="icon-link" data-network="facebook">' . file_get_contents('./simplesite/images/icons/facebook.svg', true) . '</a>';
    $html .= '    <a href="#" class="icon-link" data-network="twitter">' . file_get_contents('./simplesite/images/icons/twitter.svg', true) . '</a>';
    $html .= '    <a href="#" class="icon-link" data-network="pinterest">' . file_get_contents('./simplesite/images/icons/pinterest.svg', true) . '</a>';
    $html .= '    <a href="#" class="icon-link" data-network="tumblr">' . file_get_contents('./simplesite/images/icons/tumblr.svg', true) . '</a>';
    $html .= '    <a href="#" class="icon-link" data-network="linkedin">' . file_get_contents('./simplesite/images/icons/linkedin.svg', true) . '</a>';
    $html .= '</div>';

    // close it up
    $html .= '</div>';
    return $html;
  }

  function formatListContent( $itemData, $listTitle, $listClass, $buttonPrimary ) {
    if( !empty( $itemData ) ) {
      // TODO: probably can get rid of $listClass ?
      $listContent = '<h5>' . $listTitle . ':</h5>';
      $listContent .= str_replace( '<ul>', '<ul class="' . $listClass . '">', $itemData );
      if($buttonPrimary == true) $listContent = str_replace( '<li><a', '<li><a class="button button-primary"', $listContent );
      // $listContent = str_replace( '<li>', '<li class="tag">', $listContent );
      // $listContent = str_replace( '<li class="tag"><a class="button"', '<li><a class="button"', $listContent );
      // $listContent = str_replace( '<a class="button"', '<a class="button button-primary"', $listContent );
      // $listContent = str_replace( $listClass . '">', $listClass . '"><li class="list-title"><h5>' . $listTitle . ':</h5></li>', $listContent );
      return $listContent;
    }
    return '';
  }

  function __destruct() {
    $this->request = null;
  }
}

?>

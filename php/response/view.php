<?php
class View {
  function __construct() {
    $this->htmlStr = '';
    $this->buildHtmlData();
  }

	function html() { return $this->htmlStr . $this->pageJsFix(); }

  function pageJsFix() { return "<script>if(document.querySelector('head meta') == null) document.location.reload();</script>"; }

  function buildHtmlData() {
		// get path array and decide how to include the proper ajax page
		global $request;

		// get basic path
		$dataRoot = './data/xml/';
		$pagesRoot = './php/views/';
		$path = $request->path();
		if($path == '' || $path == '/') $path = '/home';
		$pathParams = '';

		// if route is found in current path, send the params along to the view and reassign the path as the route, with the params sent along to find the detail view or
		foreach( $request->routes() as $route ) {
		  if( strpos( $path, $route ) !== false ) {
		  	$pathParams = str_replace( $route, "", $path );
		  	$path = $route;
		  	break;
		  }
		}

		// check for deeper pages and re-split pathComponents since it may have changed
		$pathComponents = explode( '/', substr( $path, 1 ) );
		$deepPathPage = implode("-", $pathComponents) . '.php';
		$deepPathData = $dataRoot . implode("-", $pathComponents) . '.xml';

		// load a subview, a view, go home, or show a 404
		$xmlFile = '';
		$includeFile = '';
		$htmlStr = '';
		if( file_exists( $deepPathData ) == true ) {
			$xmlFile = "data/xml/" . implode("-", $pathComponents) . ".xml";
			$this->news_listing_view = new NewsListingView($xmlFile, $path, $pathParams, $pathComponents);
      $squarePreviewClass = (strpos( $route, '/music/discography' ) !== false || strpos( $route, '/art/' ) !== false || strpos( $route, '/store' ) !== false) ? ' class="content-square-previews"' : '';  // add special class for square previews
			$htmlStr = '<div'.$squarePreviewClass.' data-area-type="AreaCommon">' . $this->news_listing_view->html . '</div>';
		} else if( file_exists( $pagesRoot . $deepPathPage ) == true ) {
			$includeFile = $pagesRoot . $deepPathPage;
		} else {
			$includeFile = $pagesRoot . '404.php';
		}

    // pull in include file if there is one
		if($includeFile != '') {
			ob_start();
			include $includeFile;
			$htmlStr = ob_get_clean();
		}

		$this->htmlStr = $htmlStr;
  }

  function __destruct() {
    $this->htmlStr = null;
  }
}
?>

<?php
	// get path array and decide how to include the proper ajax page
	global $request_props;

	// get basic path 
	$pagesRoot = './php/views';
	$path = $request_props->path();
	$pathParams = '';

	// set up 'routes' for paths with collection ids
	$pathsWithParams = array(
		'/collection',
		'/press'
	);

	// break up paths that have params, and send the params along to the view
	foreach( $pathsWithParams as $pathComponent ) {
	  if( strpos( $path, $pathComponent ) !== false ) {
	  	$pathParams = str_replace( $pathComponent, "", $path );
	  	$path = $pathComponent;
	  	break;
	  }
	}
	// load a view, go home, or show a 404
	if( file_exists( $pagesRoot . $path . '.php' ) == true ) {
		include $pagesRoot . $path . '.php';
	} else if( $path == '' || $path == '/' ) {
		include $pagesRoot . '/home.php';
	} else {
		include $pagesRoot . '/404.php';
	}
	
?>

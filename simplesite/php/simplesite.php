<?php

// dependencies -------------------------------------------------------------------------------------------
include('./simplesite/php/util/date-util.php');
include('./simplesite/php/util/file-util.php');
include('./simplesite/php/util/json-util.php');
include('./simplesite/php/util/string-util.php');

include('./simplesite/php/request/login.php');
include('./simplesite/php/request/redirects.php');
include('./simplesite/php/request/request.php');

include('./simplesite/php/response/metadata.php');
include('./simplesite/php/response/view.php');
include('./simplesite/php/response/news-listing-view.php');
include('./simplesite/php/response/response.php');

// init ---------------------------------------------------------------------------------------------------
$request = new Request();
$metadataProps = ($metadataProps != null) ? $metadataProps : [];
$metadata = new Metadata($metadataProps);
$response = new Response($request);
$response->renderPageRequest();  // defer so html.php has access to response obj

?>

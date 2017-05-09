<?php

include('./simplesite/php/request/redirects.php');
include('./simplesite/php/request/request.php');
include('./simplesite/php/util/string-utils.php');
include './simplesite/php/response/metadata.php';
include './simplesite/php/response/view.php';
include('./simplesite/php/response/news-listing-view.php');
include('./simplesite/php/response/response.php');


// init ---------------------------------------------------------------------------------------------------
$string_utils = new StringUtils();
$request = new Request($routes);
$metadataProps = ($metadataProps != null) ? $metadataProps : [];
$metadata = new Metadata($metadataProps);
$response = new Response($request);
$response->renderPageRequest();  // defer so html.php has access to response obj

?>

<?php

include('./php/request/redirects.php');
include('./php/request/request.php');
include('./php/util/string-utils.php');
include './php/response/metadata.php';
include './php/response/view.php';
include('./php/response/news-listing-view.php');
include('./php/response/response.php');


// init ---------------------------------------------------------------------------------------------------
$string_utils = new StringUtils();
$request = new Request($routes);
$metadataProps = ($metadataProps != null) ? $metadataProps : [];
$metadata = new Metadata($metadataProps);
$response = new Response($request);
$response->renderPageRequest();  // defer so html.php has access to response obj

?>

<?php

include('./php/app/app.php');
Login::resetPasswordCookie();
// JsonUtil::setJsonOutput();
// echo '{"reset": true}';

?>
<div id="login-reset" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
	<h1>Bye</h1>
	<p>You are now logged out.</p>
</div>

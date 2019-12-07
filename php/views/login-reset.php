<?php
include('./php/app/app.php');
Login::resetPasswordCookie();
?>
<div data-view-type="LoginView" id="login-reset" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
	<h1>Bye</h1>
	<p>You are now logged out.</p>
	<p><a href="/login" class="button button-primary">Log in</a></p>
</div>

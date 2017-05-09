<?php
global $metadata;
$metadata->set_pageTitle('Cacheflowe / Contact');
?>
<div data-area-type="AreaCommon">
	<div id="content">
		<h1>Contact</h1>
		<p>You can <a href="#email" id="email-button">send me an email</a> or use the form below.</p>
		<h1>Send An Email</h1>
		<div id="form_holder">
		  <form method="post" id="contactform">
  			<div>Your Email:</div>
  			<div><input type="text" name="email" id="email" value="" /></div>
  			<div>About:</div>
  			<div><input type="text" name="about" value="" /></div>
  			<div>Message:</div>
  			<div><textarea name="message" id="message" value="" cols="30" rows="4"></textarea></div>
  			<div><input type="button" value="send" id="contact-submit" /></div>
  		</form>
		</div>
	</div>
</div>

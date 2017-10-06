<?php
global $metadata;
$metadata->set_pageTitle('Simplesite | Contact');
?>
<div data-view-type="FancyView" data-page-title="<?php echo $metadata->get_pageTitle(); ?>">
	<div id="content">
		<h1>Contact</h1>
		<p>You can email <a href="#email" id="email-button" data-username="cacheflowe" data-domain="cacheflowe.com"></a> or use the form below.</p>
		<h1>Send An Email</h1>
		<div id="form_holder">
		  <form method="post" id="contactform">
  			<div>Your Email:</div>
				<div><input type="text" name="email" id="email-input" value="" /></div>
		    <div>About:</div>
		    <div><input type="text" name="about" id="about-input" value="" /></div>
		    <div>Message:</div>
		    <div><textarea name="message" id="message-input" value="" cols="30" rows="4"></textarea></div>
  			<div><input type="button" value="send" id="contact-submit" /></div>
  		</form>
		</div>
	</div>
</div>

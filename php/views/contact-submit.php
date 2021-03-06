<?php
global $emailConfig;
?>

<span><b>Thanks for your email. You submitted:</b></span>
<pre>
Email: <?php echo StringUtil::protectYaText($_POST['email']); ?><br>
About: <?php echo StringUtil::protectYaText($_POST['about']); ?><br>
Message: <?php echo StringUtil::protectYaText($_POST['message']); ?><br>
</pre>

<?php
// include the PEAR mail class
include './simplesite/php/mail/Mail.php';

if(isset($_POST['email']) && isset($_POST['about']) && isset($_POST['message'])) {
  // set up the recipient and necessary headers
  $recipients = $emailConfig["recipientName"] . ' <' . $emailConfig["recipientEmail"] . '>';
  $headers['From']    = StringUtil::protectYaText($_POST['email']);
  $headers['To']      = $emailConfig["recipientName"];
  $headers['Subject'] = StringUtil::protectYaText($_POST['about']);

  // piece together the body message
  $body = "This message was generated by ".$_SERVER['SERVER_NAME']."\nfrom: ".StringUtil::protectYaText($_POST['email'])."\nmessage: ".StringUtil::protectYaText($_POST['message']);

  // Create the mail object and send it!
  $mail_object = Mail::factory('mail', $params);
  $mail_object->send($recipients, $headers, $body);
}
?>

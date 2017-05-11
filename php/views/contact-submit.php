<?php
global $emailConfig;
global $string_utils;
?>

<span><b>Thanks for your email. You submitted:</b></span>
<pre>
Email: <?php echo $string_utils->protectYaText($_POST['email']); ?><br>
About: <?php echo $string_utils->protectYaText($_POST['about']); ?><br>
Message: <?php echo $string_utils->protectYaText($_POST['message']); ?><br>
</pre>

<?php
// include the PEAR mail class
include './simplesite/php/mail/Mail.php';

if(isset($_POST['email']) && isset($_POST['about']) && isset($_POST['message'])) {
  // set up the recipient and necessary headers
  $recipients = $emailConfig["recipientName"] . ' <' . $emailConfig["recipientEmail"] . '>';
  $headers['From']    = $string_utils->protectYaText($_POST['email']);
  $headers['To']      = $emailConfig["recipientName"];
  $headers['Subject'] = $string_utils->protectYaText($_POST['about']);

  // piece together the body message
  $body = "This message was generated by ".$_SERVER['SERVER_NAME']."\nfrom: ".$string_utils->protectYaText($_POST['email'])."\nmessage: ".$string_utils->protectYaText($_POST['message']);

  // Create the mail object and send it!
  $mail_object = Mail::factory('mail', $params);
  $mail_object->send($recipients, $headers, $body);
}
?>

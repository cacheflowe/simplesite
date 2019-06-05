<?php

include('./php/app/app.php');
JsonUtil::setJsonOutput();
$didLogIn = Login::checkPostedLoginPassword();
if($didLogIn) {
  echo '{"success": true}';
} else {
  echo '{"success": false}';
}

?>

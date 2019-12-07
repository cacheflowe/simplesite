<?php

include('./php/app/app.php');
JsonUtil::setJsonOutput();
$didLogIn = Login::checkPostedLoginPassword();
if($didLogIn) {
  JsonUtil::printSuccessMessage(true);
} else {
  JsonUtil::printFailMessage(false);
}

?>

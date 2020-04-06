<?php

include('./php/app/app.php');
JsonUtil::setJsonOutput();
$postedPassword = $request->postedJson()[Login::PASS_KEY]; // get val from password key in submitted json object
$didLogIn = Login::checkPostedLoginPassword($postedPassword, $constants[Login::PASS_KEY]);
if($didLogIn) {
  JsonUtil::printSuccessMessage(true);
} else {
  JsonUtil::printFailMessage(false);
}

?>

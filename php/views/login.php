<div data-view-type="LoginView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <!-- <h1>Login</h1> -->
  <form id="login-form" action="/login/submit" target="_blank" method="POST">
    <input class="" type="text" name="password" id="password" placeholder="Password">
    <button type="submit">Log In</button>
    <div id="login-error">Bad password</div>
  </form>
</div>

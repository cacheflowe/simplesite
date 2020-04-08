<nav id="main-nav">
  <button id="main-nav-toggle"></button>
  <div id="main-nav-links">
    <a href="/"><img src="/images/preview.png" alt="Site logo"></a>
    <a href="/">Home</a>
    <a href="/collection">Collection</a>
    <a href="/about">About</a>
    <a href="/contact">Contact</a>
    <a href="/test/feed">RSS feed</a>
    <div class="section-header">CMS</div>
    <a href="/config">Config</a>
    <a href="/events">Events</a>
    <a href="/schedule">Schedule</a>
    <a href="/count">Count</a>
    <a href="/customtext">Custom Text</a>
    <a href="/demodesk">Demo Desk</a>
    <div class="section-header">Theme</div>
    <button class="dark-theme-toggle"></button>

    <span class="needs-login">
      <div class="section-header">User</div>
      <a href="/login/reset">Log Out</a>
    </span>
  </div>
</nav>
<div id="main">
  <section id="content-holder"><?php echo $response->view->html(); // insert ajax content on first page load ?></section>
  <footer id="content-footer">Copyright &copy; simplesite <?php echo date("Y"); ?></footer>
</div>
<div class="loader"></div>
<div class="content-overlay"></div>
<div id="localhost-status"></div>

    <div id="main">
      <header><h1>SimpleSite</h1></header>
      <nav id="main-nav">
        <div class="nav_item">
          <a href="/">Home</a>
        </div>
        <div class="nav_item">
          <a href="/collection">Collection</a>
        </div>
        <div class="nav_item">
          <a href="/about">About</a>
        </div>
        <div class="nav_item">
          <a href="/events">Events</a>
        </div>
        <div class="nav_item">
          <a href="/schedule">Schedule</a>
        </div>
        <div class="nav_item">
          <a href="/count">Count</a>
        </div>
        <div class="nav_item">
          <a href="/mentors">Mentors</a>
        </div>
        <div class="nav_item">
          <a href="/customtext">Custom Text</a>
        </div>
        <div class="nav_item">
          <a href="/contact">Contact</a>
        </div>
        <div class="nav_item">
          <a href="/test/feed">RSS feed</a>
        </div>
        <div class="nav_item">
          <a href="/grunt-includes">Grunt Includes</a>
        </div>
      </nav>
      <section id="content-holder"><?php echo $response->view->html(); // insert ajax content on first page load ?></section>
      <footer id="content-footer">Copyright &copy; simplesite <?php echo date("Y"); ?></footer>
    </div>
    <div class="loader"></div>
    <div id="localhost-status"></div>

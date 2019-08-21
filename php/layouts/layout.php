
    <div id="main">
      <header><h1>SimpleSite</h1></header>
      <nav id="main-nav" class="grid-container quarters">
        <a class="button" href="/">Home</a>
        <a class="button" href="/collection">Collection</a>
        <a class="button" href="/about">About</a>
        <a class="button" href="/events">Events</a>
        <a class="button" href="/schedule">Schedule</a>
        <a class="button" href="/count">Count</a>
        <a class="button" href="/mentors">Mentors</a>
        <a class="button" href="/customtext">Custom Text</a>
        <a class="button" href="/contact">Contact</a>
        <a class="button" href="/test/feed">RSS feed</a>
        <a class="button" href="/grunt-includes">Grunt Includes</a>
      </nav>
      <section id="content-holder"><?php echo $response->view->html(); // insert ajax content on first page load ?></section>
      <footer id="content-footer">Copyright &copy; simplesite <?php echo date("Y"); ?></footer>
    </div>
    <div class="loader"></div>
    <button class="dark-theme-toggle"></button>
    <div id="localhost-status"></div>

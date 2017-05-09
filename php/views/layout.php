
    <div id="main">
      <header>SimpleSite</header>
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
          <a href="/contact">Contact</a>
        </div>
        <div class="nav_item">
          <a href="/test/feed">RSS feed</a>
        </div>
      </nav>
      <section id="content-holder"><?php echo $response->view->html(); // insert ajax content on first page load ?></section>
      <footer id="content-footer">Copyright &copy; simplesite <?php echo date("Y"); ?></footer>
    </div>

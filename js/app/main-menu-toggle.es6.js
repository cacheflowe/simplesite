class MainMenuToggle {

  constructor(el) {
    _store.addListener(this, SimpleSite.CUR_PATH);
    this.buildContentBlocker();
    this.el = document.querySelector('#main-nav-toggle');
    this.el.addEventListener('click', (e) => this.toggleClicked(e));
    this.contentBlocker.addEventListener('click', (e) => this.toggleClicked(e));
    window.addEventListener('resize', (e) => this.windowResized(e));
  }

  buildContentBlocker() {
    this.contentBlocker = document.createElement('div');
    this.contentBlocker.setAttribute('id', 'content-blocker');
    document.body.appendChild(this.contentBlocker);
  }

  toggleClicked(e) {
    document.body.classList.toggle('main-nav-drawer-open');
  }

  windowResized(e) {
    // clear out drawer open when larger than collapsed browser size
    if (window.innerWidth > 767 && document.body.classList.contains('main-nav-drawer-open')) {
      document.body.classList.remove('main-nav-drawer-open');
    }
  }

  CUR_PATH(val) {
    // hide menu upon navigation
    document.body.classList.remove('main-nav-drawer-open');
  }
}

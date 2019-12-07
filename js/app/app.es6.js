class SimpleSite {

  constructor() {
    DOMUtil.addLoadedClass();
    // if(!this.hasWebGL()) window.location.href = '/help';
    requestAnimationFrame(() => this.init());
  }

  hasWebGL() {
    var canvas = document.createElement( 'canvas' ); return !! ( window.WebGLRenderingContext && ( canvas.getContext( 'webgl' ) || canvas.getContext( 'experimental-webgl' ) ) );
  }

  init() {
    this.appStore = new AppStore();
    this.appStore.addListener(this);
    this.tracking = new Tracking();
    this.initRoutes();
    this.initLocalhost();
    this.initExtras();
  }

  initLocalhost() {
    if(window.location.href.match('localhost')) {
      document.body.classList.add('localhost');
    }
  }

  storeUpdated(key, value) {
    if(key == SimpleSite.SET_CUR_PATH) page(value);
    if(key == SimpleSite.RELOAD_VIEW) _store.set(SimpleSite.SET_CUR_PATH, _store.get(SimpleSite.CUR_PATH));
    if(key == SimpleSite.LOADER_SHOW && value == true) document.body.classList.add('data-loading');
    if(key == SimpleSite.LOADER_SHOW && value == false) document.body.classList.remove('data-loading');
    if(key == SimpleSite.ALERT_ERROR) this.notyf.error(value);
    if(key == SimpleSite.ALERT_SUCCESS) this.notyf.success(value);
  }

  initRoutes() {
    this.areaModel = new AreaModel((index) => {
      page('', index);
      page('/', index);
      page('/:section', index);
      page('/:section/:id', index);
      page('/:section/:id/:params', index);
      // page('*', notfound);
      page();
    }, (curPath) => {
      _store.set(SimpleSite.CUR_PATH, curPath);
    }, 'BaseView', false, true);
  }

  // extras

  initExtras() {
    this.initToasts();
    this.initDarkThemeToggle();
    this.mainMenuToggle = new MainMenuToggle();
  }

  initToasts() {
    this.notyf = new Notyf({duration:5000});
  }

  initDarkThemeToggle() {
    document.body.addEventListener('click', (e) => {
      if(e.target && e.target.classList.contains('dark-theme-toggle')) {
        // set transition class
        document.documentElement.classList.add('transition-theme');
        // do the toggle and store it for later
        if(document.documentElement.hasAttribute("data-theme")) {
          requestAnimationFrame(() => document.documentElement.removeAttribute("data-theme"));
          window.localStorage.removeItem('dark-theme');
        } else {
          requestAnimationFrame(() => document.documentElement.setAttribute("data-theme", "dark"));
          window.localStorage.setItem('dark-theme', 'true');
        }
      }
    });
    // set initial state on window load
    if(window.localStorage.getItem('dark-theme') == 'true') document.documentElement.setAttribute("data-theme", "dark");
  }

}

SimpleSite.CUR_PATH = 'CUR_PATH';
SimpleSite.SET_CUR_PATH = 'SET_CUR_PATH';
SimpleSite.RELOAD_VIEW = 'RELOAD_VIEW';
SimpleSite.LOADER_SHOW = 'LOADER_SHOW';
SimpleSite.ALERT_SUCCESS = 'ALERT_SUCCESS';
SimpleSite.ALERT_ERROR = 'ALERT_ERROR';

window.simplesite = new SimpleSite();

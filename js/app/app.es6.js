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
  }

  initLocalhost() {
    if(window.location.href.match('localhost')) {
      document.body.classList.add('localhost');
    }
  }

  storeUpdated(key, value) {
    if(key == SimpleSite.SET_CUR_PATH) page(value);
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
}

SimpleSite.CUR_PATH = 'CUR_PATH';
SimpleSite.SET_CUR_PATH = 'SET_CUR_PATH';

window.simplesite = new SimpleSite();

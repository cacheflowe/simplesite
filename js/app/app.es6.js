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
    // this.stayAwake = new StayAwake();
    this.initRoutes();
    this.initNav();
  }

  initNav() {

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
      _store.set(SimpleSite.URL_UPDATED, curPath);
    }, 'BaseView');
  }
}

SimpleSite.URL_UPDATED = 'URL_UPDATED';
SimpleSite.SET_CUR_PATH = 'SET_CUR_PATH';

window.simplesite = new SimpleSite();

class OHY {

  constructor() {
    this.initRoutes();
    this.initNav();
  }

  initNav() {

  }

  initRoutes() {
    this.areaModel = new AreaModel(function(index) {
      page('', index);
      page('/', index);
      page('/:section', index);
      page('/:section/:id', index);
      page('/:section/:id/:params', index);
      page('/music', function(){ page.redirect('/music/discography'); });
      // page('*', notfound);
      page();
    });
  }
}

window.ohy = new OHY();

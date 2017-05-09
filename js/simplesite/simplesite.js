
var Site = function( data ) {
  var areaModel;

  var init = function() {
    initSite();
    document.addEventListener('touchstart', function(e){});
  };


  var initSite = function() {
    var delegate = {
      hashChanged: function( path ) {
        // show loader?
        if(path === '/') path = '/home';
        var pathComponents = path.substr(1).split('/');
        var section = pathComponents[0];
        var subSection = pathComponents[1];
        // setActiveMainNavButton('/'+section);
        // setActiveSubNav('/'+section, path);
      }
    };

    // init model
    var initRoutes = function(index) {
      page('', index);
      page('/', index);
      page('/home', index);
      page('/home/:id', index);
      page('/home/:section', index);
      page('/home/:section/:id', index);
      page('/about', index);
      page('/contact', index);
      page('/store', index);
      page('/store/:id', index);
      page('/collection', index);
      page('/collection/:id', index);
      page('/music', function(){ page.redirect('/music/discography'); });
      page('/music/:id', index);
      page('/music/:section', index);
      page('/music/:section/:id', index);
      page('/code', function(){ page.redirect('/code/installation'); });
      page('/code/:id', index);
      page('/code/:section', index);
      page('/code/:section/:id', index);
      page('/art', function(){ page.redirect('/art/physical'); });
      page('/art/:id', index);
      page('/art/:section', index);
      page('/art/:section/:id', index);
      // page('/video', index);
      // page('/about', function(){ page.redirect('/about/bio'); });
      // page('/about/:section', index);
      // page('*', notfound);
      page();
    };
    areaModel = new AreaModel(delegate, initRoutes);
  };

  var setActiveMainNavButton = function(section) {
    // add body class for section
    document.body.classList.remove('section-news', 'section-music', 'section-code', 'section-art', 'section-store', 'section-about', 'section-contact');
    document.body.classList.add('section-'+section.replace('/', ''));
  };

  init();

};
window.site = new Site();

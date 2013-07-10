
var routes = {
  "": "page",
  "/": "page",
  "collection/:index": "page",
  "*notFound": "page"
};

var delegate = {
  hashChanged: function( curPath, index ) {
    // show loader?
    console.log('hashChanged', curPath, index);
  },
  contentLoaded: function( path, node ) {
    // updateNavs( path );
    console.log('contentLoaded',path,node);
  }
};

// init model
_areaModel = new AreaModel( routes, delegate );
_areaModel.rewriteLinksForAjax( $(document.body) )


/*
// init main nav links
rewriteLinksForAjax( $('#main_nav') );
rewriteLinksForAjax( $('#nav') );
rewriteLinksForAjax( $('#main-nav') );



var setActiveNavButton = function() {
  // get cur path to compare to - bail on tier 3+ path elements
  var pathSplit = _prevPath.split('/');
  while( pathSplit.length > 3 ) pathSplit.pop();
  var curPath = pathSplit.join('/');
  
  
  // update 'active' main nav button
  for (var i=0; i < _main_nav_links.length; i++) {
    checkNavButton( curPath, _main_nav_links[i] );
  }
  for (var i=0; i < _gallery_links.length; i++) {
    checkNavButton( curPath, _gallery_links[i] );
  }
};

var checkNavButton = function( curPath, buttonObj ) {
  // grab url/path from <a> 
  var aLink = $( buttonObj ).find('a')[0];
  var hrefPath = aLink.href.split('#')[1];
  
  // get rid of anything after the 2nd tier
  var pathSplit = hrefPath.split('/');
  while( pathSplit.length > 3 ) pathSplit.pop();
  hrefPath = pathSplit.join('/');
  
  // if( _prevPath.indexOf( hrefPath ) !== -1 ) {
  if( curPath == hrefPath ) {
    $( buttonObj ).addClass('active');
  } else {
    $( buttonObj ).removeClass('active');
  }
};

  var applyAreaFlags = function( outerNode ){ 
    if( outerNode ) {
      // // pass on any flags to persistent objects
      // var hidesMainNav = ( outerNode.getAttribute('data-hides-main-nav') == 'true' );
      // _main_nav.swfAddressChanged( hidesMainNav );
      // var hidesLogo = ( outerNode.getAttribute('data-hides-logo') == 'true' );
      // _logo_header.swfAddressChanged( hidesLogo );
      // var showsDimmer = ( outerNode.getAttribute('data-shows-dimmer') == 'true' );
      // _dimmer.swfAddressChanged( showsDimmer );
      // var doesntReloadBg = ( outerNode.getAttribute('data-no-bg-reload') == 'true' );
      // var hasSpecificBg = outerNode.getAttribute('data-bg-img') || null;
      // _app_background.swfAddressChanged( doesntReloadBg, hasSpecificBg );
    }
  };

*/
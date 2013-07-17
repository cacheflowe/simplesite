/*
* make art scroller work
  - have it resize with browser
  - resize crop images when they load for original ratio at a standard height
* image detail pages should scale to site width, with zoom button to go larger
* make homepage fancy rollovers for top category nav?
* make mobile layout css ->
* fix up iphone formatting - nav is wrapping - entire design could be responsive
* get build scripts working to export minified and concatenated .js and css
* next/prev thumbs and back button on image detail pages
* add descriptions to Flickr from mom's texts
* get the blog going
*/

var AreaModel = function( routes, delegate ) {
  var FADE_OUT_TIME = 350;
  var FADE_IN_TIME = 250;
  var _contentEl = null;
  var _curAreaObj = null;
  var _prevPath = null;
  var _curPath = null;
  var _pageTitle = document.title; // grab original HTML title to prepend page updates
  var _isTransitioning = false;
  var _queuedPath = null;
  var _appRouter = null;
  var _delegate = delegate || function(){};

  var init = function() {
    // grab main containers
    _contentEl = $('#content_holder')[0];

    // initialize section that loaded with the page and track our initial path
    _prevPath = document.location.href.replace(document.location.origin,'');
    createMainContentObj( _contentEl.children[0], false );
    
    // init backbone for hash changes
    var AppRouter = Backbone.Router.extend({
      routes: routes,
      page: function( index ) {
        hashChanged( '/' + Backbone.history.fragment, index );
      }
    });
    _appRouter = new AppRouter();
    if( window.history.pushState ) Backbone.history.start({pushState: true});
  };
    
  var hashChanged = function( newPath, index ) {
    _curPath = newPath;
    _delegate.hashChanged( _curPath, index );
    if( !_isTransitioning ) {
      // leave section if path changed
      if( _curPath != _prevPath ) {
        exitCurSection();
        document.title = formatDocumentTitle( _curPath );
      }
    } else {
      _queuedPath = newPath;
    }
  };

  var exitCurSection = function() {
    _isTransitioning = true;
    if( $( _contentEl ).children().length > 0 ) {
      $( _contentEl ).children().first().animate({ opacity: 0 }, FADE_OUT_TIME, contentHidden);
    } else {
      contentHidden();
    }
  };
  
  var contentHidden = function(){
    // dispose previous area object
    if( _curAreaObj != null ) _curAreaObj.dispose();
    _curAreaObj = null;
    // load new areas, since all previous are cleared out now
    loadAjaxContent( _curPath );
  };
  
  var loadAjaxContent = function( path ){
    // strip tail slash if there is one
    if( path.length > 1 && path[ path.length - 1 ] == '/' ) path = path.substr( 0, path.length - 1 );
    if( path == '/' ) path = '/home'; // root of site doesn't like being requested as ajax
    // get area html path based on section 
    $.ajax({
      url: path,
      cache: false,
      success: function( data ){
        createMainContentObj( data, true );
        showAjaxContent();
      }
    });
  };
  
  var createMainContentObj = function( data, replaceContent ){
    // read area type from data attribute of first element
    var outerNode = $(data)[0];
    var pageType = outerNode.getAttribute('data-area-type');
    _delegate.preprocessHtml(outerNode);
    // set content, rewrite links, and hide it
    if( replaceContent == true ) {
      _contentEl.innerHTML = data;
      $( _contentEl ).children().first().css({ opacity: 0 });
    }
    rewriteLinksForAjax( $( _contentEl ) );
    
    // create area object if there is one
    if ( window[pageType] ) _curAreaObj = new window[pageType]( _contentEl );
  };
  
  var rewriteLinksForAjax = function( obj ) {
    if( window.history.pushState ) {
      var links = obj.find('a');
      // rewrite links to be ajax style
      for(var i=0; i < links.length; i++){
        (function(){
          var path = links[i].href.replace( document.location.origin, '' );
          links[i].onclick = function(e){ 
            e.preventDefault(); 
            _appRouter.navigate( path, { trigger: true }); 
            return false;
          };
        })();
      }
    } 
  };

  var showAjaxContent = function(){
    // fade it in
    $( _contentEl ).children().first().animate({ opacity: 1 }, FADE_IN_TIME);
    // store previous paths, set flags
    _prevPath = _curPath;
    _isTransitioning = false;
    // check to see if the path has changed during destroy/rebuild
    // TODO: reimplement this
    if( _queuedPath ) {
      hashChanged( _queuedPath );
      _queuedPath = null;
    }
    // tell the delegate
    _delegate.contentLoaded( _curPath, $( _contentEl ).children().first() );
    // track it
    setTimeout(function(){
      // window.tracking.trackPage();
    }, 200);

  };
  
  var toTitleCase = function(str) {
    return str.substr(0,1).toUpperCase() + str.substr(1).toLowerCase();
  };

  var formatDocumentTitle = function(title) {
    // title = title.replace(/-/g, ' ');
    var titleParts = title.split('/');
    for(var i=0; i < titleParts.length; i++) {
      var subParts = titleParts[i].split('-');
      for(var j=0; j < subParts.length; j++) {
        subParts[j] = toTitleCase( subParts[j] );
      }
      titleParts[i] = subParts.join(' ');
    }
    if( title != '/' )
      return _pageTitle + ' ' + titleParts.join(' / ');
    else
      return _pageTitle;
  };

  init();

  return {
    rewriteLinksForAjax: rewriteLinksForAjax
  }
};

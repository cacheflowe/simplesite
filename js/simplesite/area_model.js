
var AreaModel = function(delegate, initRoutes) {
  var _contentEl = null;
  var _curAreaObj = null;
  var _prevPath = null;
  var _curPath = null;
  var _pageTitle = document.title.split('/')[0]; // grab original HTML title to prepend page updates
  var _isTransitioning = false;
  var _queuedPath = null;
  var _appRouter = null;
  var _delegate = delegate || function(){};
  var cachedResponses = {};

  var init = function() {
    // grab main containers
    _contentEl = document.getElementById('content-holder');

    // initialize section that loaded with the page and track our initial path
    _prevPath = document.location.href.replace(document.location.origin,'');
    createMainContentObj( _contentEl.children[0], false );

    // init page.js
    function index() {
      var newPath = page.current.replace('%20', "+"); // replace() fixes "+" getting munged by page.js
      hashChanged(newPath);
    }
    function notfound() {
    }
    initRoutes(index);
  };

  var hashChanged = function( newPath, index ) {
    _curPath = newPath;
    _delegate.hashChanged( _curPath );
    if( !_isTransitioning ) {
      // leave section if path changed
      if( _curPath !== _prevPath ) {
        exitCurSection();
        document.title = formatDocumentTitle( _curPath );
      }
    } else {
      _queuedPath = newPath;
    }
  };

  var exitCurSection = function() {
    _isTransitioning = true;
    if(_contentEl.children.length > 0 ) {
      _contentEl.classList.add('hiding');
      setTimeout(function(){
        contentHidden();
      },300);
      document.body.classList.add('loading');
      setTimeout(function(){
        document.body.classList.remove('loading');
      },1100);
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
    if(typeof cachedResponses[path] === "undefined") {
      fetchPage(path);
    } else {
      sectionDataLoaded(cachedResponses[path], path);
    }
  };

  var fetchPage = function(path) {
    fetch(path, {method: "POST", body: {}})
      .then(function(response) {
        return response.text();
      }).then((data) => {
        sectionDataLoaded(data, path);
      }).catch(function(ex) {
        console.warn('Fetch failed', ex);
      });
  };

  var sectionDataLoaded = function(data, path) {
    cachedResponses[path] = data;
    createMainContentObj( data, true );
    showNewContent();
  };

  var createMainContentObj = function( data, replaceContent ) {
    // read area type from data attribute of first element
    var newContentEl;
    if(typeof data == "string") {
      newContentEl = stringToDomElement(data);  // transform from string
    } else {
      newContentEl = data;  // read initial page html from dom
    }
    var pageType = newContentEl.getAttribute('data-area-type') || 'AreaCommon';

    // set content, rewrite links, and hide it
    if( replaceContent == true ) {
      _contentEl.innerHTML = data;
    }

    // create area object if there is one
    var isInitialLoad = !replaceContent;
    if ( window[pageType] ) _curAreaObj = new window[pageType]( _contentEl, isInitialLoad );
  };

  var stringToDomElement = function(str) {
    var div = document.createElement('div');
    div.innerHTML = str;
    return div.firstChild;
  };

  var showNewContent = function(){
    // fade it in
    _contentEl.classList.remove('hiding');
    window.scrollTo(0,0);
    // store previous paths, set flags
    _prevPath = _curPath;
    _isTransitioning = false;
    // check to see if the path has changed during destroy/rebuild
    // TODO: reimplement this
    if( _queuedPath ) {
      hashChanged( _queuedPath );
      _queuedPath = null;
    }
    // track it
    setTimeout(function(){
      // window.tracking.page([_curPath]);
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

  return {}
};


/*

class AreaModel
  constructor: (contentEl) ->
    @contentEl = contentEl
    @pageTitle = document.title.split(' | ')[0]
    @curPath = null
    @queuedPath = null
    @curAreaObj = null
    @isTransitioning = false
    @initFirstSection()
    @initRoutes()


  index: =>
    if !@isTransitioning
      @curPath = page.current
      # exit section if path changed
      if @curPath != @prevPath
        document.body.classList.remove(@pathToClass(@prevPath)) if @prevPath.length > 1
        document.body.classList.add(@pathToClass(@curPath)) if @curPath.length > 1 # protect against '/' path
        easyScroll.scrollByY(600, easyScroll.scrollY()) if easyScroll.scrollY() > 20
        @exitCurSection()
        document.title = @formatDocumentTitle()
    else
      @queuedPath = page.current


  initRoutes: ->
    page('', @index)
    page('/', @index)
    page('/:id', @index)
    # page('/news/:id', index)
    # page('*', notfound)
    page()


  initFirstSection: ->
    # initialize section that loaded with the page and track our initial path
    @prevPath = document.location.href.replace(document.location.origin,'')
    @createMainContentObj(@contentEl.children[0], false)


  exitCurSection: ->
    @isTransitioning = true
    if @contentEl.children.length > 0
      @contentEl.classList.add('hiding')
      setTimeout =>
        @contentHidden()
      , 300
    else
      @contentHidden()


  contentHidden: ->
    # dispose previous area object
    @curAreaObj?.dispose()
    @curAreaObj = null
    # load new area, since all previous are cleared out now
    @loadAjaxContent( @curPath )


  loadAjaxContent: (path) ->
    # strip tail slash if there is one
    if path.length > 1 && path[ path.length - 1 ] == '/'
      path = path.substr( 0, path.length - 1 )
    # get area html path based on section
    window.reqwest
      url: path,
      success: (data) =>
        @createMainContentObj(data, true)
        @showNewContent()


  createMainContentObj: (data, replaceContent) ->
    # read area type from data attribute of first element
    newContentEl
    if typeof data == "string"
      newContentEl = @stringToDomElement(data)  # transform from string
    else
      newContentEl = data  # read initial page html from dom

    pageType = newContentEl.getAttribute('data-area-type')
    @contentEl.innerHTML = data if(replaceContent == true)

    # create area object if there is one
    isInitialLoad = !replaceContent
    @curAreaObj = new ViewCommon( @contentEl, isInitialLoad )


  showNewContent: ->
    # fade it in
    @contentEl.classList.remove('hiding')
    # window.scrollTo(0,0)
    # store previous paths, set flags
    @prevPath = @curPath
    @isTransitioning = false
    # check to see if the path has changed during destroy/rebuild
    if @queuedPath
      @queuedPath = null
      @index()
    # track it
    setTimeout =>
      ga 'send',
        hitType: 'pageview'
        page: location.pathname
    , 200


  stringToDomElement: (str) ->
    div = document.createElement('div')
    div.innerHTML = str
    return div.children[0]


  formatDocumentTitle: ->
    titleParts = @curPath.split('/')
    i = 0
    while i < titleParts.length
      subParts = titleParts[i].split('-')
      j = 0
      while j < subParts.length
        subParts[j] = @toTitleCase(subParts[j])
        j++
      titleParts[i] = subParts.join(' ')
      i++
    if( @curPath != '/' )
      return @pageTitle + ' ' + titleParts.join(' | ')
    else
      return @pageTitle


  toTitleCase: (str) ->
    return str.substr(0,1).toUpperCase() + str.substr(1).toLowerCase()


  pathToClass: (path) ->
    path = path.substr(1) if path.indexOf('/') == 0
    return path


window.AreaModel = AreaModel

*/

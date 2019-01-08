class AreaModel {
  constructor(initRoutes, pathUpdatedCallback, defaultViewClass, cacheResponses) {
    this.contentEl = document.getElementById('content-holder');
    this.pageTitleBase = document.title.split(' | ')[0];
    this.curPath = null;
    this.prevPath = null;
    this.queuedPath = null;
    this.curAreaObj = null;
    this.cachedResponses = (cacheResponses) ? {} : null;
    this.isTransitioning = false;
    this.easyScroll = new EasyScroll();
    this.pathUpdatedCallback = pathUpdatedCallback;
    this.defaultViewClass = defaultViewClass || 'BaseView';
    initRoutes(this.index.bind(this));
  }

  index() {
    if (!this.isTransitioning) {
      this.curPath = page.current;
      if (this.curPath == '' || this.curPath == '/') this.curPath = '/home';
      if (this.curPath !== this.prevPath) {
        if(this.prevPath == null) this.updateSectionCssClass();
        if (window.scrollY > 20) {
          this.easyScroll.scrollByY(300, window.scrollY);
        }
        this.exitCurSection();
      }
    } else {
      this.queuedPath = page.current;
    }
  }

  exitCurSection() {
    if(this.prevPath == null) { // first load!
      this.sectionDataLoaded(this.contentEl.innerHTML, this.curPath);
    } else {
      this.isTransitioning = true;
      if (this.contentEl.children.length > 0) {
        document.body.classList.add('page-loading');
        return setTimeout(() => this.contentHidden(), 300);
      } else {
        return this.contentHidden();
      }
    }
  }

  contentHidden() {
    if (this.curAreaObj != null) {
      this.curAreaObj.dispose();
    }
    this.curAreaObj = null;
    return this.loadAjaxContent(this.curPath);
  }

  loadAjaxContent(path) {
    if (path.length > 1 && path[path.length - 1] === '/') {
      path = path.substr(0, path.length - 1);
    }

    // get area html path based on section
    if(this.cachedResponses && typeof this.cachedResponses[path] !== "undefined") {
      this.sectionDataLoaded(this.cachedResponses[path], path);
    } else {
      this.fetchPage(path);
    }
  }

  fetchPage(path) {
    fetch(path, {method: "POST"})
      .then(function(response) {
        return response.text();
      }).then((data) => {
        this.sectionDataLoaded(data, path);
      }).catch(function(ex) {
        console.warn('Fetch failed', ex);
      });
  }

  sectionDataLoaded(data, path) {
    if(this.cachedResponses) this.cachedResponses[path] = data;
    this.createMainContentObj(data);
    this.showNewContent();
  }

  createMainContentObj(data) {
    // create new content element
    var newContentEl;
    if (typeof data === "string") {
      newContentEl = this.stringToDomElement(data);
    } else {
      newContentEl = data;
    }
    // update <title>
    let pageTitle = newContentEl.getAttribute('data-page-title') || null;
    this.formatDocumentTitle(pageTitle);
    // create page object
    let pageType = newContentEl.getAttribute('data-view-type') || this.defaultViewClass;
    if(this.prevPath != null) this.contentEl.innerHTML = data;  // don't replace initial html
    this.curAreaObj = new window[pageType](this.contentEl);
  }

  showNewContent() {
    document.body.classList.remove('page-loading');
    if (this.curPath !== this.prevPath) this.updateSectionCssClass();
    this.prevPath = this.curPath;
    this.isTransitioning = false;
    if (this.queuedPath) {
      this.queuedPath = null;
      this.index();
    }
    if(this.pathUpdatedCallback) this.pathUpdatedCallback(this.curPath);
    // return setTimeout(((_this => () => ga('send', {
    //   hitType: 'pageview',
    //   page: location.pathname
    // })))(this), 200);
  }

  updateSectionCssClass() {
    if (this.prevPath && this.prevPath.length > 1) {
      let pathSplit = this.prevPath.substr(1).split('/');
      if(pathSplit.length > 0) document.body.classList.remove('section-' + pathSplit[0]);
      if(pathSplit.length > 1) document.body.classList.remove('subsection-' + pathSplit[1]);
    }
    if (this.curPath.length > 1) {
      let pathSplit = this.curPath.substr(1).split('/');
      if(pathSplit.length > 0) document.body.classList.add('section-' + pathSplit[0]);
      if(pathSplit.length > 1) document.body.classList.add('subsection-' + pathSplit[1]);
    }
  }

  stringToDomElement(str) {
    let div = document.createElement('div');
    div.innerHTML = str;
    return div.children[0];
  }

  formatDocumentTitle(newTitle) {
    document.title = (newTitle != null) ? newTitle : this.pageTitleBase;
  }

  toTitleCase(str) {
    return str.substr(0, 1).toUpperCase() + str.substr(1).toLowerCase();
  }

}

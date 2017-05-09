class AreaModel {
  constructor(initRoutes) {
    this.contentEl = document.getElementById('content-holder');
    this.pageTitle = document.title.split(' | ')[0];
    this.curPath = null;
    this.queuedPath = null;
    this.curAreaObj = null;
    this.cachedResponses = {};
    this.isTransitioning = false;
    this.easyScroll = new EasyScroll();
    this.initFirstSection();
    initRoutes(this.index.bind(this));
  }

  index() {
    if (!this.isTransitioning) {
      this.curPath = page.current;
      if (this.curPath !== this.prevPath) {
        if (this.prevPath.length > 1) {
          document.body.classList.remove(this.pathToClass(this.prevPath));
        }
        if (this.curPath.length > 1) {
          document.body.classList.add(this.pathToClass(this.curPath));
        }
        if (window.scrollY > 20) {
          this.easyScroll.scrollByY(300, window.scrollY);
        }
        this.exitCurSection();
        return document.title = this.formatDocumentTitle();
      }
    } else {
      return this.queuedPath = page.current;
    }
  }

  initFirstSection() {
    this.prevPath = document.location.href.replace(document.location.origin, '');
    return this.createMainContentObj(this.contentEl.children[0], false);
  }

  exitCurSection() {
    this.isTransitioning = true;
    if (this.contentEl.children.length > 0) {
      document.body.classList.add('hiding-content');
      return setTimeout(() => this.contentHidden(), 300);
    } else {
      return this.contentHidden();
    }
  }

  contentHidden() {
    let ref;
    if ((ref = this.curAreaObj) != null) {
      ref.dispose();
    }
    this.curAreaObj = null;
    return this.loadAjaxContent(this.curPath);
  }

  loadAjaxContent(path) {
    if (path.length > 1 && path[path.length - 1] === '/') {
      path = path.substr(0, path.length - 1);
    }

    // get area html path based on section
    if(typeof this.cachedResponses[path] !== "undefined") {
      this.sectionDataLoaded(this.cachedResponses[path], path);
    } else {
      this.fetchPage(path);
    }
  }

  fetchPage(path) {
    fetch(path, {method: "POST", body: {}})
      .then(function(response) {
        return response.text();
      }).then((data) => {
        this.sectionDataLoaded(data, path);
      }).catch(function(ex) {
        console.warn('Fetch failed', ex);
      });
  }

  sectionDataLoaded(data, path) {
    this.cachedResponses[path] = data;
    this.createMainContentObj( data, true );
    this.showNewContent();
  }

  createMainContentObj(data, replaceContent) {
    var newContentEl;
    if (typeof data === "string") {
      newContentEl = this.stringToDomElement(data);
    } else {
      newContentEl = data;
    }
    let pageType = newContentEl.getAttribute('data-view-type') || 'BaseView';
    if (replaceContent === true) {
      this.contentEl.innerHTML = data;
    }
    this.curAreaObj = new window[pageType](this.contentEl);
  }

  showNewContent() {
    document.body.classList.remove('hiding-content');
    this.prevPath = this.curPath;
    this.isTransitioning = false;
    if (this.queuedPath) {
      this.queuedPath = null;
      this.index();
    }
    // return setTimeout(((_this => () => ga('send', {
    //   hitType: 'pageview',
    //   page: location.pathname
    // })))(this), 200);
  }

  stringToDomElement(str) {
    let div = document.createElement('div');
    div.innerHTML = str;
    return div.children[0];
  }

  formatDocumentTitle() {
    let titleParts = this.curPath.split('/');
    let i = 0;
    while (i < titleParts.length) {
      let subParts = titleParts[i].split('-');
      let j = 0;
      while (j < subParts.length) {
        subParts[j] = this.toTitleCase(subParts[j]);
        j++;
      }
      titleParts[i] = subParts.join(' ');
      i++;
    }
    if (this.curPath !== '/') {
      return `${this.pageTitle} ${titleParts.join(' | ')}`;
    } else {
      return this.pageTitle;
    }
  }

  toTitleCase(str) {
    return str.substr(0, 1).toUpperCase() + str.substr(1).toLowerCase();
  }

  pathToClass(path) {
    if (path.indexOf('/') === 0) {
      path = path.substr(1);
    }
    return path;
  }
}

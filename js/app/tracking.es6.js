class Tracking {

  constructor() {
    this.debug = true;
    _store.addListener(this);
    if(window.ga === undefined) window.ga = () => {};
  }

  event(category='test', action='click', label='', value=null) {
    // More info: https://developers.google.com/analytics/devguides/collection/analyticsjs/events
    window.ga('send', 'event', category, action, label, value);
    if(this.debug) console.log('Tracking.event()', category, action, label, value);
  }

  page(path=document.location.pathname) {
    // More info: https://developers.google.com/analytics/devguides/collection/analyticsjs/pages
    // More info: https://developers.google.com/analytics/devguides/collection/analyticsjs/single-page-applications
    window.ga('set', 'page', path); // sets the page for a single-page app, so subsequent events are tracked to this page
    window.ga('send', 'pageview');
    if(this.debug) console.log('Tracking.page()', path);
  }

  storeUpdated(key, value) {
    if(key == SimpleSite.CUR_PATH) this.page(_store.get(SimpleSite.CUR_PATH));
  }

}

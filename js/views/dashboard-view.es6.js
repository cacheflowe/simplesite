class DashboardView extends BaseSiteView {

  constructor(el) {
    super(el);
    this.setScroll();
    this.interval = setInterval(() => this.refresh(), 30 * 1000);
	}

  setScroll() {
    let disposeTime = _store.get(DashboardView.DISPOSE_TIME);
    if(disposeTime && Date.now() - disposeTime < 2000) {
      document.body.scrollTop = _store.get(DashboardView.SCROLL_TOP);
    }
  }

  refresh() {
    // reload current page on interval
    _store.set(SimpleSite.RELOAD_VIEW, true);
    // store current scroll position
    _store.set(DashboardView.SCROLL_TOP, document.body.scrollTop);
    _store.set(DashboardView.DISPOSE_TIME, Date.now());
  }

  dispose() {
    super.dispose();
    window.clearInterval(this.interval);
	}

}

DashboardView.SCROLL_TOP = "SCROLL_TOP";
DashboardView.DISPOSE_TIME = "DISPOSE_TIME";

window.DashboardView = DashboardView;

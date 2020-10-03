class BaseView {

  constructor(el) {
    this.el = el;
    this.addClickHandling();
	}

  addClickHandling() {
    this.viewClickHandler = this.viewClicked.bind(this);
    this.el.addEventListener('click', this.viewClickHandler);
  }

  viewClicked(e) {
    // must override
  }

  dispose() {
    this.el = null;
    this.el.removeEventListener('click', this.viewClickHandler);
  }

}

window.BaseView = BaseView;

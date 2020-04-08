class BaseView {

  constructor(el) {
    this.el = el;
	}

  dispose() {
    this.el = null;
  }

}

window.BaseView = BaseView;

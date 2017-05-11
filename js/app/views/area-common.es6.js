class AreaCommon extends BaseView {

  constructor(el) {
    super(el);
    this.initNav();

  }

  initNav() {
    this.clickFunction = this.navClicked.bind(this);
    this.navEl = document.getElementById('main-nav');
    this.navEl.addEventListener('click', this.clickFunction);
  }

  navClicked(e) {
    // e.preventDefault();
    // if(e.target.nodeName.toLowerCase() == 'a') {
    //   let sectionId = e.target.getAttribute('href').split('#')[1];
    //   let targetEl = document.querySelector('a[name="'+sectionId+'"]');
    //   let time = Math.abs(targetEl.getBoundingClientRect().top) * 0.4;
    //   ohy.areaModel.easyScroll.scrollToEl(time, targetEl, 100);
    // }
  }

  dispose() {
    super.dispose();
    this.navEl.removeEventListener('click', this.clickFunction);
  }
}

window.AreaCommon = AreaCommon;

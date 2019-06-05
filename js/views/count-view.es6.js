class CountView extends FancyView {

  constructor(el) {
    super(el);
    this.initCountForm();
	}

  initCountForm() {
    this.countFormHandler = this.countFormClicked.bind(this);
    this.el.addEventListener('click', this.countFormHandler);
  }

  countFormClicked(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'button') {
      if(e.target.hasAttribute('data-count-add')) {
        e.preventDefault();
        // show loader
        document.body.classList.add('data-loading');
        let countEl = this.el.querySelector('#count');
        let addCount = e.target.getAttribute('data-count-add');

        fetch('/count/add/'+addCount, {
          method: 'get'
          // body: {}
        })
          .then(function(response) {
            return response.text();
          }).then((data) => {
            // set number and animate
            data = JSON.parse(data);
            let newCount = data.count;
            countEl.innerHTML = newCount;
            countEl.classList.remove('updated');
            setTimeout(() => countEl.classList.add('updated'), 10);
            // hide loader
            document.body.classList.remove('data-loading');
          }).catch(function(ex) {
            formResult.innerHTML = '<span style="color:red;">Fetch upload failed</span>';
            document.body.classList.remove('data-loading');
          });

      }
    }
  }

  dispose() {
    super.dispose();
    this.el.removeEventListener('click', this.countFormHandler);
	}

}

window.CountView = CountView;

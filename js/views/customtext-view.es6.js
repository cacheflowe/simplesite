class CustomText extends BaseSiteView {

  constructor(el) {
    super(el);
    this.initForm();
	}

  initForm() {
    this.form = this.el.querySelector('form');
    this.customtextFormHandler = this.customtextFormClicked.bind(this);
    this.el.addEventListener('click', this.customtextFormHandler);
  }
  customtextFormClicked(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'button') {
      if(e.target.hasAttribute('data-customtext-submit')) {
        e.preventDefault();
        document.body.classList.add('data-loading');
        // line 1
        let customtextEl = this.el.querySelector('#customtext');
        let inputText = customtextEl.value.trim().replace(/'/g, "’");
        let submitVal = (inputText.length > 0) ? inputText : null;
        // line 2
        let customtextEl2 = this.el.querySelector('#customtext-line-2');
        let inputText2 = customtextEl2.value.trim().replace(/'/g, "’");
        let submitVal2 = (inputText2.length > 0) ? inputText2 : null;

        // add clear button check
        if(e.target.hasAttribute('data-customtext-clear')) {
          customtextEl.value = '';
          customtextEl2.value = '';
          submitVal = null;
          submitVal2 = null;
        }

        // send request
        fetch(this.form.getAttribute('action'), {
          method: 'POST',
          body: JSON.stringify({"customtext": submitVal, "customtext-line-2": submitVal2})
        })
          .then(function(response) {
            return response.text();
          }).then((data) => {
            data = JSON.parse(data);
            document.body.classList.remove('data-loading');
          }).catch(function(ex) {
            document.body.classList.remove('data-loading');
          });

      }
    }
  }

  dispose() {
    super.dispose();
    this.el.removeEventListener('click', this.customtextFormHandler);
	}

}

window.CustomText = CustomText;

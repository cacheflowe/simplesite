class ConfigView extends BaseSiteView {

  constructor(el) {
    super(el);
    this.initForm();
	}

  initForm() {
    // get form element
    this.form = this.el.querySelector('form');
    this.configSection = this.form.querySelector('#form-config');

    // add removable click handler
    this.formClickHandler = this.formClicked.bind(this);
    this.el.addEventListener('click', this.formClickHandler);
  }

  formClicked(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'button') {
      if(e.target.hasAttribute('data-form-submit')) {
        // prep submit
        e.preventDefault();
        _store.set(SimpleSite.LOADER_SHOW, true);
        var postData = {
          config: {}
        };

        // get config inputs & populate post sub-object
        let configInputs = this.configSection.querySelectorAll('input');
        for (var i = 0; i < configInputs.length; i++) {
          let inputType = configInputs[i].getAttribute('type');
          let inputKey = configInputs[i].getAttribute('id');
          let inputVal = (inputType != 'checkbox') ? configInputs[i].value : configInputs[i].checked;
          postData.config[inputKey] = inputVal;
        }

        // send request
        fetch(this.form.getAttribute('action'), {
          method: 'POST',
          body: JSON.stringify(postData)
        })
          .then(function(response) {
            return response.text();
          }).then((data) => {
            data = JSON.parse(data);  // get response
            if(data.success) _store.set(SimpleSite.ALERT_SUCCESS, "TopoTable saved");
            else             _store.set(SimpleSite.ALERT_ERROR, "Save failed");
            _store.set(SimpleSite.LOADER_SHOW, false);
          }).catch(function(ex) {
            _store.set(SimpleSite.ALERT_ERROR, 'Fetch failed: ' + ex.message);
            _store.set(SimpleSite.LOADER_SHOW, false);
          });
      }
    }
  }

  dispose() {
    super.dispose();
    this.el.removeEventListener('click', this.formClickHandler);
	}

}

window.ConfigView = ConfigView;

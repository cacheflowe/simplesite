class JsonEditorView extends BaseSiteView {

  constructor(el) {
    super(el);
    this.initForm();
	}

  initForm() {
    // get form element
    this.form = this.el.querySelector('form#json-editor-form');
    this.formDataPath = this.form.getAttribute('data-path');
    this.assetsListPath = this.form.getAttribute('data-assets-list-path');
    this.formContainer = this.form.querySelector('#json-editor-form-content');
    this.addFormListeners();

    // load assets, which loads form data (unless there's not data attribute for apps)
    // we need the assets list before we can build the media dropdown UI
    if(this.assetsListPath) this.loadAssetsData();
    else this.loadFormData();
    _store.set(SimpleSite.LOADER_SHOW, true);
  }

  //////////////////////////////////////
  // FORM COMPONENT LISTENERS
  //////////////////////////////////////

  addFormListeners() {
    // add removable click handler
    this.formClickHandler = this.formClicked.bind(this);
    this.el.addEventListener('click', this.formClickHandler);
    this.formSelectHandler = this.formDropDownSelected.bind(this);
    this.el.addEventListener('input', this.formSelectHandler);
    this.formTextUpdatedHandler = this.formTextUpdated.bind(this);
    this.form.addEventListener('input', this.formTextUpdatedHandler);
    this.formCheckboxHandler = this.formCheckboxUpdated.bind(this);
    this.form.addEventListener('input', this.formCheckboxHandler);
    this.formEnterSubmitBlocker = this.formKeyDown.bind(this);
    this.form.addEventListener('keydown', this.formEnterSubmitBlocker);
  }

  // button clicks

  formClicked(e) {
    // check for button clicks. preventDefault behavior, which might submit the form
    if(e.target && e.target.nodeName.toLowerCase() == 'button') {
      e.preventDefault();
      this.formButtonClick(e.target);
    }
  }

  formButtonClick(buttonEl) {
    // check for publish actions
    this.handlePublishActions(buttonEl);
    // then check for slide actions
    if(buttonEl.hasAttribute('data-slide-action')) {
      let slideAction = buttonEl.getAttribute('data-slide-action');
      let slideIndex = parseInt(buttonEl.getAttribute('data-slide-index'));
      this.handleSlideActions(buttonEl, slideIndex, slideAction);
    }
  }

  // dropdowns

  buildMediaDropdown(label, dataKey, curSelection, filter="", index=-1) { // filter regex example: /jpg|png/
    filter = (!filter) ? "" : filter; // allow passing in `null` for filter
    // build open tag whether it's a config or slide data property
    let dropDownEl = (index == -1) ?
      `
      <label for="config-${dataKey}">${label}</label>
      <select data-config-key="${dataKey}" id="config-${dataKey}" data-media-picker="true">
      ` :
      `
      <label for="slide-${index}-${dataKey}">${label}</label>
      <select data-slide-key="${dataKey}" id="slide-${index}-${dataKey}" data-slide-index="${index}" data-media-picker="true">
      `;
    // add file list <option>s
    this.assetsList.forEach((el, i) => {
      let fileNameArr = el.filePath.split('/');
      let fileName = fileNameArr[fileNameArr.length - 1]; // get just the filename from full upload path
      let isSelected = (curSelection == el.filePath) ? 'selected' : '';
      if(el.filePath.match(filter)) {
        dropDownEl += `<option value="${el.filePath}" ${isSelected}>${fileName}</option>`;
      }
    });
    dropDownEl += `</select>`;
    return dropDownEl;
  }

  buildTimeDropdown(label, dataKey, curSelection, index=-1) {
    // build open tag whether it's a config or slide data property
    let dropDownEl = (index == -1) ?
      `
      <label for="config-${dataKey}">${label}</label>
      <select data-config-key="${dataKey}" id="config-${dataKey}" data-time-picker="true">
      ` :
      `
      <label for="slide-${index}-${dataKey}">${label}</label>
      <select data-slide-key="${dataKey}" id="slide-${index}-${dataKey}" data-slide-index="${index}" data-time-picker="true">
      `;
    // add null option
    let nullSelected = (curSelection == -1) ? 'selected' : '';
    dropDownEl += `<option value="-1" ${nullSelected}>None</option>`;
    // add time <option>s
    let increment = 0.5; // 15-minute increments
    let hours = 24;
    let numOptions = Math.round(1 / increment) * hours;
    for (var i = 0; i < numOptions; i++) {
      var hourVal = increment * i;
      var hour = Math.floor(hourVal);
      var hourStr = Math.floor(hourVal) % 12;
      if(hourStr == 0) hourStr = 12;
      var minutesStr = (hourVal % 1) * 60;
      if(hourStr < 10) hourStr = '0' + hourStr;
      if(minutesStr < 10) minutesStr = '0' + minutesStr;
      let amPm = (hour >= 12) ? 'pm' : 'am';
      let isSelected = (curSelection == hourVal) ? 'selected' : '';
      dropDownEl += `<option value="${hourVal}" ${isSelected}>${hourStr}:${minutesStr}${amPm}</option>`;
    }
    dropDownEl += `</select>`;
    return dropDownEl;
  }

  formDropDownSelected(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'select') {
      let dropDownEl = e.target;
      if(dropDownEl.hasAttribute('data-media-picker')) {
        this.mediaSelected(dropDownEl);
      } else if(dropDownEl.hasAttribute('data-time-picker')) {
        this.timeSelected(dropDownEl);
      }
    }
  }

  mediaSelected(dropDownEl) {
    let selectedAsset = dropDownEl.value;
    if(dropDownEl.hasAttribute('data-slide-key')) {
      let dataKey = dropDownEl.getAttribute('data-slide-key');
      let slideIndex = parseInt(dropDownEl.getAttribute('data-slide-index'));
      this.slidesData[slideIndex][dataKey] = selectedAsset;
    } else if(dropDownEl.hasAttribute('data-config-key')) {
      let dataKey = dropDownEl.getAttribute('data-config-key');
      this.configData[dataKey] = selectedAsset;
    }
    // refresh UI, since image and video might need to be recreated
    this.rebuildForm();
  }

  timeSelected(dropDownEl) {
    let selectedTime = parseFloat(dropDownEl.value);
    if(dropDownEl.hasAttribute('data-slide-key')) {
      let dataKey = dropDownEl.getAttribute('data-slide-key');
      let slideIndex = parseInt(dropDownEl.getAttribute('data-slide-index'));
      this.slidesData[slideIndex][dataKey] = selectedTime;
    } else if(dropDownEl.hasAttribute('data-config-key')) {
      let dataKey = dropDownEl.getAttribute('data-config-key');
      this.configData[dataKey] = selectedTime;
    }
  }

  mediaNodeFromAsset(assetURL) {
    return (assetURL.match(/.mp4/)) ?
      `<video class="slide-asset-preview" src="/${assetURL}" controls playsinline muted loops>` :
      `<img class="slide-asset-preview transparent-bg" src="/${assetURL}">`;
  }

  // checkbox toggled

  buildCheckbox(label, dataKey, value, index=-1) {   // negative index to denote configData object
    let inputId = dataKey + '-' + index;
    let checkedAttr = (value == true) ? "checked" : "";
    return `
      <label for="${inputId}">${label}</label>
      <label class="toggle" for="${inputId}">
        <input type="checkbox" id="${inputId}" data-slide-key="${dataKey}" data-slide-index="${index}" ${checkedAttr} />
        <span class="toggle-slider round"></span>
      </label>
    `;
  }

  formCheckboxUpdated(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'input' && e.target.getAttribute("type") == "checkbox") {
      this.checkboxUpdated(e.target);
    }
  }

  checkboxUpdated(checkBoxEl) {
    let dataKey = checkBoxEl.getAttribute('data-slide-key');
    let slideIndex = parseInt(checkBoxEl.getAttribute('data-slide-index'));
    if(slideIndex >= 0) {
      this.slidesData[slideIndex][dataKey] = checkBoxEl.checked;
    } else {
      this.configData[dataKey] = checkBoxEl.checked;
    }
  }

  // text updated in text input/texturea

  buildTextInput(label, dataKey, curValue, index=-1, inputType="text") {
    curValue = (!curValue) ? "" : curValue; // `null` values converted to empty string
    var maxlength = "", maxLenAttr = "", overflowClass = "";
    if(this.maxlengthData && !!this.maxlengthData[dataKey]) {
      maxlength = `data-maxlngth="${this.maxlengthData[dataKey]}"`;    // get maxlength from json form config // turn to maxlength to actually clamp textfield inputs
      maxLenAttr = `data-maxlength="${curValue.length} / ${this.maxlengthData[dataKey]}"`;
      overflowClass = (curValue.length >= this.maxlengthData[dataKey]) ? `class="overflow"` : "";
    }
    return (index == -1) ?                // build open tag whether it's a config or slide data property
      `<label for="config-${dataKey}" ${maxLenAttr} ${overflowClass}>${label}</label>
       <input type="${inputType}" id="config-${dataKey}" data-config-key="${dataKey}" value="${curValue}" ${maxlength} />`
       :
      `<label for="slide-${dataKey}-${index}" ${maxLenAttr} ${overflowClass}>${label}</label>
       <input type="${inputType}" id="slide-${dataKey}-${index}" data-slide-index="${index}" data-slide-key="${dataKey}" value="${curValue}" ${maxlength} />`;
  }

  buildTextarea(label, dataKey, curValue, index=-1) {
    curValue = (!curValue) ? "" : curValue; // `null` values converted to empty string
    var maxlength = "", maxLenAttr = "", overflowClass = "";
    if(this.maxlengthData && !!this.maxlengthData[dataKey]) {
      maxlength = `data-maxlngth="${this.maxlengthData[dataKey]}"`;    // get maxlength from json form config // turn to maxlength to actually clamp textfield inputs
      maxLenAttr = `data-maxlength="${curValue.length} / ${this.maxlengthData[dataKey]}"`;
      overflowClass = (curValue.length >= this.maxlengthData[dataKey]) ? `class="overflow"` : "";
    }
    return (index == -1) ?                // build open tag whether it's a config or slide data property
      `<label for="config-${dataKey}" ${maxLenAttr} ${overflowClass}>${label}</label>
       <textarea id="config-${dataKey}" data-config-key="${dataKey}" ${maxlength}>${curValue}</textarea>`
       :
      `<label for="slide-${dataKey}-${index}" ${maxLenAttr} ${overflowClass}>${label}</label>
       <textarea id="slide-${dataKey}-${index}" data-slide-index="${index}" data-slide-key="${dataKey}" ${maxlength}>${curValue}</textarea>`;
  }

  formTextUpdated(e) {
    if(e.target) {
      let inputEl = e.target;
      let nodeName = inputEl.nodeName.toLowerCase();
      let isTextInput = (nodeName == 'input' && inputEl.getAttribute("type") == "text");
      let isNumberInput = (nodeName == 'input' && inputEl.getAttribute("type") == "number");
      if(isTextInput || isNumberInput || nodeName == 'textarea') {
        if(isNumberInput) this.forceNumeric(inputEl);
        this.textUpdated(inputEl);
      }
    }
  }

  forceNumeric(inputEl) {
    // if non-numeric, input value is empty, so we can set it to zero as a default
    if(inputEl.value.length == 0) inputEl.value = 0;
  }

  textUpdated(inputEl) {
    // update maxlength via label attribute, since text inputs can't have pseudo-elements
    let inputId = inputEl.getAttribute('id');
    let labelForInput = this.form.querySelector(`label[for="${inputId}"]`);
    if(labelForInput && inputEl.hasAttribute('data-maxlngth')) {  // turn to maxlength to actually clamp textfield inputs
      let maxLen = inputEl.getAttribute('data-maxlngth'); // turn to maxlength to actually clamp textfield inputs
      let curLen = inputEl.value.length;
      labelForInput.setAttribute('data-maxlength', `${curLen} / ${maxLen}`);  // <span class="curLen">${curLen}</span> / <span class="maxLen">${maxLen}</span>
      if(curLen >= maxLen) {
        labelForInput.classList.add("overflow");
      } else {
        labelForInput.classList.remove("overflow");
      }
    }
    // grab value to be updated on json obj
    var savedVal = inputEl.value.trim();
    // update config data on json data object as we type
    if(inputEl.hasAttribute('data-config-key')) {
      let configKey = inputEl.getAttribute('data-config-key');
      this.configData[configKey] = savedVal;
    }
    // update slide data on json data object - get slide index to target the slide
    // and use the 'data-slide-key' attribute to target data on the main slides data object
    if(inputEl.hasAttribute('data-slide-key')) {
      let slideDataKey = inputEl.getAttribute('data-slide-key');
      let slideIndex = parseInt(inputEl.getAttribute('data-slide-index'));
      this.slidesData[slideIndex][slideDataKey] = savedVal;
    }
  }

  // block ENTER from submitting form

  formKeyDown(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'input') {
      if(e.keyCode == 13) e.preventDefault();
    }
  }

  //////////////////////
  // LOAD FORM DATA
  //////////////////////

  // load list of uploads for dropdown

  loadAssetsData() {
    // send request
    fetch(this.assetsListPath, {
      method: 'POST',
      body: JSON.stringify({})
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);  // get response
        this.assetsList = data;
        requestAnimationFrame(() => this.loadFormData());
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, 'Assets list fetch failed: ' + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

  // load current data to build form

  loadFormData() {
    // send request
    fetch(this.formDataPath, {
      method: 'POST',
      body: JSON.stringify({})
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        this.setFormDataFromServer(JSON.parse(data)); // store response
        _store.set(SimpleSite.LOADER_SHOW, false);
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, 'Slides data fetch failed: ' + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

  setFormDataFromServer(jsonData) {
    // store local properties of form json
    for(var key in jsonData) {
      this[key+"Data"] = jsonData[key];
    }
    requestAnimationFrame(() => this.rebuildForm());
  }

  // convert paths between static sites in /apps/ and CMS pointing to the same assets on the server

  valIsString(val) {
    return typeof val === 'string' || val instanceof String;
  }

  prepAssetsForCMS() {
    // replace `../_uploads` with `app/_uploads` to switch from static site's to CMS's asset path reference
    if(this.slidesData) this.slidesData.forEach((slide, i) => {
      for(var key in slide) {
        if(this.valIsString(slide[key])) slide[key] = slide[key].replace("../_uploads", "apps/_uploads");
      }
    });
    if(this.configData) for(var key in this.configData) {
      if(this.valIsString(this.configData[key])) {
        this.configData[key] = this.configData[key].replace("../_uploads", "apps/_uploads");
      }
    }
  }

  prepAssetsForSubmit() {
    // replace `app/_uploads` with `../_uploads` to switch from CMS's to static site's asset path reference
    if(this.slidesData) this.slidesData.forEach((slide, i) => {
      for(var key in slide) {
        if(this.valIsString(slide[key])) slide[key] = slide[key].replace("apps/_uploads", "../_uploads");
      }
    });
    if(this.configData) for(var key in this.configData) {
      if(this.valIsString(this.configData[key])) {
        this.configData[key] = this.configData[key].replace("apps/_uploads", "../_uploads");
      }
    }
  }

  //////////////////////////
  // BUILD FORM
  //////////////////////////
  // build form sections from json form data
  // needs to be overridden by subclass

  rebuildForm() {
    this.prepAssetsForCMS();
    this.formContainer.innerHTML = this.buildConfig() + this.buildSlides();
  }

  buildConfig() {
    return ""; // override if there's an app config form section (Boulders & Pylons)
  }

  buildSlides() {
    return ""; // override if there's an app config form section (Boulders & Pylons)
  }

  /////////////////////////
  // SLIDE BUTTON ACTIONS
  /////////////////////////

  handleSlideActions(buttonEl, slideIndex, slideAction) {
    // TODO: make handleSlideActions() extensible by subclass?
    switch(slideAction) {
      case "move-up" :
        if(slideIndex > 0) {
          let removedSlide = this.slidesData.splice(slideIndex, 1);
          this.slidesData.splice(slideIndex - 1, 0, removedSlide[0]);
          this.rebuildForm();
        } else {
          _store.set(SimpleSite.ALERT_ERROR, "Can't move up");
        }
        break;
      case "move-down" :
        if(slideIndex < this.slidesData.length - 1) {
          let removedSlide = this.slidesData.splice(slideIndex, 1);
          this.slidesData.splice(slideIndex + 1, 0, removedSlide[0]);
          this.rebuildForm();
        } else {
          _store.set(SimpleSite.ALERT_ERROR, "Can't move down");
        }
        break;
      case "slide-delete" :
        if(this.slidesData.length > 1) {
          if(confirm("Are you sure you want to delete this item?") == true) {
            let removedSlide = this.slidesData.splice(slideIndex, 1);
            this.rebuildForm();
          }
        } else {
          _store.set(SimpleSite.ALERT_ERROR, "Can't delete the only item");
        }
        break;
      // DEMO DESK ------------
      case "copy-slide" :
        let curSlide = this.slidesData[slideIndex];
        let copySlide = {};
        for(var key in curSlide) copySlide[key] = curSlide[key];
        this.slidesData.splice(slideIndex + 1, 0, copySlide);
        this.rebuildForm();
        break;
      // PYLON ----------------
      case "add-text-slide" :
        let newTextSlide = {descriptionEN: "", descriptionES: ""};
        this.slidesData.splice(slideIndex + 1, 0, newTextSlide);
        this.rebuildForm();
        break;
      case "add-video-slide" :
        let defaultVideoSelection = (this.assetsList && this.assetsList.length > 0) ? this.assetsList[0].filePath : "No Videos Uploaded";
        let newVideoSlide = {assetURL: defaultVideoSelection};
        this.slidesData.splice(slideIndex + 1, 0, newVideoSlide);
        this.rebuildForm();
        break;
      case "add-credits-slide" :
        let newCreditsSlide = {creditsTitleEN: "", creditsEN: "", creditsTitleES: "", creditsES: ""};
        this.slidesData.splice(slideIndex + 1, 0, newCreditsSlide);
        this.rebuildForm();
        break;
      default: break;
    }
  }

  /////////////////////////
  // POST FORM DATA TO SERVER
  /////////////////////////

  handlePublishActions(buttonEl) {
    // save draft json data
    if(buttonEl.hasAttribute('data-form-submit')) {
      this.submitForm();
    }
    // special publish button to copy app config to static files
    else if(buttonEl.hasAttribute('data-form-publish')) {
      this.publishData();
    } else if(buttonEl.hasAttribute('data-form-reset-from-publish')) {
      this.resetData();
    }
  }

  submitForm() {
    // prep data object with relative paths for static site
    this.prepAssetsForSubmit();
    _store.set(SimpleSite.LOADER_SHOW, true);

    // create data object from active app data
    // dynamically include config data, since it might not exist for some apps (demodesk)
    let postData = {};
    if(this.slidesData) postData.slides = this.slidesData;
    if(this.configData) postData.config = this.configData;

    // send request
    fetch(this.form.getAttribute('action'), {
      method: 'POST',
      body: JSON.stringify(postData)
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);  // get response
        if(data.success) {
          _store.set(SimpleSite.ALERT_SUCCESS, data.success);
          _store.set(SimpleSite.RELOAD_VIEW, true);   // reload form if we've published the data
        } else {
          _store.set(SimpleSite.ALERT_ERROR, data.fail);
        }
        _store.set(SimpleSite.LOADER_SHOW, false);
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, 'Fetch failed: ' + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

  // publish draft data to SciPy

  publishData() {
    if(!confirm("Are you sure you want to publish this data to the SciPy?")) return;

    // prep submit & send request
    _store.set(SimpleSite.LOADER_SHOW, true);
    fetch(this.form.getAttribute('action').replace("update", "publish"), {  // convert /update to /publish, piggybacking on existing config form API paths
      method: 'POST',
      body: JSON.stringify({})
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);  // get response
        if(data.success) {
          _store.set(SimpleSite.ALERT_SUCCESS, data.success);
          _store.set(SimpleSite.RELOAD_VIEW, true);   // reload form if we've published the data
        } else {
          _store.set(SimpleSite.ALERT_ERROR, data.fail);
        }
        _store.set(SimpleSite.LOADER_SHOW, false);
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, 'Publish fetch failed: ' + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

  // reset draft data from latest static app version

  resetData() {
    if(!confirm("Are you sure you want to reset the data to the last publish?")) return;

    // send request
    fetch(this.form.getAttribute('action').replace("update", "reset"), {  // convert /update to /publish, piggybacking on existing config form API paths
      method: 'POST',
      body: JSON.stringify({})
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);  // get response
        if(data.success) {
          _store.set(SimpleSite.ALERT_SUCCESS, data.success);
          _store.set(SimpleSite.RELOAD_VIEW, true);   // reload form if we've reset the data
        } else {
          _store.set(SimpleSite.ALERT_ERROR, data.fail);
        }
        _store.set(SimpleSite.LOADER_SHOW, false);
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, 'Fetch failed: ' + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

  /////////////////////////////////
  // CLEAN UP
  /////////////////////////////////

  dispose() {
    super.dispose();
    this.el.removeEventListener('click', this.formClickHandler);
    this.el.removeEventListener('input', this.formSelectHandler);
    this.el.removeEventListener('input', this.formCheckboxHandler);
    this.form.removeEventListener('input', this.formTextUpdatedHandler);
    this.form.removeEventListener('keydown', this.formEnterSubmitBlocker);
	}

}

window.JsonEditorView = JsonEditorView;

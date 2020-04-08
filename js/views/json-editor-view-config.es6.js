class ConfigFormView extends JsonEditorView {

  constructor(el) {
    super(el);
	}

  // custom form build overrides

  buildConfig() {
    let configFormHTML = `
      <p class="grid-container quarters">
        <a class="button button-primary" href="javascript:window.history.back();">&larr; Back</a>
      </p>
      <h2>Config</h2>
      <div class="json-editor-config-props grid-container halves">
    `;
    for(var key in this.configData) {
      let value = this.configData[key];
      configFormHTML += this.configTemplate(key, value);
    }
    configFormHTML += "</div>";
    return configFormHTML;
  }

  // custom app data cards

  inputForData(key, value, label) {
    let dataType = typeof(value);
    var inputHtml = "";
    switch(dataType) {
      case "string" :
        // todo: check for hex color
        inputHtml = this.buildTextInput(label, key, value);
        break;
      case "number" :
        inputHtml = this.buildTextInput(label, key, value, -1, 'number');
        break;
      case "boolean" :
        inputHtml = this.buildCheckbox(label, key, value);
        break;
    }
    return inputHtml;
  }

  configTemplate(key, value) {
    let label = (this.labelsData && this.labelsData[key]) ? this.labelsData[key] : key;
    let inputEl = this.inputForData(key, value, label);
    let configCard = `
      <div class="config-card">
        ${inputEl}
      </div>
    `;
    return configCard;
  }

}

window.ConfigFormView = ConfigFormView;

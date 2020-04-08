class DemoDeskFormView extends JsonEditorView {

  constructor(el) {
    super(el);
  }

  // custom form build overrides

  buildSlides() {
    let slidesHTML = '<h2>Slides</h2>';
    slidesHTML += '<div>';
    this.slidesData.forEach((slide, i) => {
      slidesHTML += this.slideTemplate(slide, i);
    });
    slidesHTML += "</div>";
    return slidesHTML;
  }

  // custom app data cards

  slideTemplate(slide, index) {
    return `
      <h5>Slide #${index+1}</h5>
      <div class="json-editor-slide grid-container thirds">
        <div>
          ${this.buildMediaDropdown("Slide Background:", "assetURL", slide.assetURL, null, index)}
          <div class="json-editor-asset-preview">${this.mediaNodeFromAsset(slide.assetURL)}</div>
        </div>
        <div>
          ${this.buildCheckbox("Hide Footer:", "hideFooter", slide.hideFooter, index)}
          <div>${this.buildTextInput("Text line 1", "textLine1", slide.textLine1, index)}</div>
          <div>${this.buildTextInput("Text line 2", "textLine2", slide.textLine2, index)}</div>
        </div>
        <div class="json-editor-slide-actions">
          <label>Actions:</label>
          <button data-slide-index="${index}" data-slide-action="move-up">Move Up &uarr;</button>
          <button data-slide-index="${index}" data-slide-action="move-down">Move Down &darr;</button>
          <button data-slide-index="${index}" data-slide-action="slide-delete">Delete</button>
          <button data-slide-index="${index}" data-slide-action="copy-slide">Copy Slide</button>
        </div>
        <div>
          <div>${this.buildTextarea("Text Paragraph (EN)", "textParagraphEN", slide.textParagraphEN, index)}</div>
        </div>
        <div>
          <div>${this.buildTextarea("Text Paragraph (ES)", "textParagraphES", slide.textParagraphES, index)}</div>
        </div>
      </div>
    `;
  }

}

window.DemoDeskFormView = DemoDeskFormView;

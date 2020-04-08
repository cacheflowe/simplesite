class UploadView extends BaseSiteView {

  constructor(el) {
    super(el);
    this.uploadForm = this.el.querySelector('#upload-form');
    this.formResultEl = this.el.querySelector('#upload-form-result');
    this.inputEl = this.uploadForm.querySelector('input[type="file"]');
    this.uploadsEl = this.el.querySelector("#uploads-container");
    this.initImageUploadForm();
    this.listenForDeleteClick();
    this.displayAllUploads();
	}

  /////////////////
  // IMAGE UPLOAD FORM
  /////////////////

  initImageUploadForm() {
    if(this.uploadForm) {
      // form submit override listener
      this.fileUploadHandler = this.uploadFormSubmitted.bind(this);
      this.uploadForm.addEventListener('submit', this.fileUploadHandler);
      // file input select listener
      this.fileChangeHandler = this.fileSelected.bind(this);
      this.uploadForm.addEventListener('change', this.fileChangeHandler);
      // drag & drop file onto form listeners for css styling
      this.formDragOverHandler = this.formDragFileOver.bind(this);
      this.uploadForm.addEventListener('dragover', this.formDragOverHandler);
      this.formDragLeaveHandler = this.formDragFileLeave.bind(this);
      this.uploadForm.addEventListener('dragleave', this.formDragLeaveHandler);
      this.formDropFileHandler = this.formDropFile.bind(this);
      this.uploadForm.addEventListener('drop', this.formDropFileHandler);
    }
  }

  // drag & drop -----

  formDragFileOver(e) {
    e.preventDefault();
    this.uploadForm.classList.add('drop-over');
  }

  formDragFileLeave(e) {
    // e.preventDefault();
    this.uploadForm.classList.remove('drop-over');
  }

  formDropFile(e) {
    e.preventDefault();
    this.uploadForm.classList.remove('drop-over');

    // get files from drop
    let files = e.target.files || e.dataTransfer.files;  // get files array from drop event
    this.inputEl.files = files;                           // set dropped files on file input
    this.fileSelected({target:this.inputEl});            // simulate input change event to trigger preview
  }

  // image selected listener for input

  fileSelected(e) {
    if(e.target && e.target.files && e.target.files.length > 0) {
      let isVideo = (e.target.files[0].name.match(/.mp4/)) ? true : false;
      let mediaBlob = URL.createObjectURL(e.target.files[0]);
      let previewImg = this.el.querySelector('#img-preview');
      let previewVideo = this.el.querySelector('#video-preview');
      if(isVideo) {
        previewVideo.src = mediaBlob;
        previewImg.removeAttribute('src');
      } else {
        previewImg.src = mediaBlob;
        previewVideo.removeAttribute('src');
      }
      this.clearFormResult();
    } else {
      this.formResultError("Couldn't load file");
    }
  }

  clearPreview() {
    let previewImg = this.el.querySelector('#img-preview');
    let previewVideo = this.el.querySelector('#video-preview');
    previewImg.removeAttribute('src');
    previewVideo.removeAttribute('src');
  }

  uploadFormSubmitted(e) {
    e.preventDefault();
    this.submitFileUploadForm();
  }

  submitFileUploadForm() {
    // validate selected file
    if(!this.inputEl.files || this.inputEl.files.length == 0) {
      this.formResultError("Please select an image or video to upload");
      return;
    }

    // reset loading
    _store.set(SimpleSite.LOADER_SHOW, true);
    this.clearFormResult();

    // prep file file upload data
    var data = new FormData();
    data.append('file_upload', this.inputEl.files[0]);

    // send to server
    fetch(this.uploadForm.getAttribute('action'), {
      method: this.uploadForm.getAttribute('method'),
      body: data
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        if(data.success) {
          this.formResultSuccess(data.success);
          _store.set(SimpleSite.ALERT_SUCCESS, "Upload success!");
          requestAnimationFrame(() => {this.displayAllUploads()});
        } else if(data.fail) {
          this.formResultError(data.fail);
        }
        _store.set(SimpleSite.LOADER_SHOW, false);
        this.uploadForm.reset();
        this.clearPreview();
      }).catch((ex) => {
        this.formResultError("Fetch js upload failed: " + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
        this.uploadForm.reset();
        this.clearPreview();
      });
  }

  clearFormResult() {
    this.formResultEl.innerHTML = '';
  }

  formResultSuccess(msg) {
    this.formResultEl.innerHTML = msg;
  }

  formResultError(msg) {
    this.formResultEl.innerHTML = '<span class="form-upload-error-message">'+ msg +'</span>';
  }

  /////////////////
  // LOAD UPLOADS FRAGMENT
  /////////////////

  displayAllUploads() {
    fetch(this.uploadForm.getAttribute('data-uploads'), {method: "POST"})
      .then(function(response) {
        return response.text();
      }).then((data) => {
        this.uploadsEl.innerHTML = data;
        this.removeImageZooming();
        this.addImageZooming()
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, "Couldn't load uploads");
      });
  }

  /////////////////
  // DELETE IMAGES
  /////////////////

  listenForDeleteClick() {
    this.deleteClickHandler = this.deleteButtonClicked.bind(this);
    this.uploadsEl.addEventListener('click', this.deleteClickHandler);
  }

  deleteButtonClicked(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'button') {
      if(e.target.hasAttribute('data-action')) {
        e.preventDefault();
        let buttonAction = e.target.getAttribute('data-action');
        if(buttonAction == "delete") {
          var confirmResult = confirm("Are you sure you want to delete this file?");
          if(confirmResult == true) {
           this.deleteImage(e.target);
          } else {
           // canceled
          }
        }
      }
    }
  }

  deleteImage(button) {
    _store.set(SimpleSite.LOADER_SHOW, true);

    // set post data specifying the upload to delete
    let filePath = button.getAttribute('data-upload-path');
    let uploadData = {'filepath': filePath};

    fetch(this.uploadForm.getAttribute('data-delete'), {
      method: 'post',
      body: JSON.stringify(uploadData)
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        this.clearFormResult();
        this.displayAllUploads();
        _store.set(SimpleSite.ALERT_SUCCESS, "File deleted!");
        _store.set(SimpleSite.LOADER_SHOW, false);
      }).catch(function(ex) {
        _store.set(SimpleSite.ALERT_ERROR, "Delete request failed");
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

  // CLEANUP

  dispose() {
    super.dispose();
    if(this.uploadForm) {
      this.uploadForm.removeEventListener('submit', this.fileUploadHandler);
      this.uploadForm.removeEventListener('change', this.fileChangeHandler);
      this.uploadForm.removeEventListener('dragover', this.formDragOverHandler);
      this.uploadForm.removeEventListener('dragleave', this.formDragLeaveHandler);
      this.uploadForm.removeEventListener('drop', this.formDropFileHandler);
    }
    this.uploadsEl.removeEventListener('click', this.deleteClickHandler);
	}

}

window.UploadView = UploadView;

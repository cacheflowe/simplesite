class ImageUploadView extends FancyView {

  constructor(el) {
    super(el);
    this.uploadsEl = this.el.querySelector("#uploads-container");
    this.initImageUploadForm();
    this.listenForDeleteClick();
    this.reloadUploads();
	}

  /////////////////
  // IMAGE UPLOAD FORM
  /////////////////

  initImageUploadForm() {
    this.imageUploadForm = this.el.querySelector('#image-upload-form');
    if(this.imageUploadForm) {
      this.imageUploadHandler = this.imageUploadFormSubmitted.bind(this);
      this.imageUploadForm.addEventListener('submit', this.imageUploadHandler);
      this.imageChangeHandler = this.imageSelected.bind(this);
      this.imageUploadForm.addEventListener('change', this.imageChangeHandler);
    }
  }

  imageSelected(e) {
    let preview = this.el.querySelector('#img-preview');
    preview.src = URL.createObjectURL(e.target.files[0]);
  }

  clearPreview() {
    let preview = this.el.querySelector('#img-preview');
    preview.removeAttribute('src');
  }

  imageUploadFormSubmitted(e) {
    e.preventDefault();
    this.submitImageUploadForm();
  }

  submitImageUploadForm() {
    // get elements
    let formResult = this.el.querySelector('#form-result');
    var input = this.imageUploadForm.querySelector('input[type="file"]');

    // validate selected image
    if(!input.files || input.files.length == 0) {
      formResult.innerHTML = '<span style="color:red;">Please select an image to upload</span>';
      return;
    }

    // reset loading
    document.body.classList.add('data-loading');
    formResult.innerHTML = '';

    // get image file upload
    var data = new FormData();
    data.append('image_upload', input.files[0]);
    // data.append('submit', true); // add more data if needed

    // send to server
    fetch(this.imageUploadForm.getAttribute('action'), {
      method: this.imageUploadForm.getAttribute('method'),
      body: data
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        if(data.success) {
          formResult.innerHTML = data.success;
          // page('/imageupload/uploads');
          requestAnimationFrame(() => {this.reloadUploads()});
        } else if(data.fail) {
          formResult.innerHTML = '<span style="color:red;">' + data.fail + '</span>';
        }
        document.body.classList.remove('data-loading');
        this.imageUploadForm.reset();
        this.clearPreview();
      }).catch((ex) => {
        formResult.innerHTML = '<span style="color:red;">Fetch upload failed</span>';
        document.body.classList.remove('data-loading');
        this.imageUploadForm.reset();
        this.clearPreview();
      });
  }

  /////////////////
  // LOAD UPLOADS FRAGMENT
  /////////////////

  reloadUploads() {
    fetch(this.imageUploadForm.getAttribute('action') + '/uploads', {method: "POST"})
      .then(function(response) {
        return response.text();
      }).then((data) => {
        this.uploadsEl.innerHTML = data;
      }).catch(function(ex) {
        console.warn('Fetch uploads failed', ex);
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
          this.deleteImage(e.target);
        } else if(buttonAction == "activate") {
          this.activateImage(e.target);
        }
      }
    }
  }

  deleteImage(button) {
    document.body.classList.add('data-loading');

    let imagePath = button.getAttribute('data-image-path');
    let uploadData = {'imagepath': imagePath};

    fetch('/imagedelete', {
      method: 'post',
      body: JSON.stringify(uploadData)
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        this.reloadUploads();
        document.body.classList.remove('data-loading');
      }).catch(function(ex) {
        // formResult.innerHTML = '<span style="color:red;">Fetch upload failed</span>';
        document.body.classList.remove('data-loading');
      });
  }

  activateImage(button) {
    document.body.classList.add('data-loading');

    // toggle off if already active
    let imagePath = (button.classList.contains('active')) ? null : button.getAttribute('data-image-path');
    let uploadData = {
      'imagepath': imagePath,
      'imagekey': button.getAttribute('data-image-key')
    };

    fetch('/imageactivate', {
      method: 'post',
      body: JSON.stringify(uploadData)
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        this.reloadUploads();
        document.body.classList.remove('data-loading');
      }).catch(function(ex) {
        // formResult.innerHTML = '<span style="color:red;">Fetch upload failed</span>';
        document.body.classList.remove('data-loading');
      });
  }

  // CLEANUP

  dispose() {
    super.dispose();
    if(this.imageUploadForm) {
      this.imageUploadForm.removeEventListener('submit', this.imageUploadHandler);
      this.imageUploadForm.addEventListener('change', this.imageChangeHandler);
    }
    this.uploadsEl.removeEventListener('click', this.deleteClickHandler);
	}

}

window.ImageUploadView = ImageUploadView;

class BaseView {

  constructor(el) {
    this.el = el;
    this.findLightboxImages();

    let mediaServices = [
      window.embetter.services.youtube,
      window.embetter.services.vimeo,
      window.embetter.services.soundcloud,
      window.embetter.services.instagram,
      window.embetter.services.dailymotion,
      window.embetter.services.codepen,
      window.embetter.services.shadertoy,
      window.embetter.services.video,
      window.embetter.services.gif
    ];
    window.embetter.utils.initMediaPlayers(this.el, mediaServices);
    // prepLazyLoadImages();
    this.buildShareLinks();
    this.buildContactForm();
	};

  // lightbox stuff ----------------------------------------------------------------

  findLightboxImages() {
    this.lightboxDiv = null;
    this.lightboxImgSrc = null;
    this.lightboxImageLoader = null;
    this.lightboxLinks = null;

    this.lightboxLinks = this.el.querySelectorAll('a[rel]');
    for( var i=0; i < this.lightboxLinks.length; i++ ) {
      this.lightboxLinks[i].addEventListener('click', (e) => this.handleLightboxLink(e));
    }
  };

  handleLightboxLink(e) {
    e.preventDefault();

    // closest() implementation to find link
    var clickedEl = e.target;
    while(clickedEl.nodeName.toLowerCase() !== 'a') {
      clickedEl = clickedEl.parentNode;
    }

    // load image
    this.lightboxImgSrc = clickedEl.href;
    this.lightboxImageLoader = new Image();
    this.lightboxImageLoader.onload = this.lightboxImageLoaded;
    this.lightboxImageLoader.src = this.lightboxImgSrc;
  };

  lightboxImageLoaded() {
    // check if we need to let the image display at natural size
    // console.log('this.lightboxImageLoader.height', this.lightboxImageLoader.height);
    var containedClass = (this.lightboxImageLoader.height < window.innerHeight - 40 && this.lightboxImageLoader.width < window.innerWidth - 40) ? 'lightbox-image-contained' : '';

    // add elements to body
    this.lightboxDiv = document.createElement('div');
    this.lightboxDiv.className = 'lightbox';
    this.lightboxDiv.innerHTML = '<div class="lightbox-image-holder '+ containedClass +'" style="background-image:url('+ this.lightboxImgSrc +')"></div>';
    document.body.appendChild(this.lightboxDiv);

    requestAnimationFrame(function(){
      this.lightboxDiv.className = 'lightbox';
      requestAnimationFrame(function(){
        this.lightboxDiv.className = 'lightbox showing';
      });
    });

    this.lightboxDiv.addEventListener('click', (e) => this.hideLightbox(e));
    document.addEventListener('keyup', (e) => this.hideLightbox(e));
  };

  hideLightbox(e) {
    if(e.keyCode == null || e.keyCode == 0 || e.keyCode == 27) {  // handle either clicks of esc key press with same event
      this.lightboxDiv.removeEventListener('click', (e) => this.hideLightbox(e));
      document.removeEventListener('keyup', (e) => this.hideLightbox(e));
      this.lightboxDiv.className = 'lightbox';
      setTimeout(function(){
        document.body.removeChild(this.lightboxDiv);
      },300);
    }
  };

  disposeLightboxLinks() {
    if( this.lightboxLinks != null ) {
      for( var i=0; i < this.lightboxLinks.length; i++ ) {
        this.lightboxLinks[i].removeEventListener('click', (e) => this.handleLightboxLink(e));
      }
    }
  };

	/* Lazy-load images --------------------------------------*/
  // prepLazyLoadImages() {
  //   var images = $('.listing-image img');
  //   for( var i=0; i < images.length; i++ ) {
  //     console.log(images[i]);
  //     images[i].setAttribute('data-src',images[i].src);
  //     images[i].src = '/images/blank.gif';
  //   }
  // };

  /* Share links ----------------------------------------*/
  buildShareLinks() {
    var shareEl = this.el.querySelector('.share-out-links');
    if(shareEl !== null) {
      var titleEl = this.el.querySelector('h2');
      var summary = (titleEl !== null) ? titleEl.textContent : '';
      var firstImg = this.el.querySelector('img');
      var img = (firstImg !== null) ? firstImg.src : '';
      window.shareout.setShareLinks(shareEl, document.location.href, summary, img);
    }
  };

  disposeShareLinks() {
    var shareEl = this.el.querySelector('.share-out-links');
    if(shareEl !== null) {
      window.shareout.disposeShareLinks(shareEl);
    }
  };

  /* Contact form ----------------------------------------*/

  buildContactForm() {
    this.contactForm = null;
    this.submitButton = null;
    this.emailInput = null;
    this.aboutInput = null;
    this.messageInput = null;


    this.contactForm = this.el.querySelector('#contactform');
    if(this.contactForm !== null) {
      // grab elements
      this.emailInput = document.getElementById("email");
      this.aboutInput = document.getElementById("about");
      this.messageInput = document.getElementById("message");
      this.submitButton = this.el.querySelector('#contact-submit');
      // listen to submit button
      this.submitButton.addEventListener('click', (e) => this.submitForm(e));
      // populate email link
      var emailLink = document.getElementById('email-button');
      var emailAddy = 'test'+'@'+'user'+'.'+'com';
      emailLink.href = 'mailto:'+emailAddy;
      emailLink.innerHTML = emailAddy;
    }
  };

  submitForm(e) {
    if(this.validateForm() === true) {
      this.submitButton.setAttribute('disabled', 'disabled');
      document.body.classList.add('loading');
      window.reqwest({
        url: 'php/'+'mail/cachemail.'+'p'+'hp',
        method: 'post',
        data: { email: this.emailInput.value, about: this.aboutInput.value, message: this.messageInput.value },
        success: function(data){
          this.insertContactResponse(data);
        },
        complete: function(data){
          document.body.classList.remove('loading');
        }
      });
    }
  };

  insertContactResponse(html) {
    this.contactForm.innerHTML = html;
  };

  disposeContactForm() {
    if(this.submitButton === null) return;
    this.submitButton.removeEventListener('click', (e) => this.submitForm(e));
  };

  validateForm() {
  	if(this.isValidEmail(this.emailInput.value) === false) {
  		alert('Please enter a valid email');
  		this.emailInput.focus();
  		return false;
  	}
  	if (document.getElementById("message").value.length === 0) {
  		alert('Please enter a message');
  		this.messageInput.focus();
  		return false;
  	}
    return true;
  }

  isValidEmail(email) {
      var emailRegex = new RegExp(/^[+\w.-]+@\w[\w.-]+\.[\w.-]*[a-z][a-z]$/i);
      return emailRegex.test(email);
  };

  validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  };

  /* View lifecycle ----------------------------------------*/
	dispose() {
    this.disposeLightboxLinks();
    this.disposeShareLinks();
    this.disposeContactForm();
    window.embetter.utils.disposePlayers();
    // window.cacheCart.disposeLinks(this.el);
	};

};

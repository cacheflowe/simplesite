class FancyView extends BaseView {

  constructor(el) {
    super(el);
    window.embetter.utils.initMediaPlayers(this.el, SimpleSite.mediaServices);
    this.buildShareLinks();
    // prepLazyLoadImages();
    window.cacheCart.parseLinks(this.el);
    this.buildContactForm();
    this.lazyImageLoader = new LazyImageLoader(this.el);
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

  /* Share links ---------------------------------------- */

  buildShareLinks() {
    this.shareOut = new ShareOut();
    var shareEl = this.el.querySelector('.share-out-links');
    if(shareEl !== null) {
      var titleEl = this.el.querySelector('h2');
      var summary = (titleEl !== null) ? titleEl.textContent : '';
      var firstImg = this.el.querySelector('img');
      var img = (firstImg !== null) ? firstImg.src : '';
      this.shareOut.setShareLinks(shareEl, document.location.href, summary, img);
    }
  };

  disposeShareLinks() {
    var shareEl = this.el.querySelector('.share-out-links');
    if(shareEl !== null) {
      this.shareOut.disposeShareLinks(shareEl);
      this.shareOut = null;
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
      this.emailInput = document.getElementById("email-input");
      this.aboutInput = document.getElementById("about-input");
      this.messageInput = document.getElementById("message-input");
      this.submitButton = document.getElementById("contact-submit");
      // listen to submit button
      this.submitButton.addEventListener('click', (e) => this.submitForm(e));
      // populate email link
      var emailLink = document.getElementById('email-button');
      var emailAddy = emailLink.getAttribute('data-username')+'@'+emailLink.getAttribute('data-domain');
      emailLink.href = 'mailto:'+emailAddy;
      emailLink.innerHTML = emailAddy;
    }
  };

  submitForm(e) {
    if(this.validateForm() === true) {
      this.submitButton.setAttribute('disabled', 'disabled');
      document.body.classList.add('page-loading');
      // fetch('/contact/submit', {method: "POST", body:{email: this.emailInput.value, about: this.aboutInput.value, message: this.messageInput.value}})
      fetch('/contact/submit', {method: "POST", body: new FormData(this.contactForm)})
        .then((response) => {
          return response.text();
        }).then((data) => {
          this.insertContactResponse(data);
          document.body.classList.remove('page-loading');
        }).catch(function(ex) {
          console.warn('Submit failed', ex);
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
    if (this.messageInput.value.length === 0) {
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
    this.disposeShareLinks();
    this.disposeContactForm();
    window.embetter.utils.disposePlayers();
    window.cacheCart.disposeLinks(this.el);
    this.lazyImageLoader.dispose();
	};

}

window.FancyView = FancyView;

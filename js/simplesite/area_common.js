var AreaCommon = function(el, isInitialLoad) {

  var _mediaServices = [
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

  var init = function() {
    findLightboxImages();
    window.embetter.utils.initMediaPlayers(el, _mediaServices);
    // prepLazyLoadImages();
    buildShareLinks();
    buildContactForm();
    buildCacheflowePronunciation();
	};

  // lightbox stuff ----------------------------------------------------------------
  var lightboxDiv = null;
  var lightboxImgSrc = null;
  var lightboxImageLoader = null;
  var lightboxLinks = null;

  var findLightboxImages = function() {
    lightboxLinks = el.querySelectorAll('a[rel]');
    for( var i=0; i < lightboxLinks.length; i++ ) {
      lightboxLinks[i].addEventListener('click',handleLightboxLink);
    }
  };

  var handleLightboxLink = function(e) {
    e.preventDefault();

    // closest() implementation to find link
    var clickedEl = e.target;
    while(clickedEl.nodeName.toLowerCase() !== 'a') {
      clickedEl = clickedEl.parentNode;
    }

    // load image
    lightboxImgSrc = clickedEl.href;
    lightboxImageLoader = new Image();
    lightboxImageLoader.onload = lightboxImageLoaded;
    lightboxImageLoader.src = lightboxImgSrc;
  };

  var lightboxImageLoaded = function() {
    // check if we need to let the image display at natural size
    // console.log('lightboxImageLoader.height', lightboxImageLoader.height);
    var containedClass = (lightboxImageLoader.height < window.innerHeight - 40 && lightboxImageLoader.width < window.innerWidth - 40) ? 'lightbox-image-contained' : '';

    // add elements to body
    lightboxDiv = document.createElement('div');
    lightboxDiv.className = 'lightbox';
    lightboxDiv.innerHTML = '<div class="lightbox-image-holder '+ containedClass +'" style="background-image:url('+ lightboxImgSrc +')"></div>';
    document.body.appendChild(lightboxDiv);

    requestAnimationFrame(function(){
      lightboxDiv.className = 'lightbox';
      requestAnimationFrame(function(){
        lightboxDiv.className = 'lightbox showing';
      });
    });

    lightboxDiv.addEventListener('click',hideLightbox);
    document.addEventListener('keyup',hideLightbox);
  };

  var hideLightbox = function(e) {
    if(e.keyCode == null || e.keyCode == 0 || e.keyCode == 27) {  // handle either clicks of esc key press with same event
      lightboxDiv.removeEventListener('click',hideLightbox);
      document.removeEventListener('keyup',hideLightbox);
      lightboxDiv.className = 'lightbox';
      setTimeout(function(){
        document.body.removeChild(lightboxDiv);
      },300);
    }
  };

  var disposeLightboxLinks = function() {
    if( lightboxLinks != null ) {
      for( var i=0; i < lightboxLinks.length; i++ ) {
        lightboxLinks[i].removeEventListener('click',handleLightboxLink);
      }
    }
  };

	/* Lazy-load images --------------------------------------*/
  // var prepLazyLoadImages = function() {
  //   var images = $('.listing-image img');
  //   for( var i=0; i < images.length; i++ ) {
  //     console.log(images[i]);
  //     images[i].setAttribute('data-src',images[i].src);
  //     images[i].src = '/images/blank.gif';
  //   }
  // };

  /* Share links ----------------------------------------*/
  var buildShareLinks = function() {
    var shareEl = el.querySelector('.share-out-links');
    if(shareEl !== null) {
      var titleEl = el.querySelector('h2');
      var summary = (titleEl !== null) ? titleEl.textContent : '';
      var firstImg = el.querySelector('img');
      var img = (firstImg !== null) ? firstImg.src : '';
      window.shareout.setShareLinks(shareEl, document.location.href, summary, img);
    }
  };

  var disposeShareLinks = function() {
    var shareEl = el.querySelector('.share-out-links');
    if(shareEl !== null) {
      window.shareout.disposeShareLinks(shareEl);
    }
  };

  /* Contact form ----------------------------------------*/
  var contactForm = null;
  var submitButton = null;
  var emailInput = null;
  var aboutInput = null;
  var messageInput = null;


  var buildContactForm = function() {
    contactForm = el.querySelector('#contactform');
    if(contactForm !== null) {
      // grab elements
      emailInput = document.getElementById("email");
      aboutInput = document.getElementById("about");
      messageInput = document.getElementById("message");
      submitButton = el.querySelector('#contact-submit');
      // listen to submit button
      submitButton.addEventListener('click', submitForm);
      // populate email link
      var emailLink = el.querySelector('#cacheflowe-email');
      var emailAddy = 'cacheflowe'+'@'+'cacheflowe'+'.'+'com';
      emailLink.href = 'mailto:'+emailAddy;
      emailLink.innerHTML = emailAddy;
    }
  };

  var submitForm = function(e) {
    if(validateForm() === true) {
      submitButton.setAttribute('disabled', 'disabled');
      document.body.classList.add('loading');
      window.reqwest({
        url: 'php/'+'mail/cachemail.'+'p'+'hp',
        method: 'post',
        data: { email: emailInput.value, about: aboutInput.value, message: messageInput.value },
        success: function(data){
          insertContactResponse(data);
        },
        complete: function(data){
          document.body.classList.remove('loading');
        }
      });
    }
  };

  var insertContactResponse = function(html) {
    contactForm.innerHTML = html;
  };

  var disposeContactForm = function() {
    if(submitButton === null) return;
    submitButton.removeEventListener('click', submitForm);
  };

  var validateForm = function() {
  	if(isValidEmail(emailInput.value) === false) {
  		alert('Please enter a valid email');
  		emailInput.focus();
  		return false;
  	}
  	if (document.getElementById("message").value.length === 0) {
  		alert('Please enter a message');
  		messageInput.focus();
  		return false;
  	}
    return true;
  }

  var isValidEmail = function(email) {
      var emailRegex = new RegExp(/^[+\w.-]+@\w[\w.-]+\.[\w.-]*[a-z][a-z]$/i);
      return emailRegex.test(email);
  };

  var validateEmail = function(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  };

  /* Pronunciation hover/click ----------------------------------------*/

  var pronounceEl = null;

  var buildCacheflowePronunciation = function() {
    pronounceEl = el.querySelector('.pronounce-cacheflowe');
    if(pronounceEl !== null) {
      pronounceEl.addEventListener('click', sayMyName);
    }
  };

  var sayMyName = function(e) {
    e.preventDefault();
    window.cacheflowe.sayCacheflowe();
  };

  var disposeCacheflowePronunciation = function() {
    if(pronounceEl !== null) {
      pronounceEl.removeEventListener('click', sayMyName);
      pronounceEl = null;
    }
  };

  /* View lifecycle ----------------------------------------*/
	var dispose = function() {
    disposeLightboxLinks();
    disposeShareLinks();
    disposeContactForm();
    disposeCacheflowePronunciation();
    window.embetter.utils.disposePlayers();
    // window.cacheCart.disposeLinks(el);
	};

	init();

	return {
		dispose : dispose
	};
};

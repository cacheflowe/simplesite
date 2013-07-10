var AreaHome = function( el ) {
	
	var _crops = [];
	console.log('yah!');
	var _scroller = null;
	var _scroll_outer = $(el).find('.scroll_outer');
	var _scroll_inner = $(el).find('.scroll_inner');
	
	var init = function() {
		setUpEmailButton();
    setUpFormSubmit();
    
		// if( _scroll_outer.length == 1 ) buildScroller();
		var totalWidth = 0;
		
		// init ImageCrops - start hidden and fade in when loaded
		var crops = $(el).find('.image_crop');
		crops.css({opacity:0});
		
		// attach a load listener to each image in the incoming ajax snippet
		for (var i=0; i < crops.length; i++) {
			(function(){
				var crop = crops[i];
				var cropImg = $( crop ).find('img')[0];
				cropImg.onload = function(){
					_crops.push( new ImageCrop( crop, 370, 370, cropImg.width, cropImg.height, ImageCrop.CROP ) );
					$(crop).animate({ opacity: 1 }, 200);
					cropImg.onload = null;
					
					// increase size of container
					totalWidth += $(crop).width() + 20;
					_scroll_inner.css({ width:totalWidth });
					if( _scroller ) _scroller.setOrientation(_scroller.HORIZONTAL );
				};
			})();
		};
	};
	
	
	var buildScroller = function() {
	  // attach scrollbar elements
	  _scroll_outer.append('<div class="scroll_bar"><div class="scroll_bar_pill"></div></div>');
	  _scroll_outer.append('<a href="#prev" id="prev" class="gallery_prev_next">&lt; MORE</a><a href="#next" id="next" class="gallery_prev_next">MORE &gt;</a>');
  	var _scroll_bar = _scroll_outer.find('.scroll_bar');
  	
  	// a little extra code to prevent clicking while dragging
  	_scrollDelegate = {
			touchStart : function(event) {
				_scroll_inner.find('a').bind('click',function(e){ e.preventDefault(); return false; });
			},
			touchEnd : function(didMove) {
        if( didMove == false ) {
          _scroll_inner.find('a').unbind();
        }
			},
			touchEnter : function() {
        _scroll_outer.find('.gallery_prev_next').fadeIn();
			},
			touchLeave : function() {
        _scroll_outer.find('.gallery_prev_next').fadeOut();
			},
			handleDestination : function(){},
			updatePosition : function(){}
		};
  	
    _scroll_outer.css({ width:$(window).width() });
		_scroller = new TouchScroller( _scroll_outer[0], _scroll_inner[0], _scroll_bar[0], new Cursor('../images/cur/openhand.cur','../images/cur/closedhand.cur'), false, _scrollDelegate );
		
		_scroll_outer.find('.gallery_prev_next').bind('click',function(e){
		  e.preventDefault();
		  if( this.id == 'prev' ) {
		    _scroller.setOffsetPosition( -$(window).width() * 0.75 );
		  } else {
		    _scroller.setOffsetPosition( $(window).width() * 0.75 );
		  }
		});
	};
	
	var setUpEmailButton = function() {
	    $('#email_button').click(function(e){
	        e.preventDefault();
	        var subject = 'Frake Fine Art Inquiry';
        	document.location = 'mai'+'lto:'+'barbara'+'@'+'frakefineart'+'.c'+'om?'+'subject=' + subject;
	    });
	};
	
	var setUpFormSubmit = function() {
	    var form = $('#contact_form');
	    $('#contact_submit').click(function(e){
	        e.preventDefault();
	        
        	if (document.getElementById("email").value == "") {
        		alert('please enter your email.');
        		document.getElementById("email").focus();
        		return false;
        	}
        	if (document.getElementById("message").value == "") {
        		alert('please enter a message.');
        		document.getElementById("message").focus();
        		return false;
        	}
        	
        	// send it off
          $.ajax({
            type: "POST",
            url: 'php/'+'mail/cachemail.'+'p'+'hp',
            data: form.serialize(),
          }).done(function( msg ) {
            $('#form_holder').html( msg );
          });        	
	    });
    	
	};
	
	var validateEmail = function(email) {
      var regex = new RegExp(/^([a-zA-Z0-9_.-])+@[a-zA-Z_.]+?\.[a-zA-Z]{2,3}$/);
      return regex.test( email );
    };
	
	
	var dispose = function() {
	  // scroller
		if( _scroller ) _scroller.dispose();
		// clean up crops
		for (var i=0; i < _crops.length; i++) {
			_crops[i].dispose;
		};
		_crops.splice(0);
		// email button
		$('#email_button').unbind();
	};
	
	init();
	
	return {
		dispose : dispose
	};
};
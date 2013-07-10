var AreaContact = function( data ) {
	
	var init = function() {
        setUpEmailButton();
        setUpFormSubmit();
	};
	
	var setUpEmailButton = function() {
	    $('#email_button').click(function(e){
	        e.preventDefault();
	        var subject = 'Site Starter Inquiry';
        	document.location = 'mai'+'lto:'+'test'+'@'+'user'+'.c'+'om?'+'subject=' + subject;
	    });
	};
	
	var setUpFormSubmit = function() {
	    var form = $('#contact_form');
	    console.log(form);
	    console.log($('#contact_submit'));
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
	    });
    	
	};
	
	var validateEmail = function(email) {
      var regex = new RegExp(/^([a-zA-Z0-9_.-])+@[a-zA-Z_.]+?\.[a-zA-Z]{2,3}$/);
      return regex.test( email );
    };
	
	var dispose = function() {
        $('#email_button').unbind();
	};
	
	init();
	
	return {
		dispose : dispose
	};
};
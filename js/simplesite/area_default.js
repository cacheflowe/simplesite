var AreaDefault = function( data ) {
	
	var init = function() {
    AppComponents.updateNav( data );
	};
	
	var dispose = function() {

	};
	
	init();
	
	return {
		dispose : dispose
	};
};
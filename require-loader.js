require (['../../js/jquery-1.6.2.min'], function ($) {
	require({
		baseUrl: '../../js/'
	}, [
		"navigation",
		"add",
		"edit"
	], function( 
		nav,
		add,
		edit
	) {
		// Show/Hide password
		jQuery ("td.passphrase").bind ("click", function ( e ) {
				jQuery (this).find("span.mask").toggle().parent().find(".password-actual").toggle();
		});
	});
});
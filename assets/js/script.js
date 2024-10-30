jQuery('document').ready( function($) {

	'use strict';

	function getCookie(cname) {
	  let name = cname + "=";
	  let decodedCookie = decodeURIComponent(document.cookie);
	  let ca = decodedCookie.split(';');
	  for(let i = 0; i <ca.length; i++) {
	    let c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	  }
	  return "";
	}

	$(".lspw-wpml-popup").prependTo('body');


	$('.button#popup-settings-button').click( function(){
		$('.lspw-wpml-popup').show();
	});

	var language  = window.navigator.language;
	var lang_code = language.split('-');

	if( ! getCookie('wpml_lspw_current_language').length && wpml_var.active_lang != lang_code[0] ) {

		$('.lspw-wpml-popup').show();
	}


	$('.wpml_language_dropdown').select2({
		dropdownParent: $('#popup-form-language')
	});


	$('#popup-form-language-close').click( function(){
		$('.lspw-wpml-popup').hide();
	});


	$('.lspw-wpml-popup-background').click( function(){
		$('.lspw-wpml-popup').hide();
	});

});

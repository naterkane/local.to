/**
 * @author naterkane
 */
jQuery.noConflict();
jQuery(document).ready(function(){
		// hides a flashmessage shortly after loading
		jQuery("#flashMessage").slideDown(1000);
		var hideFlashMessage = setTimeout(function(){jQuery("#flashMessage").slideUp(1000);},3000);
	});

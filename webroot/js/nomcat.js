/**
 * @author naterkane
 */
jQuery.noConflict();
jQuery(document).ready(function(){
		// hides a flashmessage shortly after loading
		jQuery("#flashMessage").css({
			"position": "absolute",
			"top": "0pt",
			"left": "0pt",
			"color": "#000000",
			"border-bottom-width": "2px",
			"border-bottom-style": "solid",
			"border-bottom-color": "rgba(0, 0, 0, 0.15)",
			"width": "100%",
			"cursor": "pointer",
			"zindex": "99999"
		})
		jQuery("#flashMessage").slideDown(1000);
		var hideFlashMessage = setTimeout(function(){jQuery("#flashMessage").slideUp(1000);},3000);
	});

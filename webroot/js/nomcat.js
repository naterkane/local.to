/**
 * @author naterkane
 */
if(!(('console' in window) && ('log' in console)))
    window.console = { log: function(){} };
$(document).ready(function(){
		// hides a flashmessage shortly after loading
		$("#flashMessage").slideDown(1000);
		var hideFlashMessage = setTimeout(function(){jQuery("#flashMessage").slideUp(1000);},3000);
	});

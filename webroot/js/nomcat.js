/**
 * @author naterkane
 */
window.log=function(){var a="history";log[a]=log[a]||[];log[a].push(arguments);window.console&&console.log[console.firebug?"apply":"call"](console,Array.prototype.slice.call(arguments))};window.logargs=function(a){log(a,arguments.callee.caller.arguments)};
$(document).ready(function(){
		// hides a flashmessage shortly after loading
		$("#flashMessage").slideDown(1000);
		var hideFlashMessage = setTimeout(function(){jQuery("#flashMessage").slideUp(1000);},3000);
	});

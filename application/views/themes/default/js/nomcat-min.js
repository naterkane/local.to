var nc={myLeftColumn:null,myRightColumn:null,myLeftHeight:null,myRightHeight:null,context:null,charLimit:140,charCountElem:null,commentBoxElem:null,defaultContext:null,defaultContextFontSize:null,btn:null,m:null,str:null,re:new RegExp(/^\s*@(\w+)\W+/),dm:new RegExp(/^\s*[dD][mM]?\s+(?:(\w+)\W+)?/),dmg:new RegExp(/^\s*[dD][mM]?\s\!+(?:(\w+)\W+)?/),t:new RegExp(/^\s*!(\w+)\W+/),updateCount:function(b){var a=nc.charLimit-this.value.length;nc.charCountElem.innerHTML=a.toString();if(a<0){$(nc.charCountElem).addClass("alert");$(nc.btn).attr("disabled",true).addClass("disabled")}else{$(nc.charCountElem).removeClass("alert");$(nc.btn).attr("disabled",false).removeClass("disabled")}if(nc.context){nc.str=this.value;if(nc.str.match(nc.re)){nc.m=nc.re.exec(this.value);nc.context.innerHTML="Reply to "+nc.m[1]+":";nc.setButtonText("Reply")}else{if(nc.str.match(nc.dmg)){nc.m=nc.dmg.exec(this.value);nc.context.innerHTML=(nc.m[1])?"Send a private message to members of "+nc.m[1]+":":"Send a private message to:";nc.setButtonText("Send")}else{if(nc.str.match(nc.dm)){nc.m=nc.dm.exec(this.value);nc.context.innerHTML=(nc.m[1])?"Send a private message to "+nc.m[1]+":":"Send a private message to:";nc.setButtonText("Send")}else{if(nc.str.match(nc.t)){nc.m=nc.t.exec(this.value);nc.context.innerHTML="Share something with "+nc.m[1]+":";nc.setButtonText("Post")}else{nc.context.innerHTML=nc.defaultContext;nc.setButtonText("Update")}}}}nc.setContextSize()}return true},setContextSize:function(){if(nc.context.innerHTML.length>35){if(nc.defaultContextFontSize===null){nc.defaultContextFontSize=$(nc.context).css("font-size")}return $(nc.context).css("font-size",(100-(nc.context.innerHTML.length-35))*1.75+"%")}else{return $(nc.context).css("font-size",nc.defaultContextFontSize)}},setButtonText:function(a){if(typeof nc.btn=="undefined"){return false}if(nc.btn.value){nc.btn.value=a}else{nc.btn.innerHTML=a}return true},equalHeight:function(){nc.myLeftColumn=document.getElementById("sidebar");nc.myRightColumn=document.getElementById("main");nc.myLeftHeight=$(nc.myLeftColumn).height();nc.myRightHeight=$(nc.myRightColumn).height();if(nc.myLeftHeight>nc.myRightHeight){$(nc.myRightColumn).height(nc.myLeftHeight)}else{$(nc.myLeftColumn).height(nc.myRightHeight)}return true},setCaretPosition:function(c,b){if(c!==null){if(c.createTextRange){var a=c.createTextRange();a.move("character",b);a.select()}else{if(c.selectionStart!==null){c.focus();c.setSelectionRange(b,b)}c.focus()}}return true},_init:function(){nc.equalHeight();nc.charCountElem=$("#character-count")[0];nc.commentBoxElem=document.getElementById("comment-box");nc.context=($("#message-context h3")[0])?$("#message-context h3")[0]:$("#message-context")[0];nc.btn=$("#update-btn")[0];if(typeof nc.context!="undefined"){nc.defaultContext=nc.context.innerHTML}else{nc.setButtonText("Send")}$(".form").submit(function(){if($(nc.btn).attr("disabled")==true){return false}$(nc.btn).attr("disabled",true).addClass("disabled");$("#flashMessage").innerHTML="Got it! Please hold on for just a sec.";$("#flashMessage").slideDown(1000);return true});if(nc.commentBoxElem){nc.charCountElem.innerHTML=(nc.charLimit-nc.commentBoxElem.value.length).toString();nc.commentBoxElem.onkeyup=nc.updateCount;$(nc.commentBoxElem).trigger("onkeyup");nc.setCaretPosition(nc.commentBoxElem,nc.commentBoxElem.value.length)}else{if(nc.charCountElem){nc.charCountElem.innerHTML=(nc.charLimit-nc.commentBoxElem.value.length).toString();nc.commentBoxElem.onkeyup=nc.updateCount;$(nc.commentBoxElem).trigger("onkeyup")}}return true}};$(document).ready(function(){nc._init()});
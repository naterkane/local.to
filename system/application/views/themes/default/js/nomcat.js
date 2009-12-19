var nc = {
	myLeftColumn: null,
	myRightColumn: null,
	myLeftHeight: null,
	myRightHeight: null,
	context: null,
	charLimit: 140,
	charCountElem: null,
	commentBoxElem: null,
	defaultContext: null,
	defaultContextFontSize: null,
	btn: null,
	m: null,
	str: null,
	re: new RegExp(/^\s*@(\w+)\W+/),
	dm: new RegExp(/^\s*[dD][mM]?\s+(?:(\w+)\W+)?/),
	dmg: new RegExp(/^\s*[dD][mM]?\s\!+(?:(\w+)\W+)?/),
	t: new RegExp(/^\s*!(\w+)\W+/),
	/**
	* 
	* @param {Object} e Event
	*/
	updateCount: function(e) {
		var charCount = nc.charLimit - this.value.length;
		nc.charCountElem.innerHTML = charCount.toString();
		if(charCount<0) {
			$(nc.charCountElem).addClass("alert");
			$(nc.btn).attr("disabled",true).addClass("disabled");
		}
		else {
			$(nc.charCountElem).removeClass("alert");
			$(nc.btn).attr("disabled",false).removeClass("disabled");
		}
		if (nc.context){ 
			nc.str = this.value;
			if (nc.str.match(nc.re)){
				nc.m = nc.re.exec(this.value);
				nc.context.innerHTML = "Reply to "+nc.m[1]+":";
				nc.setButtonText("Reply");
			} else if (nc.str.match(nc.dmg)){
				nc.m = nc.dmg.exec(this.value);
				nc.context.innerHTML = (nc.m[1])?"Send a private message to members of " + nc.m[1] + ":":"Send a private message to:";	
				nc.setButtonText("Send");
			} else if (nc.str.match(nc.dm)){
				nc.m = nc.dm.exec(this.value);
				nc.context.innerHTML = (nc.m[1])?"Send a private message to " + nc.m[1] + ":":"Send a private message to:";	
				nc.setButtonText("Send");
			} else if (nc.str.match(nc.t)){
				nc.m = nc.t.exec(this.value);
				nc.context.innerHTML = "Share something with "+nc.m[1]+":";
				nc.setButtonText("Post");
			} else {
				nc.context.innerHTML = nc.defaultContext;
				nc.setButtonText("Update");
			}
			
			nc.setContextSize();
		}
		return true;
	}, // updateCount
	/**
	 * adjust the font size of the main label above the text area to accomodate direct messages to groups with long names.
	 */
	setContextSize: function() {
		if (nc.context.innerHTML.length > 35) {
			if (nc.defaultContextFontSize === null){
				nc.defaultContextFontSize = $(nc.context).css('font-size');
			}
			return $(nc.context).css("font-size", (100-(nc.context.innerHTML.length-35))*1.75 + "%");
			//console.log((100-(nc.context.innerHTML.length-35))*1.75);
		} else {
			return $(nc.context).css("font-size", nc.defaultContextFontSize);
		}
	},
	/**
	 * sets the display text of the post button
	 * @param {String} s
	 * @return {Boolean} true if {@link nc.btn} is defined; false on faliure
	 */
	setButtonText: function(s) {
		if (typeof nc.btn == "undefined") {
			return false;
		}
		if (nc.btn.value) {
			nc.btn.value = s;
		}
		else {
			nc.btn.innerHTML = s;
		}
		return true;
	}, // setButtonText
  	/**
  	 * sets the height of the left and right columns to match each other
  	 */
	equalHeight: function(){
		nc.myLeftColumn = document.getElementById('sidebar');
		nc.myRightColumn = document.getElementById('main');
		nc.myLeftHeight = $(nc.myLeftColumn).height();
		nc.myRightHeight = $(nc.myRightColumn).height();
		
		if (nc.myLeftHeight > nc.myRightHeight) {
			$(nc.myRightColumn).height(nc.myLeftHeight);
		} else {
			$(nc.myLeftColumn).height(nc.myRightHeight);
		}
		return true;
	}, // equalHeight
  	/**
  	 * sets the cursor position to the end of the text in a text area
  	 * @param {Object} elem
  	 * @param {Object} caretPos
  	 */
	setCaretPosition: function(elem, caretPos) {
		if(elem !== null) {
			if(elem.createTextRange) {
				var range = elem.createTextRange();
				range.move('character', caretPos);
				range.select();
			}
			else {
				if(elem.selectionStart !== null) {
					elem.focus();
					elem.setSelectionRange(caretPos, caretPos);
				}
				elem.focus();
			}
		}
		return true;
	}, // setCaretPosition
  
  	tooltip: function(){
		$(".post-actions a").each(function(){
			$(this).simpletip({
			   position: "right",
			   offset: [10, 0],
			   content: $(this).attr('title')
			})
		});
	},
  
  	/**
  	 * initialization 
  	 */
	_init: function() {
		//make sure both columns go all the way down to the footer
		//nc.equalHeight();
		//character counter
		nc.charCountElem = $('#character-count')[0];
		nc.commentBoxElem = document.getElementById('comment-box');
		nc.context = ($('#message-context h3')[0])?$('#message-context h3')[0] : $('#message-context')[0];
		nc.btn = $('#update-btn')[0];
		if (typeof nc.context != "undefined") {
			nc.defaultContext = nc.context.innerHTML;
		} else {
			nc.setButtonText("Send");
		}
		
		$(".form").submit(function() {
			if ($(nc.btn).attr("disabled")== true){
				return false;
			}
			$(nc.btn).attr("disabled",true).addClass("disabled");
			$("#flashMessage").innerHTML = "Got it! Please hold on for just a sec.";
			$("#flashMessage").slideDown(1000);
			return true;
		});
		if (nc.commentBoxElem) {
			nc.charCountElem.innerHTML = (nc.charLimit - nc.commentBoxElem.value.length).toString();
			nc.commentBoxElem.onkeyup = nc.updateCount;
			$(nc.commentBoxElem).trigger('onkeyup');//.focus();
			nc.setCaretPosition(nc.commentBoxElem,nc.commentBoxElem.value.length);
		} else if (nc.charCountElem) {
			nc.charCountElem.innerHTML = (nc.charLimit - nc.commentBoxElem.value.length).toString();
			nc.commentBoxElem.onkeyup = nc.updateCount;
			$(nc.commentBoxElem).trigger('onkeyup');//.focus();
		}
		//nc.tooltip();
		return true;
	} // _init 

}; // nc


$(document).ready(function() {
	nc._init();
});
/**
 * jquery.simpletip 1.3.1. A simple tooltip plugin
 * 
 * Copyright (c) 2009 Craig Thompson
 * http://craigsworks.com
 *
 * Licensed under GPLv3
 * http://www.opensource.org/licenses/gpl-3.0.html
 *
 * Launch  : February 2009
 * Version : 1.3.1
 * Released: February 5, 2009 - 11:04am
 */
//eval(function(p,a,c,k,e,r){e=function(c){return(c<62?'':e(parseInt(c/62)))+((c=c%62)>35?String.fromCharCode(c+29):c.toString(36))};if('0'.replace(0,e)==0){while(c--)r[e(c)]=k[c];k=[function(e){return r[e]||e}];e=function(){return'([3-9a-zB-Z]|1\\w)'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(6($){6 Z(f,3){4 7=n;f=b(f);4 5=b(document.createElement(\'div\')).B(3.10).B((3.p)?3.11:\'\').B((3.C)?3.12:\'\').13(3.q).appendTo(f);a(!3.14)5.t();o 5.r();a(!3.C){f.hover(6(c){7.t(c)},6(){7.r()});a(!3.p){f.mousemove(6(c){a(5.D(\'N\')!==\'u\')7.E(c)})}}o{f.click(6(c){a(c.15===f.16(0)){a(5.D(\'N\')!==\'u\')7.r();o 7.t()}});b(v).mousedown(6(c){a(5.D(\'N\')!==\'u\'){4 17=(3.O)?b(c.15).parents(\'.5\').andSelf().filter(6(){d n===5.16(0)}).length:0;a(17===0)7.r()}})};b.18(7,{getVersion:6(){d[1,2,0]},getParent:6(){d f},getTooltip:6(){d 5},getPos:6(){d 5.i()},19:6(8,9){4 e=f.i();a(s 8==\'F\')8=G(8)+e.k;a(s 9==\'F\')9=G(9)+e.l;5.D({k:8,l:9});d 7},t:6(c){3.1a.m(7);7.E((3.p)?P:c);Q(3.1b){g\'H\':5.fadeIn(3.I);h;g\'1c\':5.slideDown(3.I,7.E);h;g\'1d\':3.1e.m(5,3.I);h;w:g\'u\':5.t();h};5.B(3.R);3.1f.m(7);d 7},r:6(){3.1g.m(7);Q(3.1h){g\'H\':5.fadeOut(3.J);h;g\'1c\':5.slideUp(3.J);h;g\'1d\':3.1i.m(5,3.J);h;w:g\'u\':5.r();h};5.removeClass(3.R);3.1j.m(7);d 7},update:6(q){5.13(q);3.q=q;d 7},1k:6(1l,K){3.1m.m(7);5.1k(1l,K,6(){3.1n.m(7)});d 7},L:6(8,9){4 1o=8+5.S();4 1p=9+5.T();4 1q=b(v).width()+b(v).scrollLeft();4 1r=b(v).height()+b(v).scrollTop();d[(1o>=1q),(1p>=1r)]},E:6(c){4 x=5.S();4 y=5.T();a(!c&&3.p){a(3.j.constructor==Array){8=G(3.j[0]);9=G(3.j[1])}o a(b(3.j).attr(\'nodeType\')===1){4 i=b(3.j).i();8=i.k;9=i.l}o{4 e=f.i();4 z=f.S();4 M=f.T();Q(3.j){g\'l\':4 8=e.k-(x/2)+(z/2);4 9=e.l-y;h;g\'bottom\':4 8=e.k-(x/2)+(z/2);4 9=e.l+M;h;g\'k\':4 8=e.k-x;4 9=e.l-(y/2)+(M/2);h;g\'right\':4 8=e.k+z;4 9=e.l-(y/2)+(M/2);h;w:g\'w\':4 8=(z/2)+e.k+20;4 9=e.l;h}}}o{4 8=c.pageX;4 9=c.pageY};a(s 3.j!=\'object\'){8=8+3.i[0];9=9+3.i[1];a(3.L){4 U=7.L(8,9);a(U[0])8=8-(x/2)-(2*3.i[0]);a(U[1])9=9-(y/2)-(2*3.i[1])}}o{a(s 3.j[0]=="F")8=1s(8);a(s 3.j[1]=="F")9=1s(9)};7.19(8,9);d 7}})};b.fn.V=6(3){4 W=b(n).eq(s 3==\'number\'?3:0).K("V");a(W)d W;4 X={q:\'A simple 5\',C:1t,O:1t,14:Y,j:\'w\',i:[0,0],L:Y,p:Y,1b:\'H\',I:1u,1e:P,1h:\'H\',J:1u,1i:P,10:\'5\',R:\'active\',11:\'p\',12:\'C\',focusClass:\'O\',1a:6(){},1f:6(){},1g:6(){},1j:6(){},1m:6(){},1n:6(){}};b.18(X,3);n.each(6(){4 el=new Z(b(n),X);b(n).K("V",el)});d n}})();',[],93,'|||conf|var|tooltip|function|self|posX|posY|if|jQuery|event|return|elemPos|elem|case|break|offset|position|left|top|call|this|else|fixed|content|hide|typeof|show|none|window|default|tooltipWidth|tooltipHeight|elemWidth||addClass|persistent|css|updatePos|string|parseInt|fade|showTime|hideTime|data|boundryCheck|elemHeight|display|focus|null|switch|activeClass|outerWidth|outerHeight|overflow|simpletip|api|defaultConf|true|Simpletip|baseClass|fixedClass|persistentClass|html|hidden|target|get|check|extend|setPos|onBeforeShow|showEffect|slide|custom|showCustom|onShow|onBeforeHide|hideEffect|hideCustom|onHide|load|uri|beforeContentLoad|onContentLoad|newX|newY|windowWidth|windowHeight|String|false|150'.split('|'),0,{}))
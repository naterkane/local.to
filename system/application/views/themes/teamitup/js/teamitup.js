(function() {

var tiu = {
  context:null,
  charLimit: 140,
  charCountElem: null,
  commentBoxElem: null,
  btn: null,
  m: null,
  str: null,
  re: new RegExp(/^\s*@(\w+)\W+/),
  dm: new RegExp(/^\s*[dD][mM]?\s+(?:(\w+)\W+)?/),
  t: new RegExp(/^\s*!(\w+)\W+/),
  updateCount: function(e) {
    var charCount = tiu.charLimit - this.value.length;
    tiu.charCountElem.innerHTML = charCount.toString();
    if(charCount<0) {
      tiu.charCountElem.className = 'character-count alert';
    }
    else {
      tiu.charCountElem.className = 'character-count';
    }
	tiu.context = document.getElementById('message-context');
	if (tiu.context){ 
		tiu.str = this.value;
		if (tiu.str.match(tiu.re)){
			tiu.m = tiu.re.exec(this.value);
			tiu.context.innerHTML = "Reply to "+tiu.m[1]+":";
			tiu.btn.innerHTML = "Reply";
		} else if (tiu.str.match(tiu.dm)){
			tiu.m = tiu.dm.exec(this.value);
			tiu.context.innerHTML = (tiu.m[1])?"Direct message " + tiu.m[1] + ":":"Direct message:";	
			tiu.btn.innerHTML = "Send";
		} else if (tiu.str.match(tiu.t)){
			tiu.m = tiu.t.exec(this.value);
			tiu.context.innerHTML = "Share something with "+tiu.m[1]+":";
			tiu.btn.innerHTML = "Post";
		} else {
			tiu.context.innerHTML = "What are you doing?";
			tiu.btn.innerHTML = "Update";
		}
	}
	
  }, // updateCount
	
  _init: function() {
    tiu.charCountElem = document.getElementById('character-count');
    tiu.commentBoxElem = document.getElementById('comment-box');
	tiu.context = document.getElementById('message-context');
	tiu.btn = document.getElementById('update-btn');
    if(tiu.commentBoxElem) {
      tiu.charCountElem.innerHTML = (tiu.charLimit - tiu.commentBoxElem.value.length).toString();
      tiu.commentBoxElem.onkeyup = tiu.updateCount;
    }
    
  } // _init 

}; // tiu

tiu._init();

})();
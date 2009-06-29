(function() {

var nc = {
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
    var charCount = nc.charLimit - this.value.length;
    nc.charCountElem.innerHTML = charCount.toString();
    if(charCount<0) {
      nc.charCountElem.className = 'character-count alert';
    }
    else {
      nc.charCountElem.className = 'character-count';
    }
	nc.context = document.getElementById('message-context');
	if (nc.context){ 
		nc.str = this.value;
		if (nc.str.match(nc.re)){
			nc.m = nc.re.exec(this.value);
			nc.context.innerHTML = "Reply to "+nc.m[1]+":";
			nc.btn.innerHTML = "Reply";
		} else if (nc.str.match(nc.dm)){
			nc.m = nc.dm.exec(this.value);
			nc.context.innerHTML = (nc.m[1])?"Direct message " + nc.m[1] + ":":"Direct message:";	
			nc.btn.innerHTML = "Send";
		} else if (nc.str.match(nc.t)){
			nc.m = nc.t.exec(this.value);
			nc.context.innerHTML = "Share something with "+nc.m[1]+":";
			nc.btn.innerHTML = "Post";
		} else {
			nc.context.innerHTML = "What are you doing?";
			nc.btn.innerHTML = "Update";
		}
	}
	
  }, // updateCount
	
  _init: function() {
    nc.charCountElem = document.getElementById('character-count');
    nc.commentBoxElem = document.getElementById('comment-box');
	nc.context = document.getElementById('message-context');
	nc.btn = document.getElementById('update-btn');
    if(nc.commentBoxElem) {
      nc.charCountElem.innerHTML = (nc.charLimit - nc.commentBoxElem.value.length).toString();
      nc.commentBoxElem.onkeyup = nc.updateCount;
    }
    
  } // _init 

}; // nc

nc._init();

})();
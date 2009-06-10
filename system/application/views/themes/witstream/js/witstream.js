(function() {

var ws = {
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
    var charCount = ws.charLimit - this.value.length;
    ws.charCountElem.innerHTML = charCount.toString();
    if(charCount<0) {
      ws.charCountElem.className = 'character-count alert';
    }
    else {
      ws.charCountElem.className = 'character-count';
    }
	ws.context = document.getElementById('message-context');
	if (ws.context){ 
		ws.str = this.value;
		if (ws.str.match(ws.re)){
			ws.m = ws.re.exec(this.value);
			ws.context.innerHTML = "Reply to "+ws.m[1]+":";
			ws.btn.innerHTML = "Reply";
		} else if (ws.str.match(ws.dm)){
			ws.m = ws.dm.exec(this.value);
			ws.context.innerHTML = (ws.m[1])?"Private message to " + ws.m[1] + ":":"Private message:";	
			ws.btn.innerHTML = "Send";
		} else if (ws.str.match(ws.t)){
			ws.m = ws.t.exec(this.value);
			ws.context.innerHTML = "Share something with "+ws.m[1]+":";
			ws.btn.innerHTML = "Post";
		} else {
			ws.context.innerHTML = "You think you&apos;re funny?";
			ws.btn.innerHTML = "Update";
		}
	}
	
  }, // updateCount
	
  _init: function() {
    ws.charCountElem = document.getElementById('character-count');
    ws.commentBoxElem = document.getElementById('comment-box');
	ws.context = document.getElementById('message-context');
	ws.btn = document.getElementById('update-btn');
    if(ws.commentBoxElem) {
      ws.charCountElem.innerHTML = (ws.charLimit - ws.commentBoxElem.value.length).toString();
      ws.commentBoxElem.onkeyup = ws.updateCount;
    }
    
  } // _init 

}; // ws

ws._init();

})();
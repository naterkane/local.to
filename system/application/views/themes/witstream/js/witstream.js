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
			ws.context.innerHTML = "You think you&#146;re funny?";
			ws.btn.innerHTML = "Update";
		}
	}
	
  }, // updateCount
  /******
	* wordWrap to firefox for big words
	* Creative Commons license * Version: 1.0 - 26/04/2006
	* @author Micox - Náiron J.C.G - micoxjcg@yahoo.com.br - http://elmicoxcodes.blogspot.com
	* @description call the function on onload of body element. put the class "word-wrap" on elements to wordwrap
	* 
	* @author Nater Kane nater@wearenom.com Translated to english 
	*******/
  wordWrap: function(){
	
	    var larg_total,larg_carac,quant_quebra,pos_quebra;
	    var elementos,quem, caracs, texto, display_orig;
	    
	    elementos = document.getElementsByTagName("p")
	    
	    for(var i=0; i<elementos.length;i++){
	        if(elementos[i].className=="message-text"){
	            quem = elementos[i];
	            
	            quem.innerHTML = String(quem.innerHTML).replace(/ /g,"Ø")
	            texto = String(quem.innerHTML)
	            
	            quem.innerHTML = " "
	            
	            display_orig = quem.style.display;
	            quem.style.display="block";
	            larg_oficial = quem.offsetWidth;
	            //alert("oficial: " + larg_oficial)
	            //alert("display " + quem.style.display)
	            if(!document.all) quem.style.display="table";
	            //alert("display " + quem.style.display)
	            quem.innerHTML = texto;
	            larg_total = quem.offsetWidth;
	            //alert("total: " + larg_total)
	            
	            pos_quebra = 0;
	            caracs = texto.length;
	            texto = texto.replace(/Ø/g," ")
	            larg_carac = larg_total / caracs
	            if(larg_total>larg_oficial){
	                quant_quebra = parseInt(larg_oficial/larg_carac)
	                quant_quebra = quant_quebra - (parseInt(quant_quebra/6)) //quanto menor o num, maior a garantia;
	                quem.innerHTML = ""
	                while(pos_quebra<=caracs){
	                    quem.innerHTML = quem.innerHTML + texto.substring(pos_quebra,pos_quebra + quant_quebra) + " "
	                    pos_quebra = pos_quebra + quant_quebra;
	                }
	            }else{
	                quem.innerHTML = texto;
	            }//end if do larg_total>larg_oficial
	            quem.style.display = display_orig;
	        }//end if do word wrap
	    }//end for loop dos elementos
	},
	checkForSubmit: function(e){
		if ((e.keyCode != null && e.keyCode == 13) || e.charCode == 13){
			form = document.getElementById('postform');
			mt = document.getElementById('message-context');
			ta = document.getElementById('comment-box');
			
			mt.innerHTML = "Sending...";
			ta.className += " disabled";
			ta.onkeyup = null;
			ms = document.createElement("input");
			ms.type = "hidden";
			ms.value = ta.value;
			ms.name = ta.name;
			form.appendChild(ms);
			ta.name += "disabled";
			ta.disabled = true;
			form.submit();
		}
	},
  _init: function() {
    ws.charCountElem = document.getElementById('character-count');
    ws.commentBoxElem = document.getElementById('comment-box');
	ws.context = document.getElementById('message-context');
	ws.btn = document.getElementById('update-btn');
    if(ws.commentBoxElem) {
      ws.charCountElem.innerHTML = (ws.charLimit - ws.commentBoxElem.value.length).toString();
      ws.commentBoxElem.onkeyup = ws.updateCount;
	  ws.commentBoxElem.onkeydown = ws.checkForSubmit;
    }
    //ws.wordWrap();
  } // _init 

}; // ws

ws._init();

})();
var IE6 = (navigator.userAgent.indexOf("MSIE 6")>=0) ? true : false;
if(IE6){

	$(function(){
		
		$("<div>")
			.css({
				'position': 'absolute',
				'top': '0px',
				'left': '0px',
				backgroundColor: 'black',
				'opacity': '0.75',
				'width': '100%',
				'height': $(window).height(),
				zIndex: 5000
			})
			.appendTo("body");
			
		$("<div><img src='/img/no-ie6.png' alt='' style='float: left;'/><p><br /><h2>Oh my gosh!</h2><h3>As you can see... things can look pretty crappy in Internet Explorer 6.</h3><br /><br />Our code utilizes many features available to modern browsers. It can be <i>really darn expensive</i> to fancy, modern stuff with a browser that's a <b>decade</b> old. <br/><br/>We want you to use this site with confidence, so please <a href='http://browsehappy.com/browsers/'><b>upgrade your browser</b></a> and come right back!</p>")
			.css({
				backgroundColor: 'white',
				'top': '50%',
				'left': '50%',
				marginLeft: -310,
				marginTop: -100,
				width: 610,
				paddingRight: 10,
				height: 220,
				'position': 'absolute',
				zIndex: 6000
			})
			.appendTo("body");
	});		
}
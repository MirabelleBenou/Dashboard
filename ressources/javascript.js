function render_module(name, args) {
	// tester connexion internet ici ?
	var connexion = new Boolean(true);

	if(name == "rss"){
		var req = new XMLHttpRequest();
		req.open('HEAD', '216.58.212.196', true);
		req.onreadystatechange = function (aEvt) {
	  		if((req.readyState === XMLHttpRequest.DONE) && (req.status === 200)) {
	  			connexion = true;
			}
			else {
				connexion = false;
			}
		};
		req.send(null);
	}
	
	if(connexion == true){
		$('#' + name).load('modules/' + name + '/' + name + '.php?' + args);
	}
	
}

function activate_module(name, seconds, args) {
	render_module(name, args);
	if(seconds > 0) {
		setInterval("render_module('"+name+"', '"+args+"')", (seconds * 1000));
	}
}

$(document).ready(function() {
	$('.middle').each(function(id, val) {
		var outer_height = $(val).height();
		var inner_height = $(val).children().first().height();
		var buffer = (outer_height - inner_height) / 2;
		var SEL = '#' + $(val).attr('id') + '>div';
		$(SEL).css('marginTop', buffer);
		$(SEL).css('marginBottom', buffer);
	});
});

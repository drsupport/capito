var page = require('webpage').create(),
	system = require('system'),
	url = 'http://zmtc.yarsagumbaforte.com';

page.open(url, function (status) {
	page.evaluate(function() {


	});

});
	page.onConsoleMessage = function(msg, lineNum, sourceId) {
		console.log(msg);
		phantom.exit();	
	};	



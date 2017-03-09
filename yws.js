var page = require('webpage').create(),
	system = require('system'),
	url = 'https://wordstat.yandex.ru/#!/?words=' +encodeURIComponent(system.args[1]); //достаем параметр, в котором передан наш url страницы, которую мы парсим

page.open(url, function (status) {
	page.injectJs('cdn/jquery/jquery.min.js');	//подключаем jquery.js
	page.render('log/' + system.args[2] + '.png');
	var productName = page.evaluate(function() {
		return $('.b-word-statistics__info').text();
	});

	console.log('productName: ' + productName);

	phantom.exit();
});



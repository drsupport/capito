function ajax(url, params, callback) {
    var xhr = new XMLHttpRequest();    
    xhr.open('POST', url, true);
    //xhr.withCredentials = true;
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function(){
        if (this.readyState == 4) {
            if (this.status == 200) {
                callback(JSON.parse(this.responseText));       
            } else {
            	callback(JSON.parse(this.responseText)); 
            }            
        }
    };
    xhr.send(JSON.stringify(params));
}
var page = require('webpage').create(),
	system = require('system'),
	url = 'https://wordstat.yandex.ru/#!/?words=' +encodeURIComponent(system.args[1]);
page.open(url, function (status) {
	page.includeJs('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', function () {
		//page.injectJs('http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js');	
		page.evaluate(function() {
			var login = document.getElementsByClassName('b-domik_type_popup')[0];   
			var username = document.getElementById('b-domik_popup-username');
			var password = document.getElementById('b-domik_popup-password');
			
			username.value = 'ivanov.vladimir.v';
			password.value = 'sp@rt@nec';
		});
	
	    setTimeout(function() {
	    	page.evaluate(function() {
				$(document).ready(function(){
					$('.b-form-button__input').click();
				});	
			});
		}, 5000);

	    setTimeout(function() {
	    	$response = page.evaluate(function() {
	    		var r = new Object();
				r.impressions  = parseInt(document.getElementsByClassName('b-word-statistics__info')[0].innerHTML.replace(/\D+/g,""));
				r.datetime = document.getElementsByClassName('b-word-statistics__last-update')[0].innerHTML.replace(/[^.\d]+/g,"")				
				return JSON.stringify(r); 
				/*var arg = '{"clientKey":"003fa7c1cd658bca6016eae7c179f012","task":{"type":"ImageToTextTask","body":"BASE64_BODY_HERE!","phrase":false,"case":false,"numeric":false,"math":0,"minLength":0,"maxLength":0}}';
				$.ajax({
				    async: false, // this
				    url: 'https://api.anti-captcha.com/createTask',
				    data: '',
				    type: 'post',
				    success: function (data) {			    	
				        console.log('ajax: ok');
				    },
				});*/
			});

			console.log($response);
			page.render('log/' + system.args[2] + '.png');
			phantom.exit();
		}, 10000);
	  

	});
});
page.onConsoleMessage = function(msg, lineNum, sourceId) {
	/*console.log($msg);*/
	phantom.exit();	
};	

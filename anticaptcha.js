function ajax(url, params, callback) {
    var xhr = new XMLHttpRequest();    
    xhr.open('POST', url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function(){
        if (this.readyState == 4) {
            if (this.status == 200) {
                callback(this.responseText);       
            } else {
                callback(this.responseText); 
            }            
        }
    };
    xhr.send(params);
}


var page = require('webpage').create(),
    system = require('system'),
    url = 'https://wordstat.yandex.ru/#!/history?words=test';
var args = system.args;
var clientKey = '003fa7c1cd658bca6016eae7c179f012';
page.open(url, function (status) {
    page.includeJs('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', function () {
        page.evaluate(function(args) {
            var login = document.getElementsByClassName('b-domik_type_popup')[0];   
            var username = document.getElementById('b-domik_popup-username');
            var password = document.getElementById('b-domik_popup-password');
            username.value = args[3];
            password.value = args[4];  
            console.log('login/password');
            $(document).ready(function(){
                $('.b-form-button__input').click();
            }); 
            console.log('log in');
        }, args);
        setTimeout(function() {
            page.evaluate(function() {
                $(document).ready(function(){
                    $('.b-form-button__input').click();
                }); 
            });
        }, 5000);
        setTimeout(function() {            
            var clipRect = page.evaluate(function() {
                var captcha = document.getElementsByClassName('b-popupa__content')[0];                              
                var clipRect = captcha.getBoundingClientRect();
                return clipRect;
            }, 'clipRect');                           
            page.clipRect = {
                top:    174,
                left:   395,
                width:  175,
                height: 71
            };
            var base64 = page.renderBase64('PNG');   
            var arg = '{"clientKey":"'+clientKey+'","task":{"type":"ImageToTextTask","body":"'+base64+'","phrase":false,"case":false,"numeric":false,"math":0,"minLength":0,"maxLength":0}}';            
            ajax('http://api.anti-captcha.com/createTask', arg, function(response){
                var response = JSON.parse(response);    
                console.log('antigate decoding ... task: '+response['taskId']);  
                var arg = '{"clientKey":"'+clientKey+'","taskId":"'+response['taskId']+'"}'; 
                var result;
                var getTaskResult = setInterval(function() { 
                    ajax('http://api.anti-captcha.com/getTaskResult', arg, function(response){
                        var response = JSON.parse(response);  
                        //if (typeof response!= "undefined") console.log('Я живой!');       
                        if(response['status']=='ready') {           
                            console.log('antigate decode: '+response['solution']['text']);  
                            result = response['solution']['text'];
                            clearInterval(getTaskResult);
                        }                                   
                    }); 
                }, 10000);   
                var captchaPush = setInterval(function() {  
                    if(result != undefined) {
                        var captcha_input = page.evaluate(function(result) {
                            var captcha_input = document.getElementsByClassName('b-form-input__input')[1];                             
                            //captcha_input.value = result.trim();  
                            function insertAtCaret(a,b){if(a){var c=a.scrollTop,d=0,e=a.selectionStart||"0"==a.selectionStart?"ff":!!document.selection&&"ie";if("ie"==e){a.focus();var f=document.selection.createRange();f.moveStart("character",-a.value.length),d=f.text.length}else"ff"==e&&(d=a.selectionStart);var g=a.value.substring(0,d),h=a.value.substring(d,a.value.length);if(a.value=g+b+h,d+=b.length,"ie"==e){a.focus();var i=document.selection.createRange();i.moveStart("character",-a.value.length),i.moveStart("character",d),i.moveEnd("character",0),i.select()}else"ff"==e&&(a.selectionStart=d,a.selectionEnd=d,a.focus());a.scrollTop=c}}
                            insertAtCaret(captcha_input, result.trim());
                            return captcha_input;
                        }, result);    
                        setTimeout(function() { 
                            //function clickFromPoint(a,b){var c=document.createEvent("MouseEvent"),d=document.elementFromPoint(a,b);c.initMouseEvent("click",!0,!0,window,null,a,b,0,0,!1,!1,!1,!1,0,null),d.dispatchEvent(c)}
                            //clickFromPoint(571, 266);                            
                            page.evaluate(function() {
                                $(document).ready(function(){
                                    $('.b-form-button__input').click();
                                }); 
                                //$(document).ready(function(){$('.b-form-button__input').click();}); 
                                //document.getElementsByClassName('b-form-button__input')[1].click(); 
                                //document.getElementsByClassName('b-form-button__input')[1].style.display = 'none';                                
                                //document.elementFromPoint(571, 266).click();   
                                console.log('click');      
                            });
                                                       
                        }, 5000); 
                        setTimeout(function() { 
                            page.clipRect = { left:0, top:0, width:0, height:0 }
                            page.render('log/test.png');  
                            phantom.exit();                              
                        }, 10000);    
                        clearInterval(captchaPush); 
                    }  
                }, 10000);   
            });  
        }, 10000);             
    });
});
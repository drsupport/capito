function serialize (form) {
    if (!form || form.nodeName !== "FORM") {
            return;
    }
    var i, j, q = [];
    for (i = form.elements.length - 1; i >= 0; i = i - 1) {
            if (form.elements[i].name === "") {
                    continue;
            }
            switch (form.elements[i].nodeName) {
            case 'INPUT':
                    switch (form.elements[i].type) {
                    case 'text':
                    case 'hidden':
                    case 'password':
                    case 'button':
                    case 'reset':
                    case 'submit':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                    case 'checkbox':
                    case 'radio':
                            if (form.elements[i].checked) {
                                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            }                                               
                            break;
                    }
                    break;
                    case 'file':
                    break; 
            case 'TEXTAREA':
                    q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                    break;
            case 'SELECT':
                    switch (form.elements[i].type) {
                    case 'select-one':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                    case 'select-multiple':
                            for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                    if (form.elements[i].options[j].selected) {
                                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                                    }
                            }
                            break;
                    }
                    break;
            case 'BUTTON':
                    switch (form.elements[i].type) {
                    case 'reset':
                    case 'submit':
                    case 'button':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                    }
                    break;
            }
    }
    return q.join("&");
}
function ajax(url, params, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.withCredentials = true;
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if (this.readyState == 4) {
            if (this.status == 200) {
                callback(JSON.parse(this.responseText));       
            } else {
                var data = JSON.parse('{success":"false","msg":"server not responding"}');
                //callback(JSON.parse(this.responseText)); 
                callback(JSON.parse(data));
            }            
        }
    };
    xhr.send(params);
}
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function validateTxt(txt) {
    if (txt < 1) {
        return false;
    }
    return true;
}
function validateBrief(f) {
    if (!validateEmail(f.elements['login'].value) || !validateTxt(f.elements['password'].value)) {
        return false;
    }
    return true;   
} 
document.addEventListener("DOMContentLoaded", function(){ 
    var f = document.getElementsByClassName('signin')[0];   
    f.addEventListener("keyup", function(event) {
        event.preventDefault();  
        var e = document.querySelector('[type="submit"]');
        if(validateBrief(f)) {     
            e.className += " btn-info";
            e.classList.remove("btn-default");
            e.disabled = false;
        } else {
            e.className += " btn-default";
            e.classList.remove("btn-info");
            e.disabled = true;            
        }
    });
    f.addEventListener("submit", function(event) {       
        var p = document.getElementsByClassName('preloader')[0];
        //var b = document.getElementsByClassName('signin')[0];
        event.preventDefault();           
        p.style.display = 'block';
        var arg = serialize(this);            
        ajax('../signin', arg, function(data){
            p.style.display = 'none' 
            if (data['response']['success']) {                   
                window.location = "../admin";
            } else {

            }
            //b.className += " animated zoomOut";                       
        });   
             
    });
}, false);

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
                callback(this.responseText);      
                //callback(JSON.parse(this.responseText));       
            } else {
                callback(this.responseText); 
                //var data = JSON.parse('{success":"false","msg":"server not responding"}');
                //callback(JSON.parse(this.responseText)); 
                //callback(JSON.parse(data));
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
    var b = document.getElementsByClassName('btn-run')[0];   
    var i = document.getElementsByClassName('icon-run')[0];   
    b.addEventListener("click", function(event) {
        event.preventDefault();  
        b.disabled = true;
        i.className += " fa-spin-hover";
        var arg = serialize(this);   
        ajax('../p/yws', arg, function(data){
            i.classList.remove("fa-spin-hover");
            b.disabled = false;  
            location.reload();
        }); 


    });
  
             
}, false);

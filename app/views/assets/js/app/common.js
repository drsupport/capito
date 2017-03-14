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
function validateReg(txt, re) {
    //var re = /([a-zA-Zа-яА-Я-\.]{1,20},)/g;
    //var re = /^([0-9a-zа-я][^\s]+)([,][^\s][0-9a-zа-я]+)*$/i;
    return re.test(txt);
}
function validateTxt(txt) {
    if (txt < 1) {
        return false;
    }
    return true;
}
function validateWords(txt) {
    var re = /^([0-9a-zа-я]+)([,][0-9a-zа-я]+)*$/i;
    txt = txt.replace(/\s/g, '');
    return validateReg(txt, re);
}
function validateForm(f) {
    if (!validateTxt(f.elements['name'].value) || !validateTxt(f.elements['words'].value) || !validateWords(f.elements['words'].value)) {
        return false;
    }
    return true;   
} 
function isValidJSON(src) {
    var filtered = src;
    filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
    filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
    filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');
    return (/^[\],:{}\s]*$/.test(filtered));
};
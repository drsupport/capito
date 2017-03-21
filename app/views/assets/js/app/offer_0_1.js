document.addEventListener("DOMContentLoaded", function(){ 
    var f = document.getElementsByClassName('createForm')[0];  
    var e = document.querySelector('[type="submit"]');
    var p = document.getElementsByClassName('preloader')[0];
    var a = document.getElementsByClassName('alert')[0];
    var m = document.getElementsByClassName('msg')[0];
    if(validateForm(f)) {     
        e.className += " btn-info";
        e.classList.remove("btn-default");
        e.disabled = false;
    } else {
        e.className += " btn-default";
        e.classList.remove("btn-info");
        e.disabled = true;            
    }  
    f.addEventListener("keyup", function(event) {
        event.preventDefault();          
        e.disabled = false;       
        if(validateForm(f)) {     
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
        event.preventDefault();           
        p.style.display = 'block'; 
        e.disabled = true;
        var arg = serialize(this);            
        ajax('/admin/offer/create', arg, function(data){
            if(isValidJSON(data)) var data = JSON.parse(data);
            if(data['response']['success']) { 
                window.location = '/admin/offer/';
            } else {            
                p.style.display = 'none';
                e.className += " btn-default";
                e.classList.remove("btn-info"); 
                a.style.display = 'block';
                m.innerHTML = data['response']['msg'];
                f.elements[data['response']['target']].style.borderColor = '#ffb400';
            }
        }); 
    });             
}, false);
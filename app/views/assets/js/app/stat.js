document.addEventListener("DOMContentLoaded", function(){

    var f = document.getElementsByClassName('offers')[0];  
    f.addEventListener("focusout", function(event) {   
        event.preventDefault();           
        console.log('test');
    }); 
                
}, false);
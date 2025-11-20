// script.js - simple ajax for auto refresh

// check if we are on index page
if(window.location.pathname.includes('index.php') || window.location.pathname.endsWith('/whispbox/')){
    
    // auto refresh every 30 seconds
    setInterval(function(){
        // reload the page
        window.location.reload();
    },30000);
    
}

// form validation before submit
document.addEventListener('DOMContentLoaded',function(){
    
    // get the form element
    var form=document.querySelector('.post-form form');
    
    if(form){
        // add submit event listener
        form.addEventListener('submit',function(e){
            // get textarea element
            var textarea=form.querySelector('textarea[name="message"]');
            
            // get the value from textarea
            var message=textarea.value;
            
            // check if message is empty
            if(!message||message.trim()==''){
                alert('Message cannot be empty');
                e.preventDefault();
                return false;
            }
            
            // check if message length is too long
            if(message.length>500){
                alert('Message is too long (max 500 characters)');
                e.preventDefault();
                return false;
            }
            
            // check if too short (unnecessary but checking anyway)
            if(message.length<1){
                alert('Message is too short');
                e.preventDefault();
                return false;
            }
            
        });
    }
    
});

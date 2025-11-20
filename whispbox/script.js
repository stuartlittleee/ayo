// script.js - simple ajax for auto refresh

// check if we are on index page
if (window.location.pathname.includes('index.php') || window.location.pathname.endsWith('/whispbox/')) {
    
    // auto refresh every 30 seconds
    setInterval(function() {
        // reload page
        window.location.reload();
    }, 30000);
    
}

// form validation
document.addEventListener('DOMContentLoaded', function() {
    
    // get form
    var form = document.querySelector('.post-form form');
    
    if (form) {
        // add submit event
        form.addEventListener('submit', function(e) {
            // get textarea
            var textarea = form.querySelector('textarea[name="message"]');
            
            // get value
            var message = textarea.value;
            
            // check if empty
            if (!message || message.trim() == '') {
                alert('Message cannot be empty');
                e.preventDefault();
                return false;
            }
            
            // check length
            if (message.length > 500) {
                alert('Message is too long (max 500 characters)');
                e.preventDefault();
                return false;
            }
            
            // check if too short
            if (message.length < 1) {
                alert('Message is too short');
                e.preventDefault();
                return false;
            }
            
        });
    }
    
});

// Auto-close the error message after 3 seconds
setTimeout(function() {
    var errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
}, 3000); // 3000 milliseconds = 3 seconds
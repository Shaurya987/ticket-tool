window.onload = function() {
    setTimeout(() => {
        // Show logo with animation
        document.getElementById('prayatna-logo').classList.add('visible');
    }, 1000);

    setTimeout(() => {
        // Show buttons with animation
        document.getElementById('button-container').classList.add('visible');
    }, 2000);

    setTimeout(() => {
        // Show contact info with animation
        document.getElementById('contact-info').classList.add('visible');
    }, 3000);
};
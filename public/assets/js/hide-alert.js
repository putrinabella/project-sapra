document.addEventListener('DOMContentLoaded', function () {
    var alertElement = document.getElementById('alert');
    if (alertElement) {
        setTimeout(function () {
            alertElement.style.display = 'none';
        }, 5000);
    }
});

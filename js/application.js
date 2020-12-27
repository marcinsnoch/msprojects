const baseUrl = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

// bootstrap-notify default settings
$.notifyDefaults({
    delay: 2000,
    z_index: 1050,
    placement: {
        align: 'center'
    },
    allow_dismiss: true,
    newest_on_top: true,
    animate: {
        enter: 'animated fadeInDown',
        exit: 'animated fadeOutUp'
    }
});

// set menu active class
$(function () {
    const url = window.location;
    const menu = url.pathname.split("/");
    if (menu.length > 2) {
        $('.nav a[href="' + url.origin + '/' + menu[1] + '"]').addClass("active");
    } else {
        $('.nav a[href="' + url.href + '"]').addClass("active");
    }
});

// token generator
const generateToken = (length = 64) => {
    // Declare all characters
    let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    // Pick characers randomly
    let str = '';
    for (let i = 0; i < length; i++) {
        str += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return str;
};
// input token
$('#tokenGenerator').on('click', function() {
    $('#tokenInput').val(generateToken());
});

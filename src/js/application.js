const baseUrl = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

$.notifyDefaults({
    allow_dismiss: false,
    delay: 500,
    z_index: 1050,
    placement: {
        align: 'center'
    }
});

$(function () {
    $('.nav a[href="' + window.location.href + '"]').addClass("active");
});

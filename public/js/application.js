const baseUrl = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

$.notifyDefaults({
    delay: 1000,
    z_index: 1050,
    placement: {
        align: 'center'
    },
    allow_dismiss: true,
    newest_on_top: true,
    animate: {
        enter: 'animate__animated animate__fadeInDown',
        exit: 'animate__animated animate__fadeOutUp'
    }
});

$(function () {
    $('.nav a[href="' + window.location.href + '"]').addClass("active");
});

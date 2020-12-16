const baseUrl = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

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

$(function () {
    const url = window.location;
    const menu = url.pathname.split("/");
    if (menu.length > 2) {
        $('.nav a[href="' + url.origin + '/' + menu[1] + '"]').addClass("active");
    } else {
        $('.nav a[href="' + url.href + '"]').addClass("active");
    }
    console.log(menu);
});

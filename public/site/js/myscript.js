$(document).ready(function () {

    $(window).scroll(function () {
        if (window.outerWidth >= 768) {
            var header_h = $('#site-header').outerHeight();
            nav_h = $('#site-nav').outerHeight();

            if ($(window).scrollTop() >= header_h) {
                $('#site-nav').addClass('fixed-top');
                $('header#header').css('margin-top', nav_h);
            } else {
                $('#site-nav').removeClass('fixed-top');
                $('#site-header').css('margin-top', '0');
            }
        }
    });

    $('.item-news.verticle .title').trunk8({
        lines: 2
    });

    $('.item-news.horizontal .title').trunk8({
        lines: 1
    });

    $('.item-news .desc').trunk8({
        lines: 3
    });

    $('.item-news .tag').trunk8({
        lines: 1
    });

    $('.newsticker').newsTicker({
        max_rows: 1,
        duration: 3000,
        pauseOnHover: 0
    });

    $('.open-search-bar').on('click', function () {
        $('.search-form').toggleClass('show');
    })
})
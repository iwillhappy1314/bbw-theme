import "./off-convas.scss";

export default  function offCanvas() {
    $('body').append('<div class="rs-body-cover hidden" />');

    $('.rs-body-cover, .rs-off-canvas-toggle').click(function() {
        $('body').toggleClass('rs-off-canvas-toggled');
        $('.rs-body-cover').toggleClass('hidden');
        $('#site-navigation').toggleClass('is-toggled');
        $(this).toggleClass('is-toggled');
    });

    /**
     * 移动端 Accordion 效果
     */
    $('#rs-primary-nav > ul > li > a').click(function() {

        var checkElement = $(this).next();

        $('#rs-primary-nav li').removeClass('active');
        $(this).closest('li').addClass('active');

        if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            $(this).closest('li').removeClass('active');
            checkElement.slideUp('fast');
        }

        if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            $('#rs-primary-nav ul ul:visible').slideUp('fast');
            checkElement.slideDown('fast');
        }

        return !checkElement.is('ul');
    });
}

offCanvas();
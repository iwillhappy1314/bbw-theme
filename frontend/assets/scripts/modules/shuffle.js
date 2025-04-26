import Shuffle from 'shufflejs';

var rsShuffle = new Shuffle(document.querySelector('.rs-shuffle__container'), {
    itemSelector: '.rs-shuffle__item',
    sizer       : '.rs-shuffle__sizer',
    buffer      : 1,
});

$('.rs-shuffle__filter li').on('click', function() {
    $('.rs-shuffle__filter li').removeClass('active');
    $(this).addClass('active');

    rsShuffle.filter($(this).attr('data-filter'));

    return false;
});
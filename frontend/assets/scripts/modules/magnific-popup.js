import 'magnific-popup';
import 'magnific-popup/src/css/main.scss';

if ($('.rs-popup-image').length > 0) {
    $('.rs-popup-image').magnificPopup({
        type: 'image',
    });
}

if ($('.rs-popup-video').length > 0) {
    $('.rs-popup-video').magnificPopup({
        disableOn      : 700,
        type           : 'iframe',
        mainClass      : 'mfp-fade',
        removalDelay   : 160,
        preloader      : false,
        fixedContentPos: false,
    });
}

$('.rs-popup-gallery').magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: 'Loading image #%curr%...',
    mainClass: 'mfp-img-mobile',
    gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
    },
    image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        titleSrc: function(item) {
            return item.el.attr('title');
        }
    }
});

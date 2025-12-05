import Swiper, {Navigation, Pagination, Thumbs, FreeMode, Controller} from 'swiper';
import {initPhotoSwipeWithSwiper} from './ini-photoswipe';

jQuery(document).ready(function($) {
    /**
     * Slider with animated caption
     * @type {Swiper}
     */
    var swiper = new Swiper('.rs-swiper-container', {
        navigation         : {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination         : {
            el            : '.swiper-pagination',
            dynamicBullets: true,
        },
        paginationClickable: true,
        nextButton         : '.swiper-button-next',
        prevButton         : '.swiper-button-prev',
        spaceBetween       : 0,
        loop               : true,
        simulateTouch      : true,
        autoplay           : 5000,
        speed              : 500,
    });

    swiper.on('slideChangeTransitionStart', function() {
        $('.rs-swiper-container').find('.slider-wrapper').hide();
    });

    swiper.on('slideChangeTransitionEnd', function() {
        $('.rs-swiper-container').find('.slider-wrapper').show();
    });

    /**
     * 绑定两个Swiper实例
     * @param swiperList
     */
    function bindSwipers(...swiperList) {
        for (const swiper of swiperList) {
            swiper.slideTo = function(index, speed, runCallbacks, doNotPropagate) {
                if (doNotPropagate) {
                    Swiper.prototype.slideTo.apply(this, arguments);
                } else {
                    for (const swiper of swiperList) {
                        swiper.slideTo(index, speed, runCallbacks, true);
                    }
                }
            };
        }
    }

    /**
     * Product gallery with PhotoSwipe
     * @type {Swiper}
     */
    var galleryTop = new Swiper('.rs-gallery-top', {
        modules      : [Navigation, Pagination, FreeMode, Thumbs, Controller],
        slidesPerView: 1,
        loop         : true,
        loopedSlides : 50,
        navigation   : {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    var galleryThumbs = new Swiper('.rs-gallery-thumbs', {
        modules            : [Navigation, Pagination, FreeMode, Thumbs, Controller],
        slidesPerView      : 4,
        slideToClickedSlide: true,
        spaceBetween       : 10,
        loopedSlides       : 50,
        loop               : true,
        navigation         : {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    bindSwipers(galleryTop, galleryThumbs);

    initPhotoSwipeWithSwiper('.rs-gallery-top', galleryTop);

});

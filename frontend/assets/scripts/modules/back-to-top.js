import {isTableMobile} from '../components/is-mobile';

jQuery(document).ready(function($) {
  function goToTop() {
    var $goToTopEl          = $('.rs-back-to-top'),
        elementScrollSpeed  = $goToTopEl.attr('data-speed'),
        elementScrollEasing = $goToTopEl.attr('data-easing');

    if (!elementScrollSpeed) {
      elementScrollSpeed = 700;
    }
    if (!elementScrollEasing) {
      elementScrollEasing = 'easeOutQuad';
    }

    $goToTopEl.click(function() {
      $('body,html').stop(true).animate({
        'scrollTop': 0,
      }, Number(elementScrollSpeed), elementScrollEasing);
      return false;
    });
  }

  function goToTopScroll() {
    var $goToTopEl    = $('.rs-back-to-top'),
        elementMobile = $goToTopEl.attr('data-mobile'),
        elementOffset = $goToTopEl.attr('data-offset');

    if (!elementOffset) {
      elementOffset = 250;
    }

    if (elementMobile !== 'true' && isTableMobile()) {
      return true;
    }

    if ($(window).scrollTop() > Number(elementOffset)) {
      $goToTopEl.fadeIn();
    } else {
      $goToTopEl.fadeOut();
    }
  }

  goToTop();

  window.addEventListener('scroll', () => {
    goToTopScroll();
  });
});

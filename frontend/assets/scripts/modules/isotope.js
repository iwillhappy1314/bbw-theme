const loadjs = require('loadjs');
loadjs([wenpriseSettings.staticPath + 'js/imagesloaded.pkgd.min.js', wenpriseSettings.staticPath + 'js/isotope.pkgd.min.js'], 'imagesloaded-isotope');

loadjs.ready('imagesloaded-isotope', function() {
  $('.rs-isotope__container').imagesLoaded(function() {

    $('.rs-isotope__container').isotope({
      itemSelector: '.rs-isotope__item',
      layoutMode  : 'fitRows',
    });

    $('.rs-isotope__filter li').on('click', function() {
      $('.rs-isotope__filter li').removeClass('active');
      $(this).addClass('active');

      $('.rs-isotope__container').isotope({
        filter          : $(this).attr('data-filter'),
        animationOptions: {
          duration: 750,
          easing  : 'linear',
          queue   : false,
        },
      });

      return false;
    });
  });
});
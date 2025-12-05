const loadjs = require('loadjs');

loadjs([wenpriseSettings.staticPath + 'js/imagesloaded.pkgd.min.js'], 'imagesloaded');

loadjs.ready('imagesloaded', function() {
  let $container = $('.rsis-container').infiniteScroll({
    // options...
    // enable button
    button: '.view-more-button',
  });

  let $viewMoreButton = $('.rsis-view-more-button');

// get Infinite Scroll instance
  let infScroll = $container.data('infiniteScroll');

  $container.on('load.infiniteScroll', onPageLoad);

  function onPageLoad() {
    if (infScroll.loadCount == 1) {
      // after 2nd page loaded
      // disable loading on scroll
      $container.infiniteScroll('option', {
        loadOnScroll: false,
      });
      // show button
      $viewMoreButton.show();
      // remove event listener
      $container.off('load.infiniteScroll', onPageLoad);
    }
  }
});
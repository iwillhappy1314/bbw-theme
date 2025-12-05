const loadjs = require('loadjs');

loadjs([wenpriseSettings.staticPath + 'js/imagesloaded.pkgd.min.js', wenpriseSettings.staticPath + 'js/masonry.pkgd.js'], 'imagesloaded-masonry');

loadjs.ready('imagesloaded-masonry', function() {
  var $grid = $('.rs-masonry').imagesLoaded(function() {
    $grid.masonry();
  });
});
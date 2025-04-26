require('script-loader!theia-sticky-sidebar');

if ($(document).width() > 1024) {
    $('.rs-sticky-left, .rs-sticky-right').theiaStickySidebar({
        additionalMarginTop: 32,
    });
}

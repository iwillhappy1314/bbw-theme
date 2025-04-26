import scrollSpy from 'simple-scrollspy';

scrollSpy('.rs-scroll-nav', {
    scrollContainer    : '.rs-scroll-container',
    sectionClass    : '.rs-scroll-section',
    menuActiveTarget: '.menu-item',
    offset          : 100,
    smoothScroll    : false,
});

require('smartmenus');

export default function smartMenu() {
    //@see https://www.smartmenus.org/docs/
    jQuery('.sm, .product-categories').smartmenus({
        showFunction: function($ul, complete) {
            $ul.slideDown(100, complete);
        },
        hideFunction: function($ul, complete) {
            $ul.hide();
        },
        showTimeout : 0,
        hideTimeout : 100,
    });
}

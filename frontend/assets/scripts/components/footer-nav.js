import {isMobile} from './is-mobile';

export default function footerNav() {

    if (isMobile()) {
        $('.sidebar-footer .widget-title, .site__footer .widget_nav_menu .widgettitle').click(function() {
            $(this).next().slideToggle();
        });
    }

}

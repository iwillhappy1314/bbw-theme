const $navTreeEl = $('.widget_nav_menu, .widget_product_categories, .widget-nav_menu');

if ($navTreeEl.length > 0) {
    $navTreeEl.each(function() {

        let element        = $(this),
            elementSpeed   = element.attr('data-speed'),
            elementEasing  = element.attr('data-easing'),
            currentChild   = element.find(
                'ul li.current_page_parent .children, ul > li.current_page_item .children, ul > li.current-menu-item .children,  ul > li.current-menu-parent .children, ul > li.current-cat-parent .children, ul > li.current-cat.cat-parent .children'),
            currentSubmenu = element.find(
                'ul li.current_page_parent .sub-menu, ul > li.current_page_item .sub-menu, ul > li.current-menu-item .sub-menu, ul > li.current-menu-parent .sub-menu, ul > li.current-cat-parent .sub-menu, ul > li.current-cat.cat-parent .sub-menu');

        //element.addClass('widget--tree');

        // 动画速度
        if (!elementSpeed) {
            elementSpeed = 250;
        }

        // 动画效果
        if (!elementEasing) {
            elementEasing = 'swing';
        }

        // 添加 sub-menu 类，添加箭头
        element.find('ul li:has(ul)').addClass('sub-menu');
        element.find('ul li:has(ul) > a').
            append(
                '<span class="icon"><i class="icon-chevron-down"></i></span>');

        // 打开当前菜单的父级
        element.find(
            'ul li.current_page_ancestor, ul li.current_page_parent, ul > li.current_page_item, ul > li.current-menu-parent, ul > li.current-cat-parent').
            addClass('active');

        currentChild.slideDown(Number(elementSpeed), elementEasing);
        currentSubmenu.slideDown(Number(elementSpeed), elementEasing);

        element.find('ul > li.current-menu-item').
            parent().
            parent().
            slideDown(Number(elementSpeed), elementEasing);
        element.find('ul > li.current-menu-item').
            parent().
            parent('li').
            addClass('active');
        element.find('ul > li.current-cat').
            parent().
            parent('li').
            addClass('active');

        // 鼠标划过还是点击显示子菜单
        if (element.hasClass('on-hover')) {
            element.find('ul li:has(ul):not(.active)').
                hover(function() {
                    $(this).
                        children('ul').
                        stop(true, true).
                        slideDown(Number(elementSpeed), elementEasing);
                }, function() {
                    $(this).
                        children('ul').
                        delay(250).
                        slideUp(Number(elementSpeed), elementEasing);
                });
        } else {
            // 如果需要父级菜单不能点击，把点击元素设置为 a
            element.find('ul li:has(ul) > a .icon').click(function(e) {
                const childElement = $(this).parent();
                element.find('ul li').
                    not(childElement.parents()).
                    removeClass('active');
                childElement.parent().
                    children('ul').
                    slideToggle(Number(elementSpeed), elementEasing,
                        function() {
                            $(this).find('ul').hide();
                            $(this).
                                find('li.active').
                                removeClass('active');
                        });
                element.find('ul li > ul').
                    not(childElement.parent().children('ul')).
                    not(childElement.parents('ul')).
                    slideUp(Number(elementSpeed), elementEasing);
                childElement.parent('li:has(ul)').toggleClass('active');
                e.preventDefault();
            });
        }
    });
}

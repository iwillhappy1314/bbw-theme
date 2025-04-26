import './plugins/quantity';

(function($) {

    'use strict';

    const _s_wc = {};

    const rswc_options = {};

    _s_wc.init = function() {
        this.ajax_add_to_cart();
        this.sticky_cart();
        this.sticky_add_to_cat();
        this.quick_shop();
        this.productBoughtTogetherInit();
        this.productBoughtTogetherChangeEvent();
    };

    /**
     * Ajax add to cart
     */
    _s_wc.ajax_add_to_cart = function() {
        const body = $('body');
        body.on('submit', 'form.cart', function(e) {
            const $product_element = $(this).parents('.type-product');

            if ($product_element.hasClass('product-type-external') ||
                $product_element.hasClass('product-type-grouped')) {
                return;
            }

            e.preventDefault();

            let $add_to_cart_button = $(this).find('.single_add_to_cart_button'),
                data = $(this).serialize();

            data += '&action=wprs_wc_ajax_add_to_cart';

            if ($add_to_cart_button.val()) {
                data += '&add-to-cart=' + $add_to_cart_button.val();
            }

            $add_to_cart_button.removeClass('added not-added');
            $add_to_cart_button.addClass('loading');

            $.ajax({
                url    : _s_ajax_obj.ajaxurl,
                data   : data,
                method : 'POST',
                success: function(response) {

                    if (response.redirect_url) {
                        window.location = response.redirect_url;
                    } else {
                        $(document.body).trigger('adding_to_cart', [$add_to_cart_button, data]);
                        $add_to_cart_button.removeClass('loading');

                        const fragments = response.fragments;

                        // 更新购物车
                        if (fragments) {
                            $.each(fragments, function(key) {
                                $(key).addClass('updating');
                            });

                            $.each(fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }

                        // show notice
                        if (0 < response.notices.indexOf('error')) {
                            $('body').append(response.notices);
                            $add_to_cart_button.addClass('not-added');
                        } else {
                            $add_to_cart_button.addClass('added');
                        }
                    }

                },
            });

            return false;
        });

        body.on('adding_to_cart', function(event, fragments, cart_hash) {
            body.toggleClass('drawer-open');
        });
    };

    /*
     * Product Buy Now Button click
     */
    _s_wc.quick_shop = function() {
        $('body').on('click', '.rswc_quick_buy_button', function() {
            //if (rswc_options.product_add_to_cart_ajax) {
            //$('.single_add_to_cart_button').addClass('quick-buy-proceed');
            //}

            const $this = $(this),
                product_id = $(this).attr('data-product-id'),
                product_type = $(this).attr('data-product-type'),
                selected = $('form.cart input#rswc_quick_buy_product_' + product_id),
                product_form = selected.parents('form.cart'),
                submit_btn = product_form.find('[type="submit"]'),
                is_disabled = submit_btn.is(':disabled');

            if (is_disabled) {
                $('html, body').animate({
                    scrollTop: submit_btn.offset().top - 200,
                }, 900);
            } else {
                if (!$this.hasClass('disable')) {
                    product_form.append('<input type="hidden" value="true" name="rswc_quick_buy" />');
                }

                document.getElementsByClassName('single_add_to_cart_button')[0].click();
            }
        });

        $('form.cart').change(function() {
            const is_submit_disabled = $(this).find('[type="submit"]').is(':disabled');
            if (is_submit_disabled) {
                $('.rswc_quick_buy_button').attr('disabled', 'disable');
            } else {
                $('.rswc_quick_buy_button').removeAttr('disabled');
            }
        });
    };

    /**
     * sticky cart in product page
     */
    _s_wc.sticky_cart = function() {
        $('.single-product form.cart').attr('id', 'sticky-scroll');

        // Smooth scroll for sticky single products - for variable, bundle, composite and grouped items
        $('a.variable-grouped-sticky[href*="#"]').on('click', function(e) {
            e.preventDefault();

            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 80,
            }, 500, 'linear');
        });
    };

    _s_wc.productBoughtTogetherInit = function() {
        /* Procut Bought Together */
        const self = this;

        // check box click
        $('body').on('click', '.rswc-bt-products .product-checkbox input[type=checkbox]', function() {
            if ($(this).is(':checked')) {
                $(this).closest('.product-wrapper').removeClass('kapee-disable-product');
            } else {
                $(this).closest('.product-wrapper').addClass('kapee-disable-product');
            }
            self.productBoughtTogetherChangeEvent();
        });

        // check all
        self.productBoughtTogetherCheckAllItems();

        // add to cart
        self.productBoughtTogetherAddToCart();
    };

    _s_wc.productBoughtTogetherChangeEvent = function() {
        const self = this;
        const button = $('.add-items-to-cart');
        const total_price = self.pbt_get_total_price();
        const addon_total_price = self.product_addons_get_total_price();
        const total_addon = self.pbt_product_count();

        button.addClass('loading');

        if (total_addon) {
            $('.add-items-to-cart').removeAttr('disabled');
        } else {
            $('.add-items-to-cart').attr('disabled', true);
        }

        $('.addons-item .addon-count').html(total_addon);
        $('.addons-item span.items-price').html(self.rswc_woo_format_price(addon_total_price));
        $('.items-total span.total-price').html(self.rswc_woo_format_price(total_price));

        button.removeClass('loading');
    };

    _s_wc.productBoughtTogetherCheckAllItems = function() {
        const self = this;

        $('body').on('click', '.check-all-items', function() {
            $('.kapee-together-product:checkbox').not(this).prop('checked', this.checked);
            if ($(this).is(':checked')) {
                $('.kapee-together-product:checkbox').prop('checked', true);
            } else {
                $('.kapee-together-product:checkbox').prop('checked', false);
            }

            self.productBoughtTogetherChangeEvent();
        });
    };

    _s_wc.productBoughtTogetherAddToCart = function() {
        const self = this;

        $('body').on('click', '.add-items-to-cart:not(.loading)', function(e) {
            e.preventDefault();
            const self_this = $(this);

            self_this.addClass('loading');

            const all_product_ids = self.pbt_get_checked_product_ids();
            let msg = '';

            if (all_product_ids.length === 0) {
                msg = rswc_options.bought_together_error;
            } else {

                setTimeout(function() {
                    for (var i = 0; i < all_product_ids.length; i++) {
                        $.ajax({
                            type   : 'POST',
                            async  : false,
                            url    : _s_ajax_obj.ajaxurl,
                            data   : {
                                'product_id': all_product_ids[i],
                                'action'    : 'woocommerce_add_to_cart',
                            },
                            success: function(response) {
                                self.pbt_refresh_fragments(response);
                            },
                        });
                    }

                    if (!$('body').hasClass('drawer-open')) {
                        $(document.body).trigger('adding_to_cart', [$('.add-items-to-cart:not(.loading)'), {}]);
                    }
                    self_this.removeClass('loading');
                }, 300);

            }
            if (msg !== '') {
                $('.rswc-message').html(msg).show(100);
                self_this.removeClass('loading');
                setTimeout(function() {
                    $('.rswc-message').slideUp(500);

                }, 3000);
            }

        });
    };

    _s_wc.pbt_get_total_price = function(){
        let price = 0,
            item_price = 0;

        item_price = $('.items-total-price .item-price').data('item_price');
        price = parseFloat(item_price);

        $('.rswc-bt-products .product-checkbox input[type=checkbox]').each(function() {
            if( $(this).is(':checked') ) {
                price += parseFloat( $(this).data( 'price' ) );
            }
        });

        return price;
    };

    _s_wc.product_addons_get_total_price = function(){
        let price = 0;

        $('.rswc-bt-products .product-checkbox input[type=checkbox]').each(function() {
            if( $(this).is(':checked') ) {
                price += parseFloat( $(this).data( 'price' ) );
            }
        });
        return price;
    };

    _s_wc.pbt_product_count = function(){
        let count = 0;
        $('.rswc-bt-products .product-checkbox input[type=checkbox]').each(function() {
            if ($(this).is(':checked')) {
                count++;
            }
        });
        return count;
    };

    // get checked product ids
    _s_wc.pbt_get_checked_product_ids = function(){
        let product_ids = [],
            product_id;

        product_id = $('.items-total-price .item-price').data('id');

        product_ids.push( product_id);
        $('.rswc-bt-products .product-checkbox input[type=checkbox]').each(function() {
            if( $(this).is(':checked') ) {
                product_ids.push( $(this).data( 'id' ) );
            }
        });
        return product_ids;
    };

    // get checked product ids
    _s_wc.pbt_refresh_fragments = function(response){
        const frags = response.fragments;

        // Block fragments class
        if ( frags ) {
            $.each( frags, function( key ) {
                $( key ).addClass( 'updating' );
            });
        }
        if ( frags ) {
            $.each( frags, function( key, value ) {
                $( key ).replaceWith( value );
            });
        }
    };

    _s_wc.rswc_woo_format_price = function(number){
        const self = this;
        return self.rswc_format_price(number, rswc_options.price_thousand_separator,
            rswc_options.price_decimal_separator, rswc_options.price_decimals,
            rswc_options.currency_symbol,rswc_options.price_format);
    };

    _s_wc.rswc_format_price = function(number, thousand_sep, decimal_sep, tofixed, symbol, woo_price_format){
        let before_text = '';
        let after_text = '';
        number = number || 0;
        tofixed = !isNaN(tofixed = Math.abs(tofixed)) ? tofixed : 2;
        symbol = symbol !== undefined ? symbol : "$";
        thousand_sep = thousand_sep || ",";
        decimal_sep = decimal_sep || ".";

        let negative = number < 0 ? '-' : '',
            i = parseInt(number = Math.abs(+number || 0).toFixed(tofixed), 10) + '',
            j = i.length;

        if(j > 3){
            j = j % 3;
        } else {
            j = 0;
        }


        symbol = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';

        switch (woo_price_format) {
            case '%1$s%2$s':
                //left
                before_text += symbol;
                break;
            case '%1$s %2$s':
                //left with space
                before_text += symbol + ' ';
                break;
            case '%2$s%1$s':
                //right
                after_text += symbol;
                break;
            case '%2$s %1$s':
                //right with space
                after_text += ' ' + symbol;
                break;
            default:
                //default
                before_text += symbol;
        }

        let woo_price_return = before_text +
            negative + (j ? i.substr(0, j) + thousand_sep : '') +
            i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand_sep) +
            (tofixed ? decimal_sep + Math.abs(number - i).toFixed(tofixed).slice(2) : '') +
            after_text;

        woo_price_return = '<span class="woocommerce-Price-amount amount">' + woo_price_return + '</span>';

        return woo_price_return;
    };

    _s_wc.sticky_add_to_cat = function(){
        if(!$('.js-sticky-add-to-cart').length){
            return;
        }

        var initialTopOffset = $('.rswc-product-hero').offset().top;
        $(window).scroll(function(event) {
            var scroll = $(window).scrollTop();

            if (scroll + initialTopOffset >= $('.product_title').offset().top) {
                $('.js-sticky-add-to-cart').addClass('visible');
            } else {
                $('.js-sticky-add-to-cart').removeClass('visible');
            }
        });
        $(window).scroll();
    };

    $(document).ready(function() {
        _s_wc.init();
    });

})(jQuery);

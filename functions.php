<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */
define('TEMPLATE_DIRECTORY', get_template_directory() . '-master-child24');
define('TEMPLATE_URL', get_template_directory_uri() . '-master-child24');



//Disable plugin and theme auto-update email notification
// add_filter('auto_plugin_update_send_email', '__return_false');
// add_filter('auto_theme_update_send_email', '__return_false');
// add_filter( 'auto_core_update_send_email', '__return_false' );
// add_filter( 'auto_update_plugin', '__return_false' );
// add_filter( 'auto_update_theme', '__return_false' );
// remove_action('load-update-core.php', 'wp_update_plugins');
// add_filter('pre_site_transient_update_plugins', '__return_null');
// remove_action('load-update-core.php', 'wp_update_themes');
// add_filter('pre_site_transient_update_core','__return_null');

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_register_style( 'elementor-icons', plugins_url('elementor/assets/lib/eicons/css/elementor-icons.min.css') );
	wp_enqueue_style( 'elementor-icons' );
	
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css?v='.microtime(),
		array(),
		'1.0.0'
	);

	// Scripts
	wp_enqueue_script(
		'swiper',
		plugins_url('elementor/assets/lib/swiper/swiper.min.js'),
		array(),
        '', true
	);


	wp_register_script( 'my_loadmore', get_stylesheet_directory_uri() . '/assets/js/main.js?v='. microtime(), array('jquery') );

	wp_localize_script( 'my_loadmore', 'loadmore_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
	) );
 	wp_enqueue_script( 'my_loadmore' );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 ); 



/**
* Remove Unused CSS JS Files in WordPress
*/
function wra_filter_styles() {
	/**
	 * Remove styles and scripts.
	 */
	wp_deregister_style( 'issuem-leaky-paywall' );
	wp_dequeue_style( 'issuem-leaky-paywall' );

	wp_dequeue_style( 'feedzy-rss-feeds-elementor' );

	wp_deregister_script( 'leaky_paywall_script' );
	wp_dequeue_script( 'leaky_paywall_script' );
	wp_deregister_script( 'leaky_paywall_validate' );
	wp_dequeue_script( 'leaky_paywall_validate' );
	wp_deregister_script( 'zeen101_micromodal' );
	wp_dequeue_script( 'zeen101_micromodal' );


 	if (function_exists('um_is_core_page') && !um_is_core_page('user') && !um_is_core_page('login') && !um_is_core_page('register') && !um_is_core_page('members')  && !um_is_core_page('logout') && !um_is_core_page('account') && !um_is_core_page('password-reset') && !is_page_template('template-umlogin.php') && !is_page_template('template-umregister.php') && !is_page_template('template-umpasswordreset.php') && !is_page_template('template-umuser.php')) {
 		// /plugins/ultimate-member
 		wp_deregister_style( 'um_crop' );
		wp_dequeue_style( 'um_crop' );
		wp_deregister_style( 'select2' );
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'um_fonticons_ii' );
		wp_dequeue_style( 'um_fonticons_ii' );
		wp_deregister_style( 'um_fonticons_fa' );
		wp_dequeue_style( 'um_fonticons_fa' );
		wp_deregister_style( 'um_styles' );
		wp_dequeue_style( 'um_styles' );
		wp_deregister_style( 'um_profile' );
		wp_dequeue_style( 'um_profile' );
		wp_deregister_style( 'um_account' );
		wp_dequeue_style( 'um_account' );
		wp_deregister_style( 'um_misc' );
		wp_dequeue_style( 'um_misc' );
		wp_deregister_style( 'um_fileupload' );
		wp_dequeue_style( 'um_fileupload' );
		wp_deregister_style( 'um_datetime' );
		wp_dequeue_style( 'um_datetime' );
		wp_deregister_style( 'um_datetime_date' );
		wp_dequeue_style( 'um_datetime_date' );
		wp_deregister_style( 'um_datetime_time' );
		wp_dequeue_style( 'um_datetime_time' );
		wp_deregister_style( 'um_raty' );
		wp_dequeue_style( 'um_raty' );
		wp_deregister_style( 'um_scrollbar' );
		wp_dequeue_style( 'um_scrollbar' );
		wp_deregister_style( 'um_tipsy' );
		wp_dequeue_style( 'um_tipsy' );
		wp_deregister_style( 'um_default_css' );
		wp_dequeue_style( 'um_default_css' );

		wp_deregister_script( 'um_crop' );
		wp_dequeue_script( 'um_crop' );
		wp_deregister_script( 'um_modal' );
		wp_dequeue_script( 'um_modal' );
		wp_deregister_script( 'select2' );
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'um_jquery_form' );
		wp_dequeue_script( 'um_jquery_form' );
		wp_deregister_script( 'um_fileupload' );
		wp_dequeue_script( 'um_fileupload' );
		wp_deregister_script( 'um_datetime' );
		wp_dequeue_script( 'um_datetime' );
		wp_deregister_script( 'um_datetime_date' );
		wp_dequeue_script( 'um_datetime_date' );
		wp_deregister_script( 'um_datetime_time' );
		wp_dequeue_script( 'um_datetime_time' );
		wp_deregister_script( 'um_scrollbar' );
		wp_dequeue_script( 'um_scrollbar' );
		wp_deregister_script( 'um_functions' );
		wp_dequeue_script( 'um_functions' );
		wp_deregister_script( 'um_conditional' );
		wp_dequeue_script( 'um_conditional' );
		wp_deregister_script( 'um_profile' );
		wp_dequeue_script( 'um_profile' );
		wp_deregister_script( 'um_account' );
		wp_dequeue_script( 'um_account' );
		wp_deregister_script( 'um_raty' );
		wp_dequeue_script( 'um_raty' );
		wp_deregister_script( 'um_tipsy' );
		wp_dequeue_script( 'um_tipsy' );
		wp_deregister_script( 'um_tipsy' );
		wp_dequeue_script( 'um_tipsy' );
		wp_deregister_script( 'um-gdpr' );
		wp_dequeue_script( 'um-gdpr' );

 	}
}
add_action( 'wp_print_styles', 'wra_filter_styles', 100000 );
add_action( 'wp_print_footer_scripts', 'wra_filter_styles', 100000 );



/**
 * Set up theme support.
 *
 * @return void
 */
function hello_elementor_child_setup() {
	// Polylang: register string
	if ( function_exists( 'pll_register_string' ) ) {
		pll_register_string('hello-elementor', 'All premium content');
		pll_register_string('hello-elementor', 'Search results for');
		pll_register_string('hello-elementor', 'Load more');
		pll_register_string('hello-elementor', 'Loading...');
		pll_register_string('hello-elementor', 'Related Articles');
		pll_register_string('hello-elementor', 'Recent Articles');
		pll_register_string('hello-elementor', 'View All');
		pll_register_string('hello-elementor', 'Search');
	  	pll_register_string('hello-elementor', 'Sign in');
	  	pll_register_string('hello-elementor', 'Sign up');
	  	pll_register_string('hello-elementor', 'Register');
	  	pll_register_string('hello-elementor', 'My account');
	  	pll_register_string('hello-elementor', 'Remember me');
	  	pll_register_string('hello-elementor', 'Don’t have an account?');
	  	pll_register_string('hello-elementor', 'You have reached the limit of 5 Premium articles, please');
	  	pll_register_string('hello-elementor', 'for an account to enjoy full access.');
	  	pll_register_string('hello-elementor', 'Membership Required');

	  	pll_register_string('hello-elementor', 'Your password * must contain at least 8 characters');
  	}
}
add_action( 'after_setup_theme', 'hello_elementor_child_setup' );
/**
 * Register our sidebars and widgetized areas.
 *
 */
function hello_elementor_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Top Banner Ad - Header', 'bamboo' ),
			'id'            => 'header-top-ad',
			'description'   => __( 'Add widgets here to appear in your footer.', 'bamboo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Bottom Banner Ad - Header', 'bamboo' ),
			'id'            => 'header-bottom-ad',
			'description'   => __( 'Add widgets here to appear in your footer.', 'bamboo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	        register_sidebar(
		array(
			'name'          => __( 'Top new tickers Header', 'bamboo' ),
			'id'            => 'top-new-tickers',
			'description'   => __( 'Add widgets here to appear in Top new tickers Header.', 'bamboo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'bamboo' ),
			'id'            => 'sidebar-footer-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'bamboo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

  	register_sidebar(
	    array(
	      'name'          => __( 'Page Sidebar', 'bamboo' ),
	      'id'            => 'page-sidebar',
	      'description'   => __( 'Add widgets here to appear in your footer.', 'bamboo' ),
	      'before_widget' => '<section id="%1$s" class="widget %2$s">',
	      'after_widget'  => '</section>',
	      'before_title'  => '<h2 class="widget-title">',
	      'after_title'   => '</h2>',
	    )
  	);

  	register_sidebar(
	    array(
	      'name'          => __( 'Blog Sidebar', 'bamboo' ),
	      'id'            => 'blog-sidebar',
	      'description'   => __( 'Add widgets here to appear in your footer.', 'bamboo' ),
	      'before_widget' => '<section id="%1$s" class="widget %2$s">',
	      'after_widget'  => '</section>',
	      'before_title'  => '<h2 class="widget-title">',
	      'after_title'   => '</h2>',
	    )
  	);
}
add_action( 'widgets_init', 'hello_elementor_widgets_init' );
add_filter( 'use_widgets_block_editor', '__return_false' );




/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
	return '.....';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );


/**WordPress post per page (Archive)*/
require TEMPLATE_DIRECTORY. '/inc/template-conditions.php';
require TEMPLATE_DIRECTORY. '/inc/template-tags.php';
require TEMPLATE_DIRECTORY. '/inc/template-shortcode.php';
require TEMPLATE_DIRECTORY. '/inc/template-ajax.php';
require TEMPLATE_DIRECTORY. '/inc/class-walker_category_custom.php';

require_once(get_theme_file_path('vendor/autoload.php'));
require_once(get_theme_file_path('inc/setup.php'));
require_once(get_theme_file_path('inc/rss.php'));
require_once(get_theme_file_path('inc/rss-page.php'));
require_once(get_theme_file_path('inc/helpers.php'));

add_filter('wpseo_title', 'ch_rewrite_title_english');

function ch_rewrite_title_english($title) {
	$title=get_the_title();
	if(is_category()||is_tag()){
		global $wp_query;
        $term = $wp_query->get_queried_object();
		$title=$term->name;
	}
	$title=$title.' - Bamboo Works - China stock insights for global investors';
    return $title;
}
add_filter( 'widget_custom_html_content', 'do_shortcode' );


add_filter('yarpp_results', function($related_posts, $args) {
    if (!function_exists('pll_get_post_language')) {
        return $related_posts;
    }

    $filtered_posts = array();
    $current_lang = pll_current_language();

    foreach ($related_posts as $post) {
        $post_lang = pll_get_post_language($post->ID);
        if ($post_lang === $current_lang) {
            $filtered_posts[] = $post;
        }
    }

    return $filtered_posts;
}, 10, 2);


function bbw_get_first_paragraph($post_id) {
    // 获取文章内容
    $content = get_post_field('post_content', $post_id);

    // 使用正则表达式提取第一个段落
    preg_match('/<p>(.*?)<\/p>/', $content, $matches);

    // 如果匹配到第一个段落，则返回该段落内容
    return isset($matches[1]) ? $matches[1] : '';
}
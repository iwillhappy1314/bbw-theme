<?php
/**
 * The template for displaying header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! hello_get_header_display() ) {
	return;
}

$is_editor = isset( $_GET['elementor-preview'] );
$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );
$header_nav_menu = wp_nav_menu( [
	'theme_location' => 'menu-1',
	'fallback_cb' => false,
	'echo' => false,
] );

?>

<header id="site-header" class="site-header dynamic-header <?php echo esc_attr( hello_get_header_layout_class() ); ?>" role="banner">
	<div id="top-header" class="top-header">
		<div class="top-header-inner">
			<div class="site-branding show-<?php echo esc_attr( hello_elementor_get_setting( 'hello_header_logo_type' ) ); ?>">
				<?php if ( has_custom_logo() && ( 'title' !== hello_elementor_get_setting( 'hello_header_logo_type' ) || $is_editor ) ) : ?>
					<div class="site-logo <?php echo esc_attr( hello_show_or_hide( 'hello_header_logo_display' ) ); ?>">
						<?php the_custom_logo(); ?>
					</div>
				<?php endif;

				if ( $site_name && ( 'logo' !== hello_elementor_get_setting( 'hello_header_logo_type' ) || $is_editor ) ) : ?>
					<h1 class="site-title <?php echo esc_attr( hello_show_or_hide( 'hello_header_logo_display' ) ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-elementor' ); ?>" rel="home">
							<?php echo esc_html( $site_name ); ?>
						</a>
					</h1>
				<?php endif;

				if ( $tagline && ( hello_elementor_get_setting( 'hello_header_tagline_display' ) || $is_editor ) ) : ?>
					<p class="site-description <?php echo esc_attr( hello_show_or_hide( 'hello_header_tagline_display' ) ); ?>">
						<?php echo esc_html( $tagline ); ?>
					</p>
				<?php endif; ?>
			</div>
			<?php if ( is_active_sidebar( 'header-top-ad' ) ): ?>
			<div class="site-bannerads bannerads">
				<?php dynamic_sidebar( 'header-top-ad' ); ?>
			</div>
			<?php endif; ?>
			<div class="site-actions">
				<div id="site-search" class="site-search">
					<div class="site-search-inner">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.6447 11.433C9.20569 12.5503 7.59427 12.9426 5.81983 12.5503C4.45921 12.2521 3.36194 11.5177 2.54056 10.391C0.841356 8.06228 1.16741 4.87361 3.29924 2.90266C5.32449 1.029 8.55987 0.972511 10.676 2.73946C12.9489 4.63822 13.4443 8.15329 11.4378 10.6421C11.4755 10.6829 11.5162 10.7268 11.557 10.7676C12.4818 11.6935 13.4098 12.6193 14.3315 13.5514C14.4256 13.6456 14.5008 13.7774 14.5321 13.9061C14.5886 14.1477 14.46 14.3706 14.25 14.4804C14.0337 14.5934 13.786 14.5589 13.601 14.38C13.3283 14.1132 13.0618 13.8433 12.7953 13.5734C12.1119 12.8924 11.4316 12.2082 10.7481 11.5271C10.7168 11.4957 10.6823 11.4675 10.6447 11.433ZM7.08326 2.54801C4.60656 2.54174 2.57504 4.56604 2.5625 7.05797C2.54996 9.54677 4.57834 11.5899 7.06758 11.5962C9.54741 11.6025 11.5852 9.57815 11.5977 7.09563C11.6103 4.59429 9.58503 2.55429 7.08326 2.54801Z" fill="black"/></svg><span><?php pll_e( 'Search' ); ?></span>
					</div>
				</div>
				
				<div class="site-account">
					<?php if(!is_user_logged_in()): ?>
					<a href="<?php echo get_permalink(pll_get_post( UM()->options()->get( 'core_login' ))); ?>?rid=<?php echo get_the_ID(); ?>" class="elementor-button-link elementor-button elementor-size-sm"><?php pll_e( 'Register' ); ?> / <?php pll_e( 'Sign in' ); ?></a>
					<?php else: ?>
					<a href="<?php echo get_permalink(pll_get_post( UM()->options()->get( 'core_user' ))); ?>" class="elementor-button-link elementor-button elementor-size-sm"><?php pll_e( 'My account' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<?php get_search_form(); ?>
		</div>
	</div>
        <?php if ( is_active_sidebar( 'top-new-tickers' ) ): ?>
    <div id="top_new_tickers" class="top-header">
        <div class="top_new_tickers_content">
            <?php dynamic_sidebar( 'top-new-tickers' ); ?>
        </div>
        </div>
        <?php endif; ?>
	<div class="header-inner">

		<?php if ( $header_nav_menu ) : ?>
			<nav class="site-navigation <?php echo esc_attr( hello_show_or_hide( 'hello_header_menu_display' ) ); ?>" role="navigation">
				<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu"
				echo $header_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>

			<div class="site-languages"><ul><?php pll_the_languages();?></ul></div>

			<div class="site-navigation-toggle-holder <?php echo esc_attr( hello_show_or_hide( 'hello_header_menu_display' ) ); ?>">
				<div class="site-navigation-toggle">
					<i class="eicon-menu-bar"></i>
					<span class="elementor-screen-only">Menu</span>
				</div>
			</div>
			<nav class="site-navigation-dropdown <?php echo esc_attr( hello_show_or_hide( 'hello_header_menu_display' ) ); ?>" role="navigation">
				<?php
				// PHPCS - escaped by WordPress with "wp_nav_menu"
				echo $header_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</nav>

		<?php endif; ?>
	</div>
	<?php if(is_front_page() && is_active_sidebar( 'header-bottom-ad' )): ?>
	<div id="bottom-header" class="bottom-header">
		<div class="bottom-header-inner">
			<div class="site-bannerads bannerads">
				<?php dynamic_sidebar( 'header-bottom-ad' ); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</header>
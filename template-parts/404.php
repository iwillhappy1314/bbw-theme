<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<style type="text/css">
	body.error404 .site-main {
		min-height: calc(100vh - 320px);
		padding-top: 100px;
		padding-bottom: 100px;
		text-align: center;
	}
	body.error404 .page-content .search-form {
		display: block;
		position: relative;
		bottom: auto;
		width: 100%;
	    right: 0;
	    left: 0;
	    margin-top: 50px;
	}
</style>
<main id="content" class="site-main" role="main">
	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can’t be found.', 'hello-elementor' ); ?></h1>
		</header>
	<?php endif; ?>
	<div class="page-content">
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'hello-elementor' ); ?></p>

		<?php get_search_form(); ?>
	</div>

</main>

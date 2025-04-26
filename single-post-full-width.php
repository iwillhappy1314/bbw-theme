<?php /* Template Name: Post Full Width
Template Post Type: post* */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();


while ( have_posts() ) :
	the_post();
?>

<?php if(!leaky_is_content_restricted()): ?>
<style type="text/css">
	.entry-content-container {
	  display: initial !important;
	}
</style>
<?php endif; ?>

<main id="content" <?php post_class( 'site-main' ); ?> role="main">
	<div class="page-content fullwidth clearfix">
      	<div class="page-content-fullwidth-inner">
			<div id="entry-content-container" class="entry-content-container">
				<?php 
					if (leaky_subscriber_can_view()) {
						the_content();
					}else {
						the_excerpt();
					}
				?>
			</div>
		</div>
	</div>
</main>

	<?php
endwhile;

get_footer();
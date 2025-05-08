<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

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

<?php if(pll_current_language() == 'cn'): ?>
<style type="text/css">
	.um-membership-required .en, .um-membership-required .zh {
		display: none !important;
	}
</style>
<?php elseif(pll_current_language() == 'zh'): ?>
<style type="text/css">
	.um-membership-required .en, .um-membership-required .cn {
		display: none !important;
	}
</style>
<?php else: ?>
<style type="text/css">
	.um-membership-required .cn, .um-membership-required .zh {
		display: none !important;
	}
</style>
<?php endif; ?>

<main id="content" <?php post_class( 'site-main' ); ?> role="main">
	<div class="page-content clearfix">
		<div class="page-content-left">
	      	<div class="page-content-left-inner">
	      		<div class="entry-header">
					<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
						<header class="page-header">
							<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						</header>
					<?php endif; ?>

					<div class="entry-meta"><?php hello_posted_on(); ?></div>

                    <div class="flex items-center mt-2 gap-6">
                        <?php echo eodhistoricaldata(get_the_ID()); ?>

                        <?= bbw_follow_button(get_the_ID()); ?>
                    </div>

					<?php if ( get_post_thumbnail_id() ):?>
						<div class="entry-thumbnail"><?php  echo get_the_post_thumbnail(get_the_ID(), 'full'); ?></div>
					<?php endif; ?>
				</div>
				<div id="entry-content-container" class="entry-content-container">
					<?php 
						/*if (leaky_subscriber_can_view()) {
							the_content();
						}else {
							the_excerpt();
						}*/
						the_content();
					?>
					<div class="post-tags clearfix">
						<div class="cat-links">
							<?php the_terms( get_the_ID(), 'category', '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path d="M 4 4 C 2.9057453 4 2 4.9057453 2 6 L 2 18 C 2 19.094255 2.9057453 20 4 20 L 20 20 C 21.094255 20 22 19.094255 22 18 L 22 8 C 22 6.9057453 21.094255 6 20 6 L 12 6 L 10 4 L 4 4 z M 4 6 L 9.171875 6 L 11.171875 8 L 20 8 L 20 18 L 4 18 L 4 6 z"/></svg>', '<span class="sperator">,</span> ' ); ?>
						</div>
						<?php the_tags( '<span class="tag-links"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 79.97 79.95"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M2,11.41c0,7.18.14,14.1-.05,21a16.6,16.6,0,0,0,5.29,12.9Q21.06,59,34.72,72.78a18.86,18.86,0,0,0,7.88,5c-4.05,3.1-7.45,2.78-10.31,0-10.09-9.87-20-19.91-30-29.91A7.36,7.36,0,0,1,0,42.47Q0,29,0,15.55C0,14.06.16,12.57,2,11.41Z"/><path d="M76.74,31.36C67.41,21.9,58,12.56,48.59,3.14a9.35,9.35,0,0,0-7-3.14C33.11.07,24.64-.06,16.17.06,10.78.13,8.1,3,8,8.52,7.91,12.68,8,16.83,8,21c0,3.83.13,7.65,0,11.47a10.46,10.46,0,0,0,3.34,8.44c9.34,9.22,18.55,18.57,27.87,27.8,4.48,4.44,8,4.5,12.45.15q12.6-12.42,25-25C81,39.35,81.1,35.77,76.74,31.36Zm-48.62-7a3.45,3.45,0,0,1-3.42-4c.11-2.61,1.64-3.8,4.21-3.63,2.42.16,3.32,1.72,3.48,3.75C32.12,23.29,30.62,24.58,28.12,24.39Z"/></g></g></svg>', '<span class="sperator">,</span> ', '</span>' ); ?>
						
					</div>
				</div>
			</div>
		</div>
		<div class="page-content-right sidebar right-sidebar">
	      <?php get_sidebar(); ?>
	    </div>
	</div>

	<?php
		$args = [
	      'post_type' => 'post',
	      'category__not_in' => wp_list_pluck( get_the_terms( get_the_ID(), 'category' ), 'term_id' ),
	      'posts_per_page' => 4
	    ];
		$lastposts = get_posts($args);
		if($lastposts):

	?>
	<div class="recent-articles-wrapper">
		<?php
			echo '<div class="postgrid-wrapper layout4"><div class="postgrid-heading"><h2>'.pll__( 'Recent Articles' ).'</h2></div><div class="postgrid-inner">';
			foreach ( $lastposts as $post ) {
				setup_postdata( $post );
				get_template_part('template-parts/content/content', 'grid-excerpt');

			}
			echo '</div></div>';
			wp_reset_postdata();
		?>
	</div>
	<?php endif; ?>
</main>

	<?php
endwhile;

get_footer();
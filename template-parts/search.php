<?php
/**
 * The template for displaying archive pages.
 *
 * @package HelloElementor
 */
   
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

?>
<main id="content" class="site-main layout3" role="main">

  <?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
    <div class="page-header-wrapper">
      	<div class="page-header">
	        <h1 class="entry-title">
	        	<?php pll_e( 'Search results for' ); ?>:
				<span><?php echo get_search_query(); ?></span>
	        </h1>
      	</div>
    </div>
  <?php endif; ?>

  <div class="page-content clearfix">
  	<?php if ( have_posts() ) : ?>
	    <div class="page-content-left">
	      <div id="loadmore-content" class="page-content-left-inner">
		      <?php
		        while (have_posts() ) {
		         	the_post();
		          get_template_part('template-parts/content/content', 'grid-excerpt');
		        }
		      ?>
	      </div>

	      <!-- Start Navigation -->
	      <?php 
	      global $wp_query;
	      if ( $wp_query->max_num_pages > 1 ) : ?>
	        <nav class="pagination" role="navigation">
	          <div id="btn_loadmore" class="pagination-loadmore" data-loadmore="<?php pll_e( 'Load more' ); ?>" data-loading="<?php pll_e( 'Loading...' ); ?>"><?php pll_e( 'Load more' ); ?></div>
	        </nav>
	        <script type="text/javascript">
	          jQuery( document ).ready(function() {
	            var paged = 1, loaded = true;

	            jQuery('#btn_loadmore').click(function(){
	              if (loaded) {
	              	paged++;

	                var button = jQuery(this),
	                data = {
	                  'action': 'loadmore',
	                  's' : '<?php echo get_search_query(); ?>',
	                  'paged' : paged,
	                  'posts_per_page' : <?php echo get_option('posts_per_page'); ?>,
	                  'lang' : '<?php echo pll_current_language(); ?>',
	                };
	             
	                jQuery.ajax({
	                  url : loadmore_params.ajaxurl,
	                  data : data,
	                  type : 'POST',
	                  beforeSend : function ( xhr ) {
	                    loaded = false;
	                    button.text(button.attr('data-loading'));
	                  },
	                  success : function( data ){
	                    if( data ) {
	                      jQuery('#loadmore-content').append(data);
	                      button.text(button.attr('data-loadmore'));
	                      loaded = true;

	                      if (paged == <?php echo  $wp_query->max_num_pages; ?>) {
	                        loaded = false;
	                        button.remove();
	                      }
	                    } else {
	                      loaded = false;
	                      button.remove();
	                    }
	                  }
	                });
	              }
	            });
	          });
	        </script>
	      <?php endif; ?>
	      <!-- ./Start Navigation -->

	      
	    </div>
    <?php else : ?>
		<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'hello-elementor' ); ?></p>
	<?php endif; ?>
  </div>
 
</main>
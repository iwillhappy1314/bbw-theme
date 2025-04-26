<?php
/**
 * The template for displaying archive pages.
 *
 * @package HelloElementor
 */
   
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


$term = get_queried_object();
$description = get_the_archive_description($term);
$layout = get_field('themer_layouts', $term);
if($term->slug=='esg'||$term->slug=='esg-zh'||$term->slug=='esg-cn'){
    $layout = 'layout3';
}
$posts_per_page = 7;
$hasRestrictionClass = '';
$hasDescription = '';

if ($layout) {
  if ($layout == 'layout2') {
    $posts_per_page = 6;
  }elseif ($layout == 'layout3') {
    $posts_per_page = 12;
  }
}

if (has_term_access_restriction($term->term_id)) {
  $posts_per_page = 12;
  $layout = 'layout3';
  $hasRestrictionClass = 'has-restriction';
}

if ($description) {
  $hasDescription = 'has-desc';
}
$banner=get_field('image_banner',$term);
$banner_class='';
if(!empty($banner)){
    $banner_class='has_banner';
}
?>
<style>
    .has_banner{
        background-image: url("<?php echo $banner; ?>");
        padding: 55px 0px !important;
        background-position: center;
        background-size: cover;
    }
    .has_banner h1.entry-title{
        color: white;
    }
    .esg_desc{
        text-align: center;
    }
    .esg_desc p{
        margin-bottom: 0;
    }
</style>
<main id="content" class="site-main <?php echo $layout; ?>" role="main">

  <?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
    <div class="page-header-wrapper <?php echo $hasRestrictionClass.' '.$hasDescription.' '.$banner_class;  ?>">
      <div class="page-header">
        <h1 class="entry-title">
			<?php
			if($term->slug=='esg'||$term->slug=='esg-zh'||$term->slug=='esg-cn'){
				$text_on_banner=get_field('text_on_banner',$term);
				if(!empty($text_on_banner)){
					echo $text_on_banner;
				}else{
					single_cat_title();
				}
			}else{
			?>
			<?php single_cat_title(); ?>
			<?php } ?>
		  </h1>
        <?php if($description && $term->slug!='esg'&&$term->slug!='esg-zh'&&$term->slug!='esg-cn'): ?>
        <div class="archive-description"><?php echo $description; ?></div>
        <?php endif; ?>
      </div>
       <?php
        if (has_term_access_restriction($term->term_id)) {
          hello_premium_dropdown_terms();
        }
      ?>
    </div>
    
  <?php endif; ?>
    <?php if($description && ($term->slug=='esg'||$term->slug=='esg-zh'||$term->slug=='esg-cn')): ?>
    <div class="archive-description esg_desc"><?php echo $description; ?></div>
    <?php endif; ?>
  <div class="page-content clearfix">
    <div class="page-content-left">
      <div id="loadmore-content" class="page-content-left-inner">
      <?php
        $term_type = 'is_category';

        $args = [
          'posts_per_page' => $posts_per_page,
          'post_type' => 'post',
          'post_status' => 'publish',
        ];

        if (is_category()) {
          $term_type = 'is_category';
          $args['cat'] = $term->term_id;
        }elseif (is_tag()) {
          $term_type = 'is_tag';
          $args['tag_id'] = $term->term_id;
        }

        $the_query = new WP_Query( $args );

        while ( $the_query->have_posts() ) {
          $the_query->the_post();
          get_template_part('template-parts/content/content', 'grid-excerpt');
        } 
      ?>
      </div>

      <!-- Start Navigation -->
      <?php if ( $the_query->max_num_pages > 1 ) : ?>
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
                  'type' : '<?php echo $term_type; ?>',
                  'tid': <?php echo $term->term_id; ?>,
                  'paged' : paged,
                  'posts_per_page' : <?php echo $posts_per_page; ?>,
                  'max_num_pages' : <?php echo $the_query->max_num_pages; ?>,
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

                      if (paged >= <?php echo  $the_query->max_num_pages; ?>) {
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


    <!-- Start Sidebar -->
    <?php if($layout !== 'layout3'): ?>
    <div class="page-content-right sidebar right-sidebar">
      <?php get_sidebar(); ?>
    </div>
    <?php endif; ?>
    <!-- ./End Sidebar -->


  </div>
 
</main>
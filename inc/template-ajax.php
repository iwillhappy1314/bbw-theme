<?php

function loadmore_ajax_handler(){
	$args = [
    'post_type' => 'post',
    'post_status' => 'publish',
    'lang' => $_POST['lang'],
    'paged' => $_POST['paged'],
    'posts_per_page' => $_POST['posts_per_page']
  ];

  if (isset($_POST['tid'])) {
    if ($_POST['type'] == 'is_category') {
      $args['cat'] = $_POST['tid'];
    }elseif ($_POST['type'] == 'is_tag') {
      $args['tag_id'] = $_POST['tid'];
    }
  }

  if (isset($_POST['s'])) {
    $args['s'] = $_POST['s'];
  }

  $the_query = new WP_Query( $args );

  if ( $the_query->have_posts() ) {
  	while ( $the_query->have_posts() ) {
  		$the_query->the_post();
  		get_template_part('template-parts/content/content', 'grid-excerpt');
  	}
  }
  wp_reset_postdata();

	die;
}
add_action('wp_ajax_loadmore', 'loadmore_ajax_handler');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_ajax_handler');
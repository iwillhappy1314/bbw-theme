<?php

function map_bamboo_element() {
  add_shortcode('recentposts', 'shortcode_recent_posts');
  add_shortcode('listchildcategories', 'shortcode_listcategories');
  add_shortcode('listcategorypost', 'shortcode_listcategorypost');
  add_shortcode('catlist', 'shortcode_catlist');
  add_shortcode('postlist', 'shortcode_postlist');
  add_shortcode('postgrid', 'shortcode_postgrid');
  add_shortcode('recentpostgrid', 'shortcode_recentpostgrid');
  add_shortcode('highlightpostgrid', 'shortcode_highlightpostgrid');
  add_shortcode('main_slider', 'shortcode_main_slider');
  

  add_shortcode('bamboorss', 'shortcode_bamboorss');

  // ultimatemember
  add_shortcode('CustomLoginButton', 'shortcode_CustomLoginButton');
  add_shortcode('CustomSignUpJsCss', 'shortcode_CustomSignUpJsCss');
  // leaky-paywall
  add_shortcode('LeakyPaywallUpgradeMessage', 'shortcode_LeakyPaywallUpgradeMessage');
  // Polylang
  add_shortcode('PasswordRule', 'shortcode_PasswordRule');
}
add_action('init', 'map_bamboo_element', 10);


/**
 * ========================================================
 * POSTS
 * [listchildcategories include="95,4297,6,3,4,5,2,7,8,7967"]
 */
if (!function_exists('shortcode_listcategories')) {
  function shortcode_listcategories($atts) {
    ob_start();

    $atts = shortcode_atts( array(
      'include' => [4297,5,6,7,3,4,2,8,7967]
    ), $atts, 'bartag' );

    $args = array(
      'taxonomy' => 'category',
      'include' => $atts['include'],
      'orderby'  => 'include'
    );

    $terms = get_terms( $args );

    if ($terms) {
      echo '<div class="category-list-items"><ul>';
      foreach ( $terms as $term ) {
        $term = get_term(pll_get_term($term->term_id));

        echo '<li class="cat-list-item"><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
      }
      echo '</ul></div>';
    }

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}


if (!function_exists('shortcode_listcategorypost')) {
  // [listcategorypost ids="13467,13469" fimg="show" border="show" heading="Premium Content" viewall="https://bam.cxstaging.com/category/newsletter/"]

  function shortcode_listcategorypost($atts) {
    ob_start();

    $atts = shortcode_atts( array(
      'ids' => [],
      'fimg' => 'hide',
      'border' => 'hide',
      'heading' => '',
      'viewall' => ''
    ), $atts, 'bartag' );

    if (!empty($atts['ids'])){
      $term_args = array(
        'taxonomy' => 'category',
        'include' => $atts['ids'],
        'orderby'  => 'include',
        'hide_empty' => true
      );

      $terms = get_terms( $term_args );

      if (null !== $terms) {
        global $post;

        echo '<div class="postlist-wrapper '.$atts['border'].'-border">';
        echo '<div class="postlist-heading"><h2>'.$atts['heading'].' <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.25 8.5L4.75 5L1.25 1.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></h2></div>';
        echo '<div class="postlist-inner">';

        $number = 0;

        foreach ( $terms as $term ) {
          $icon = get_field('icon', $term);
          $post = get_posts([
            'category' => $term->term_id,
            'numberposts' => 1
          ])[0];
          setup_postdata( $post );
          ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
            <div class="post-inner">
              <?php  if($atts['fimg'] == 'show'): if ($number == 0): if ( get_post_thumbnail_id() ): ?>
              <div class="entry-thumbnail">
                <a href="<?php echo esc_url( get_permalink() ); ?>"  class="entry-thumbnail-inner">
                  <?php  echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
                  </a>
              </div>
              <?php endif; endif; endif; ?>

              <div class="entry-content">
                <h3><a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-title"><?php echo clearNbsp(get_the_title()); ?></a></h3>
                <div class="entry-meta"><?php hello_posted_on(); ?></div>
              </div>
            </div>
          </article>

          <?php
          $number++;
        }

        wp_reset_postdata();

        echo '</div><div class="viewall-wrapper"><a href="'.$atts['viewall'].'" class="btn-viewall">'.pll__( 'View All' ).'</a></div></div>';
      }

    }

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}

if (!function_exists('shortcode_catlist')) {
  // [catlist cat="13467" fimg="show" border="show"]

  function shortcode_catlist($atts) {
    ob_start();

    $atts = shortcode_atts( array(
      'cat' => '',
      'icon' => 'hide',
      'fimg' => 'hide',
      'border' => 'hide',
      'cclass' => '',
      'limit' => 2
    ), $atts, 'bartag' );

    $langis=pll_current_language();
    $pll_get_term = pll_get_term($atts['cat']);

    if ($pll_get_term){

      $term = get_term( $pll_get_term);

      $args = [
        'lang' => $langis,
        'post_type' => 'post',
        'posts_per_page' => $atts['limit'],
        'cat' => $pll_get_term
      ];

      $the_query = new WP_Query( $args );

      if ( $the_query->have_posts() ) {
        echo '<div class="postlist-wrapper '.$atts['border'].'-border '.$atts['cclass'].'"><div class="postlist-inner">';

        $number = 0;

        while ( $the_query->have_posts() ) {
          $the_query->the_post();
        ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
            <div class="post-inner">
              <?php if($atts['fimg'] == 'show'): ?>
              <div class="entry-thumbnail">
                <a href="<?php echo esc_url( get_permalink() ); ?>"  class="entry-thumbnail-inner">
                  <?php  echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
                  </a>
              </div>
              <?php endif; ?>

              <div class="entry-content">
                <h3><a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-title"><?php echo clearNbsp(get_the_title()); ?></a></h3>
                <div class="entry-meta-group">
                  <div class="entry-meta clearfix">
                    <?php
                    if ($atts['icon'] == 'show') {
                      show_the_child_category(get_the_category(get_the_ID()));
                    }

                    hello_posted_on(); ?>
                    </div>
                  <?php echo eodhistoricaldata(get_the_ID()); ?>
                </div>

              </div>
            </div>
          </article>

          <?php
          $number++;
        }
        echo '</div><div class="viewall-wrapper"><a href="'.get_term_link($term->term_id).'" class="btn-viewall">'.pll__( 'View All' ).' <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.28564 8.42855L4.71422 4.99998L1.28564 1.57141" stroke="#5D853A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div>';
      }

      wp_reset_postdata();
    }

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}

if (!function_exists('shortcode_postgrid')) {
  function shortcode_postgrid($atts) {
    ob_start();

    $atts = shortcode_atts( array(
      'layout' => 1,
      'limit' => 4,
      'cat' => 63,
      'stock' => 'show',
      'cclass' => ''
    ), $atts, 'bartag' );


    $layout = 'layout1';

    if ($atts['layout'] == 2) {
      $layout = 'layout2';
    }else if ($atts['layout'] == 3) {
      $atts['limit'] = 3;
      $layout = 'layout3';
    }else if ($atts['layout'] == 4) {
      $layout = 'layout4';
    }else if ($atts['layout'] == 5) {
      $atts['limit'] = 6;
      $layout = 'layout5';
    }

    $langis=pll_current_language();
    $pll_get_term = pll_get_term($atts['cat']);

    if ($pll_get_term):

      $term = get_term( $pll_get_term);

      $args = [
        'lang' => $langis,
        'post_type' => 'post',
        'posts_per_page' => $atts['limit'],
        'cat' => $pll_get_term
      ];

      $the_query = new WP_Query( $args );

      if ( $the_query->have_posts() ) {

        // Get ACF file term icon
        $getterm_icon = get_field('icon', $term);
        $termicon = '';
        if (!empty( $getterm_icon )) {
          $termicon = '<img src="'.esc_url($getterm_icon).'" class="ic" />';
        }

        echo '<div class="postgrid-wrapper '.$layout.' '.$atts['cclass'].'">';
        echo '<div class="postgrid-heading"><h2>'.$term->name.' '.$termicon.'</h2><div class="viewall-wrapper"><a href="'.get_term_link($term->term_id).'" class="btn-viewall">'.pll__( 'View All' ).' <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.28564 8.42855L4.71422 4.99998L1.28564 1.57141" stroke="#5D853A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a></div></div>';
        echo '<div class="postgrid-inner">';
        $number = 0;
        $post_count = 1;

        if ($atts['layout'] == 2 || $atts['layout'] == 3) {
          echo '<div class="postgrid-col1">';
        }

        while ( $the_query->have_posts() ) {
          $the_query->the_post();

          if ($atts['layout'] == 2 || $atts['layout'] == 3) {
            if ($number == 1) {
              echo '</div><div class="postgrid-col2"><div class="postgrid-col2-inner">';
            }
          }

          if ($atts['layout'] == 5) {
            
            if ($atts['stock'] === 'show') {
              get_template_part('template-parts/content/content', 'grid-layout5');
            }else {
              get_template_part('template-parts/content/content', 'grid-layout5-no-stock');
            }
          }else {
            if ($atts['layout'] == 1) {

              if ($post_count < $the_query->post_count) {
                
                if ($atts['stock'] === 'show') {
                  get_template_part('template-parts/content/content', 'grid-excerpt');
                }else {
                  get_template_part('template-parts/content/content', 'grid-excerpt-no-stock');
                }

              }else {
                $frontpage_id = get_option( 'page_on_front');

                
                if (get_field('ad_layout1_option1', $frontpage_id) === 'enable') {
                  
                  if (get_field('ad_layout1_option2', $frontpage_id) === 'html') {
                    // HTML - Category IPO (AD)
                    $ad_layout1_html = get_field('ad_layout1_html', $frontpage_id);
                    if ($ad_layout1_html) {
                      echo '<article class="post post-adblock"><div class="post-inner">'.$ad_layout1_html.'</div></article>';
                    }else {
                      if ($atts['stock'] === 'show') {
                        get_template_part('template-parts/content/content', 'grid-excerpt');
                      }else {
                        get_template_part('template-parts/content/content', 'grid-excerpt-no-stock');
                      }
                    }
                  }else if(get_field('ad_layout1_option2', $frontpage_id) === 'banner') {
                    // Banner - Category IPO (AD)
                    $ad_layout1_banner = get_field('ad_layout1_banner', $frontpage_id);
                    $ad_layout1_default_link = get_field('ad_layout1_default_link', $frontpage_id);
                    if ($ad_layout1_banner) {
                      echo '<article class="post post-adblock"><div class="post-inner"><a href="'.$ad_layout1_default_link.'" target="_blank"><img src="'.$ad_layout1_banner.'" class="w-100"/></a></div></article>';
                    }else {
                      if ($atts['stock'] === 'show') {
                        get_template_part('template-parts/content/content', 'grid-excerpt');
                      }else {
                        get_template_part('template-parts/content/content', 'grid-excerpt-no-stock');
                      }
                    }

                  }else {
                    // Default - Category IPO (AD)

                    $pll_current_language = pll_current_language();
                    $ad_layout1_default_title = get_field('ad_layout1_default_title_'.$pll_current_language, $frontpage_id);
                    $ad_layout1_default_excerpt = get_field('ad_layout1_default_excerpt_'.$pll_current_language, $frontpage_id);
                    $ad_layout1_default_link = get_field('ad_layout1_default_link', $frontpage_id);
                    ?>
                    <article class="post post-addefault">
                      <div class="post-inner">
                        <div class="entry-content">
                            <a href="<?php echo $ad_layout1_default_link ?>" target="_blank">
                              <h3 class="entry-title"><?php echo $ad_layout1_default_title; ?></h3>
                              <p class="entry-excerpt"><?php echo $ad_layout1_default_excerpt; ?></p>
                            </a>
                        </div>
                      </div>
                    </article>
                    <?php
                  }
                  
                }else {
                  if ($atts['stock'] === 'show') {
                    get_template_part('template-parts/content/content', 'grid-excerpt');
                  }else {
                    get_template_part('template-parts/content/content', 'grid-excerpt-no-stock');
                  }
                }
              }

            }else {
              if ($atts['stock'] === 'show') {
                get_template_part('template-parts/content/content', 'grid-excerpt');
              }else {
                get_template_part('template-parts/content/content', 'grid-excerpt-no-stock');
              }
            }
          }

          $post_count++;
          $number++;
        }

        if ($atts['layout'] == 2 || $atts['layout'] == 3) {
          echo '</div></div>';
        }

        echo '</div></div>';
      }
      wp_reset_postdata();

    endif;

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}

if (!function_exists('shortcode_recentpostgrid')) {
  function shortcode_recentpostgrid($atts) {
    ob_start();

    $atts = shortcode_atts( array(
      'limit' => 4,
      'stock' => 'show',
      'cclass' => ''
    ), $atts, 'bartag' );

    $langis = pll_current_language();

    $args = [
      'lang' => $langis,
      'post_type' => 'post',
      'posts_per_page' => $atts['limit']
    ];

    $featured_posts = get_field('featured_post_ids_'.$langis);
    if ($featured_posts) {
      $featured_posts_arr = explode(",", $featured_posts);
      if (is_array($featured_posts_arr)) {
        $args['post__not_in'] = $featured_posts_arr;
      }
    }

    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ) {

      echo '<div class="postgrid-wrapper recentpostgrid layout2 '.$atts['cclass'].'"><div class="postgrid-inner"><div class="postgrid-col2">';

      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
          <div class="post-inner">

            <?php get_template_part('template-parts/content/entry/entry', 'thumbnail'); ?>
            
            <div class="entry-content">
                <h3><a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-title"><?php echo clearNbsp(get_the_title()); ?></a></h3>
                <div class="entry-excerpt">
                  <?php 
                    if (has_excerpt()) {
                      echo wp_strip_all_tags(get_the_excerpt());
                    }else {
                      echo wp_trim_words(get_the_content(), 30); 
                    }
                  ?>
                </div>
                <div class="entry-meta-group">
                  <div class="entry-meta">
                    <?php show_the_child_category(get_the_category(get_the_ID())); hello_posted_on(); ?>
                    </div>
                  <?php echo eodhistoricaldata(get_the_ID()); ?>
                </div>
            </div>
          </div>
        </article>
        <?php
      }

      echo '</div></div></div>';
    }
    wp_reset_postdata();


    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}

if (!function_exists('shortcode_postlist')) {
  function shortcode_postlist($atts) {
    ob_start();

    $langis=pll_current_language();

    $atts = shortcode_atts( array(
      'limit' => 2,
      'cat' => 63,
      'heading' => 'show',
      'fimg' => 'hide',
      'border' => 'hide'
    ), $atts, 'bartag' );

    $pll_get_term = pll_get_term($atts['cat']);

    if ($pll_get_term):
      $term = get_term( $pll_get_term);

      $args = [
        'lang' => $langis,
        'post_type' => 'post',
        'posts_per_page' => $atts['limit'],
        'cat' => $pll_get_term
      ];

      $the_query = new WP_Query( $args );

      if ( $the_query->have_posts() ) {

        echo '<div class="postlist-wrapper '.$atts['border'].'-border">';
        if ($atts['heading'] != 'hide') {
          echo '<div class="postlist-heading"><h2>'.$term->name.' <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.25 8.5L4.75 5L1.25 1.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></h2></div>';
        }
        echo '<div class="postlist-inner">';

        $number = 0;
        while ( $the_query->have_posts() ) {
          $the_query->the_post();

          if ($atts['fimg'] == 'show') {
            if ($number == 0) {
              get_template_part('template-parts/content/content', 'list-excerpt-hasthumbnail');
            } else {
              get_template_part('template-parts/content/content', 'list-excerpt');
            }
          }else {
            get_template_part('template-parts/content/content', 'list-excerpt');
          }

          $number++;
        }

        echo '</div><div class="viewall-wrapper"><a href="'.get_term_link($term->term_id).'" class="btn-viewall">'.pll__( 'View All' ).'</a></div></div>';
      }
      wp_reset_postdata();
    endif;


    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}

if (!function_exists('shortcode_recent_posts')) {
  function shortcode_recent_posts($atts) {
    ob_start();

    $langis=pll_current_language();
    $heading = pll__( 'Recent Articles' );

    $atts = shortcode_atts( array(
      'limit' => 7,
      'cat' => '',
      'cid' => '',
      'fimg' => 'hide',
      'exclude' => ''
    ), $atts, 'bartag' );

    $args = [
      'lang' => $langis,
      'post_type' => 'post',
      'posts_per_page' => $atts['limit']
    ];

    if (is_single()) {
      $heading = pll__( 'Related Articles' );
      $categories = wp_list_pluck( get_the_terms( get_the_ID(), 'category' ), 'term_id', 'term_id');
      $args['category__in'] =  $categories;
      $args['post__not_in'] = array(get_the_ID());

      if (!empty($atts['exclude'])) {
        $pll_get_term = pll_get_term($atts['exclude']);

        $terms = get_term_children($pll_get_term, 'category' );
        $terms[] = $pll_get_term;

        foreach ($terms as $term) {
          unset($categories[$term]);
        }

        if (sizeof($categories) <= 0) {
          $lastposts = get_posts([
            'fields' => 'ids',
            'post_type' => 'post',
            'posts_per_page' => 4,
            'post__not_in' => array(get_the_ID()),
            'category__not_in' => wp_list_pluck( get_the_terms( get_the_ID(), 'category' ), 'term_id' )
          ]);
          $lastposts[] = get_the_ID();

          $args['post__not_in'] = $lastposts;

          $args['category__in'] =  wp_list_pluck(get_terms( array( 
            'taxonomy' => 'category',
            'exclude'   => $terms
          )), 'term_id');
        }
      }

    }elseif(is_category()) {
      $args['category__not_in'] = array(get_queried_object()->term_id);
    }elseif(is_tag()) {
      $args['tag_id'] = get_queried_object()->term_id;
    }elseif(!empty( $atts['cid'])) {
      $term = get_term(pll_get_term($atts['cid']));
      $args['cat'] = $term->term_id;
      $heading = $term->name;
    }

    $the_query = new WP_Query( $args );


    if ( $the_query->have_posts() ) {

      echo '<div class="postlist-wrapper">';
      echo '<div class="postlist-heading"><h2>'.$heading.' <svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.25 8.5L4.75 5L1.25 1.5" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></h2></div>';
      echo '<div class="postlist-inner">';

      $number = 0;
      while ( $the_query->have_posts() ) {
        $the_query->the_post();

        if ($atts['fimg'] == 'show') {
          if ($number == 0) {
            if (!empty( $atts['cid'])) {
              get_template_part('template-parts/content/content', 'recent-list-excerpt-hasthumbnail2');
            }else {
              get_template_part('template-parts/content/content', 'recent-list-excerpt-hasthumbnail');
            }
          } else {
            if (!empty( $atts['cid'])) {
              get_template_part('template-parts/content/content', 'recent-list-excerpt2');
            }else {
              get_template_part('template-parts/content/content', 'recent-list-excerpt');
            }
          }
        }else {
          if (!empty( $atts['cid'])) {
            get_template_part('template-parts/content/content', 'recent-list-excerpt2');
          }else {
            get_template_part('template-parts/content/content', 'recent-list-excerpt');
          }
        }

        $number++;
      }

      if(!empty( $atts['cid'])) {
        $term = get_term(pll_get_term($atts['cid']));
        echo '</div><div class="viewall-wrapper"><a href="'.get_term_link($term).'" class="btn-viewall">'.pll__( 'View All' ).'</a></div></div>';
      }

      echo '</div></div>';
    }
    wp_reset_postdata();

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}


/**
 * ========================================================
 * RSS FEED
 */
if (!function_exists('shortcode_bamboorss')) {
  function shortcode_bamboorss() {
    ob_start();

    echo '<div class="rss-category-wrapper"><div class="rss-category-inner"><ul>';
    $our_walker = new Walker_Category_Custom();
    wp_list_categories(array('walker'=>$our_walker,'title_li'=>'','orderby'=>'name','hide_empty'=>0));
    echo '</ul></div></div>';


    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}
/**
 * ========================================================
 * Main Slider
 * ========================================================
 */
if (!function_exists('shortcode_main_slider')) {
  function shortcode_main_slider($atts) {
    ob_start();
    
    $atts = shortcode_atts( array(
      'posts' => [],
      'stock' => 'show'
    ), $atts, 'bartag' );

    $pll_current_language = pll_current_language();
    $featured_posts_by = get_field('display_featured_posts_by');

    $args = [
      'lang' => $pll_current_language,
      'post_type' => 'post',
      'posts_per_page' => 4
    ];

    if ($featured_posts_by) {
      if ($featured_posts_by === 'ids') {
        $featured_posts = get_field('featured_post_ids_'.$pll_current_language);
        if ($featured_posts) {
          $featured_posts_arr = explode(",", $featured_posts);
          if (is_array($featured_posts_arr)) {
            $args['posts_per_page'] = -1;
            $args['post__in'] = $featured_posts_arr;
            $args['orderby'] = 'post__in';
          }
        }
      }
    }

    $postslist = get_posts($args);

    if ( $postslist ) {
      $thumbssliderHTML = '';
    ?>
      <div class="main-slider-wrapper">
        <div class="main-slider-inner">
          <div id="mainSwiper" class="swiper">
            <div class="swiper-wrapper">
              <?php foreach ( $postslist as $post ) : setup_postdata( $post );
                $entrymeta = hello_posted_on_base($post->ID);
                $permalink = esc_url( get_permalink($post->ID) );
                $post_title = clearNbsp($post->post_title);

                $swiperSlideBgImageStyle = get_stylesheet_directory_uri().'/assets/images/bg-placeholder.jpg';
                $thumbImgHTML = '';

                if (get_post_thumbnail_id($post->ID)) {
                  $swiperSlideBgImageStyle = get_the_post_thumbnail_url($post->ID, 'full');
                  $thumbImgHTML = '<div class="entry-thumbnail">
                      <a href="'.$permalink.'"  class="entry-thumbnail-inner">'.get_the_post_thumbnail($post->ID, 'thumbnail').'</a>
                  </div>';
                }

                $stockPrice = eodhistoricaldata($post->ID); 

                if ($atts['stock'] !== 'show') {
                  $stockPrice = '';
                }

                $thumbssliderHTML.='<article class="post">
                  <div class="post-inner">
                    '.$thumbImgHTML.'
                    <div class="entry-content">
                      <div class="entry-meta">'.$entrymeta.'</div>
                      <h3><a  href="'.$permalink.'" class="entry-title">'.$post_title.'</a></h3>
                      '.$stockPrice.'
                    </div>
                  </div>
                </article>';
                
              ?>
                <div class="swiper-slide" style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.44) -14.83%, rgba(0, 0, 0, 0) 105.91%), linear-gradient(90deg, rgba(0, 0, 0, 0.74) -20.52%, rgba(0, 0, 0, 0) 86.08%), url(<?php echo $swiperSlideBgImageStyle; ?>);">
                  <div class="swiper-slide-container">
                    <div class="swiper-slide-inner">
                      <?php show_the_child_category(get_the_category($post->ID)); ?>
                      <h1 class="entry-title"><a href="<?php echo $permalink; ?>"><?php echo $post_title; ?></a></h1>
                      <div class="entry-meta"><?php echo $entrymeta; ?></div>
                      <?php
                        if ($atts['stock'] === 'show') {
                          echo eodhistoricaldata($post->ID); 
                        }
                      ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; wp_reset_postdata();?>
            </div>
            
            <div class="swiper-actions">
              <div class="swiper-action-container">
                <div class="swiper-action-inner">
                  <!-- If we need pagination -->
                  <div class="swiper-pagination"></div>

                  <div class="swiper-pagination-progressbar"><div id="progress" ></div></div>

                  <!-- If we need navigation buttons -->
                  <div class="swiper-button-prev"><svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.5 18.25L17 13.75L12.5 18.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="17" cy="17" r="16.5" transform="rotate(-90 17 17)" stroke="white"/></svg></div>
                  <div class="swiper-button-next"><svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.5 18.25L17 13.75L12.5 18.25" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="17" cy="17" r="16.5" transform="rotate(-90 17 17)" stroke="white"/></svg></div>

                </div>
              </div>
            </div>
          </div>

          <div class="thumbsslider-wrapper">
            <div id="thumbsslider-inner" class="thumbsslider-inner">
              <?php echo $thumbssliderHTML; ?>
            </div>
          </div>
        </div>
      </div>

      <script>
        var getTimeout = function () { var e = setTimeout, b = {}; setTimeout = function (a, c) { var d = e(a, c); b[d] = [Date.now(), c]; return d }; return function (a) { return (a = b[a]) ? Math.max(a[1] - Date.now() + a[0], 0) : NaN } }();
        
        function sanitisePercentage(i) {
          return Math.min(100, Math.max(0, i));
        }


        var setSliderInterval = setInterval(function() {
          if (document.readyState !== 'complete') {
            return false;
          }
          clearInterval(setSliderInterval);

          // Slider
          var percentTime;
          var tick;
          var autoplay = 5000;
          var progressBar = document.getElementById("progress");

          var mySwiper = new Swiper("#mainSwiper", {
            loop: true,
            effect: "fade",
            autoplay: {
              delay: autoplay,
              disableOnInteraction: false
            },
            pagination: {
              el: ".swiper-pagination",
              type: "fraction"
            },
            navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev"
            },
            on: {
              progress: function() {
                var swiper = this;
                var currentIndex = swiper.realIndex + 1;
                var currentSlide = swiper.slides[currentIndex];
                updateSwiperProgressBar(progressBar, autoplay);
              }
            }
          });

          function updateSwiperProgressBar(bar, slideDelay) {

            function startProgressBar() {
              resetProgressBar();
              tick = setInterval(progress, 50);
            }

            function progress() {

              var timeLeft = getTimeout(mySwiper.autoplay.timeout);

              if (mySwiper.autoplay.running && !mySwiper.autoplay.paused) {
                percentTime = sanitisePercentage(100 - Math.round(timeLeft / slideDelay * 100));
                bar.style.width = percentTime + '%';

                if (percentTime > 100) {
                  resetProgressBar();
                }
              }

              if (mySwiper.autoplay.paused) {
                percentTime = 0;
                bar.style.width = 0;
              }

            }

            function resetProgressBar() {
              percentTime = 0;
              bar.style.width = 0;
              clearInterval(tick);
            }

            startProgressBar();
          }


        }, 500);
      </script>
      <?php
    }

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}


if (!function_exists('shortcode_highlightpostgrid')) {
  function shortcode_highlightpostgrid($atts) {
    ob_start();

    $atts = shortcode_atts( array(
      'limit' => 6,
    ), $atts, 'bartag' );

    $pll_current_language = pll_current_language();
    $featured_posts_by = get_field('display_featured_posts_by');

    $args = [
      'lang' => $pll_current_language,
      'post_type' => 'post',
      'posts_per_page' => 6
    ];


    if ($featured_posts_by !== 'recent') {
      $featured_posts = get_field('featured_post_ids_'.$pll_current_language);
      if ($featured_posts) {
        $featured_posts_arr = explode(",", $featured_posts);
        if (is_array($featured_posts_arr)) {
          $args['posts_per_page'] = -1;
          $args['post__in'] = $featured_posts_arr;
          $args['orderby'] = 'post__in';
        }
      }
    }

    $postslist = get_posts($args);

    if ( $postslist ) {

      echo '<div class="postgrid-wrapper postgrid-highlight"><div class="postgrid-inner">';

      foreach ( $postslist as $post ) {
        setup_postdata( $post );

        $permalink = esc_url( get_permalink($post->ID) );
        $post_title = clearNbsp($post->post_title);

        ?>
        <article id="post-<?php echo $post->ID; ?>" class="post">
          <div class="post-inner">

            <?php if ( get_post_thumbnail_id($post->ID) ):?>
            <div class="entry-thumbnail">
              <a href="<?php echo $permalink; ?>"  class="entry-thumbnail-inner">
                <?php  echo get_the_post_thumbnail($post->ID, 'thumbnail'); ?>
                </a>
            </div>
            <?php endif; ?>
            
            <div class="entry-content">
                <h3><a href="<?php echo $permalink; ?>" class="entry-title"><?php echo $post_title; ?></a></h3>
                <div class="entry-excerpt">
                  <?php 
                    if (has_excerpt($post->ID)) {
                      echo wp_strip_all_tags($post->post_excerpt);
                    }else {
                      echo wp_trim_words($post->post_content, 30); 
                    }
                  ?>
                </div>
                <div class="entry-meta-group">
                  <div class="entry-meta">
                    <?php show_the_child_category(get_the_category($post->ID)); echo hello_posted_on_base($post->ID); ?>
                  </div>
                  <?php echo eodhistoricaldata($post->ID); ?>
                </div>
            </div>
          </div>
        </article>
        <?php
      }

      echo '</div></div>';
    }
    wp_reset_postdata();

    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}

/**
 * ========================================================
 * ultimatemember
 * ========================================================
 */
// [CustomLoginButton]
if (!function_exists('shortcode_CustomLoginButton')) {
  function shortcode_CustomLoginButton() {
    ob_start();
    $rid = '';
    if (isset($_GET['rid'])) {
      $rid = $_GET['rid'];
    }
    ?>
    <div class="row custom-login-button">
      <div class="col-6"><div id="customrememberme" class="um-field um-field-checkbox  um-field-customrememberme um-field-checkbox um-field-type_checkbox"><div class="um-field-area"><label class="um-field-checkbox um-field-half"><input type="checkbox" name="customrememberme" value="customrememberme"><span class="um-field-checkbox-state"><i class="um-icon-android-checkbox-outline-blank"></i></span><span class="um-field-checkbox-option"><?php echo pll__( 'Remember me' ); ?></span></label><div class="um-clear"></div></div></div></div>
      <div class="col-6"><a href="<?php echo get_permalink(pll_get_post( UM()->options()->get( 'core_password-reset' ))); ?>?rid=<?php echo $rid; ?>" class="forgotpassword"><?php echo pll__( 'Forgot password?' ); ?></a></div>
      <div class="col-12"><a href="javascript:void(0)" id="button-submit" class="elementor-button-submit elementor-button"><?php echo pll__( 'Sign in' ); ?></a></div>
      <div class="col-12 donothaveaccount"><?php echo pll__( 'Don’t have an account?' ); ?> <a href="<?php echo get_permalink(pll_get_post( UM()->options()->get( 'core_register' ))); ?>?rid=<?php echo $rid; ?>"><?php echo pll__( 'Sign up' ); ?></a></div>
    </div>
    <script type="text/javascript">
      document.getElementById("customrememberme").onclick = function(e){
        document.querySelector('[name="rememberme"]').checked = true;
      }
      document.getElementById("button-submit").onclick = function(e){
        document.querySelector('.um-col-alt [type="submit"]').click();
      }
    </script>
  <?php
    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}


// [CustomSignUpJsCss]
if (!function_exists('shortcode_CustomSignUpJsCss')) {
  function shortcode_CustomSignUpJsCss() {
    ?>
    <style type="text/css">
      .um-field-policy, .um-field-mailchimp, fieldset.um-account-fieldset {
        padding: 0 !important;
      }
      .um-field-mailchimp .um-field-label, .um-field-policy .um-field-area, .um-field-MMERGE4 {
        display: none;
      }
      fieldset.um-account-fieldset {
        margin: 0 !important;
      }
      .um-account-fieldset-dropdown .um-field, .um-field-checkbox-mailchimp {
        padding-top: 0 !important;
      }
    </style>
    <script type="text/javascript">
      var setCustomSignUpJsCss = setInterval(function() {
        if (document.readyState !== 'complete') {
          return false;
        }
        clearInterval(setCustomSignUpJsCss);
        var label = document.querySelector('#um_field_policy2 label.um-field-checkbox');
        var policy = document.querySelector('.um-field-policy input[type="checkbox"]');

        if (policy.checked == true) {
          label.classList.add('active');
          policy.checked = true;
        }
        document.getElementById('um_field_policy2').addEventListener("click", function() {
          if (!label.classList.contains('active')) {
            label.classList.add('active');
            policy.checked = true;
            
          }else {
            label.classList.remove('active');
            policy.checked = false;
          }
        });
      }, 500);

    </script>
    <?php
  }
}
/**
 * ========================================================
 * ultimatemember
 * ========================================================
 */
if (!function_exists('shortcode_LeakyPaywallUpgradeMessage')) {
  function shortcode_LeakyPaywallUpgradeMessage() {
    ob_start();
    ?>
    <div class="umRestricted-wrapper">
      <div class="umRestricted-inner">
        <div class="umRestricted-content">
          <?php get_template_part('template-parts/content/popup-um-membership-required'); ?>
          <div class="umRestricted-content-inner">
            <?php echo get_the_content(); ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    $ob_content = ob_get_contents(); 
    ob_end_clean();
    return $ob_content;
  }
}


/**
 * ========================================================
 * Polylang
 * ========================================================
 */
if (!function_exists('shortcode_PasswordRule')) {
  function shortcode_PasswordRule() {
    return '<div class="label-rule">'.pll__('Your password * must contain at least 8 characters').'</div>';
  }
}
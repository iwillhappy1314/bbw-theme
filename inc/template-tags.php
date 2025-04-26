<?php 

if ( ! function_exists( 'hello_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 * 'j M Y'
	 */
	function hello_posted_on() {

		$date_format = get_option( 'date_format' );

		if (is_front_page() || is_page(27193) || is_page(28554) || is_page(28546)) {
			$date_format = 'M. j';
		}

		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date($date_format) ),
			esc_html( get_the_date($date_format) ),
			esc_attr( get_the_date($date_format) ),
			esc_html( get_the_date($date_format) )
		);

		// '<span class="posted-on">'.get_the_author_meta( 'display_name', get_post_field('post_author', get_the_ID()) ).' - %3$s</span>',
		printf(
			'%3$s</span>',
			'',
			'',
			$time_string
		);
	}
}


if ( ! function_exists( 'hello_posted_on_base' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 * 'j M Y'
	 */
	function hello_posted_on_base($post_id) {

		$date_format = get_option( 'date_format',$post_id);

		if (is_front_page() || is_page(27193) || is_page(28554) || is_page(28546)) {
			$date_format = 'M. j';
		}

		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( $date_format,$post_id) ),
			esc_html( get_the_date( $date_format,$post_id) ),
			esc_attr( get_the_date( $date_format,$post_id) ),
			esc_html( get_the_date( $date_format,$post_id) )
		);



		// '<span class="posted-on">'.get_the_author_meta( 'display_name', get_post_field('post_author', $post_id) ).' - '.$time_string.'</span>';

		return $time_string.'</span>';
	}
}

if ( ! function_exists( 'wpdocs_get_pages_by_template_filename' ) ) {
	function wpdocs_get_pages_by_template_filename( $page_template_filename ) {
	    return get_pages( array(
	        'meta_key' => '_wp_page_template',
	        'meta_value' => $page_template_filename
	    ) );
	}
}

if ( ! function_exists( 'hello_premium_dropdown_terms' ) ) {
	function hello_premium_dropdown_terms() {
		$restricted_taxonomies = UM()->options()->get( 'restricted_access_taxonomy_metabox' );

		$taxonomy_metabox = '';

		if (is_category()) {
			$taxonomy_metabox = 'category';
		}else if(is_tag()) {
			$taxonomy_metabox = 'post_tag';
		}


		if ( ! empty( $restricted_taxonomies ) ) {
		  	
	      	$terms= get_terms( array(
		        'taxonomy' => $taxonomy_metabox,
		        'meta_key' => 'um_content_restriction'
    		));


	      	if ( ! empty( $terms ) ) {
	      		?>
	      		<form name="premiumDropdownTerms" method="POST" action="" id="premiumDropdownTerms" class="dropdownterms">
				  	<select name="premiumDropdown" class="styled" onchange="document.getElementById('premiumDropdownTerms').setAttribute('action', this.value); document.premiumDropdownTerms.submit(); ">
				  		<!-- <option disabled><?php pll_e( 'All premium content' ); ?></option> -->
				  		<?php foreach ( $terms as $term ):
				  			if (has_term_access_restriction($term->term_id)):
				  				$term_name = $term->name;

				  				$menu_title = get_field('premium_dropdown_menu_title', $term);
				  				if ($menu_title) {
				  					$term_name = $menu_title;
				  				}
				  		?>
					    	<option data-tid="<?php echo $term->term_id; ?>" value="<?php echo get_term_link($term); ?>" <?php echo (get_queried_object()->term_id == $term->term_id ? 'selected' : ''); ?>><?php echo $term_name; ?></option>
						<?php endif; endforeach; ?>
				  	</select>
				</form>
				<?php
			}

      	}
	}
}

if ( ! function_exists( 'eodhistoricaldata' ) ) {
	function eodhistoricaldata($post_id) {
		$eod = get_field('eodhistoricaldata', $post_id);
    	if ($eod) {
    		return '<div class="entry-eod">'.do_shortcode($eod).'</div>';
    	}
	}
}

if ( ! function_exists( 'show_the_child_category' ) ) {
	function show_the_child_category($categories) {
		if ( ! empty( $categories ) ): 
	?>
		<div class="entry-terms">
		  <?php
		    foreach( $categories as $key => $category ) {
		      //if($category->parent !== 0 ):
		        $icon = get_field('icon', $category);
		      ?>
		      <a href="<?php echo esc_url( get_category_link($category->term_id) ); ?>">
		        <?php if( !empty( $icon ) ): ?>
		        <img src="<?php echo esc_url($icon); ?>" />
		        <?php endif; ?>
		        <span><?php echo esc_html( $category->name ) ?></span>
		      </a>
		      <?php
		      //endif;
		    }
		  ?>
		</div>
		<?php endif;
	}
}
<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<footer class="site-footer dynamic-footer elementor-section elementor-top-section elementor-element elementor-section-boxed elementor-section-height-default elementor-section-height-default">
	<div class="footer-inner">
    <?php 
      if ( is_active_sidebar( 'sidebar-footer-1' ) ) {
        dynamic_sidebar( 'sidebar-footer-1' );
      }   
    ?>
	</div>
</footer>
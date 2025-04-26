<?php
/**
 * The template for displaying sidebar.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * This file is here to avoid the Deprecated Message for sidebar by wp-includes/theme-compat/sidebar.php.
 */
if ( is_active_sidebar( 'blog-sidebar' ) ) {
  dynamic_sidebar( 'blog-sidebar' );
} 
<?php



class Walker_Category_Custom extends Walker_Category {

  public $custom_simple_rss_options;

  function __construct(){
    $this->$custom_simple_rss_options = 20;
    
    if (get_option('custom_simple_rss_options')) {
      $this->$custom_simple_rss_options = get_option('custom_simple_rss_options')['csrp_posts_per_page'];
    }
  }
  function start_lvl(&$output, $depth=0, $args=array()) {

    $indent  = str_repeat( "\t", $depth );
    $output .= "$indent<ul class='children'>\n";
  }
   
  function end_lvl(&$output, $depth=0, $args=array()) {
    $output .= "</ul>\n";
  }
   
  function start_el(&$output, $item, $depth=0, $args=array(),$current_object_id = 0) {
    $output.= '<li class="list-item-wrapper"><div class="list-item-inner">'.esc_attr($item->name).'<a href="'.home_url().'?call_custom_simple_rss=1&csrp_posts_per_page='.$this->$custom_simple_rss_options.'&csrp_cat='.$item->term_id.'" target="_blank"><img src="'.get_stylesheet_directory_uri().'/assets/images/rss.svg" /></a></div>';
  }
   
  function end_el(&$output, $item, $depth=0, $args=array()) {
    $output .= "</li>\n";
  }
}
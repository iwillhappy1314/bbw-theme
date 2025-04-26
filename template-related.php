<?php
/*
Template Name: Custom YARPP Related Posts
*/

get_header();

if (function_exists('yarpp_related')) {
    yarpp_related(
        [
            'post_type'                   => ['stock', 'post'],
            'show_pass_post'              => false,
            'show_sticky_posts'           => false,
            'past_only'                   => false,
            'exclude'                     => [],
            'recent'                      => false,
            'weight'                      => [
                'body'  => 10,
                'title' => 1000,
                'tax'   => [
                    'post_tag' => 1,
                ],
            ],
            'require_tax'                 => [
                'post_tag' => 1,
            ],
            'threshold'                   => 5,
            'template'                    => 'yarpp-template-archive',
            'limit'                       => 15,
            'order'                       => 'score DESC',
            'promote_yarpp'               => false,
            'generate_missing_thumbnails' => true,
            'extra_css_class'             => 'class_1 class_2',
        ],
        $_GET['rid'],
        true
    );
}

get_footer();
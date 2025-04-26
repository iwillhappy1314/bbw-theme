<?php

if (function_exists('yarpp_related')) {
    yarpp_related(
        [
            'post_type'                   => ['post'],
            'post_status'                 => ['publish'],
            'show_pass_post'              => true,
            'show_sticky_posts'           => false,
            'past_only'                   => false,
            'exclude'                     => $args['exclude'],
            'recent'                      => '12 month',
            'weight'                      => [
                'title' => 3,
                'body'  => 2,
                'tax'   => [
                    'post_tag' => 1,
                ],
            ],
            'require_tax'                 => [
                'post_tag' => 1,
            ],
            'threshold'                   => 3,
            'template'                    => 'yarpp-template-card',
            'limit'                       => 4,
            'order'                       => 'score DESC',
            'promote_yarpp'               => false,
            'generate_missing_thumbnails' => true,
            'extra_css_class'             => 'class_1 class_2',
        ],
        // $args[ 'company_id' ],
        // true
    );
}

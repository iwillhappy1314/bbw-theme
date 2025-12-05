<?php
/*
Template Name: All Stories Page
*/

get_header();

$stock_code = $_GET['stock_code'] ?? null;

if(!$stock_code){
    wp_die('You can not access this page.');
}

// 获取与该股票相关的文章
$args = [
    'post_type'      => 'post',
    'posts_per_page' => 10,
    'ignore_sticky_posts' => 1,
    'meta_query'     => [
        [
            'key'   => 'eodhistoricaldata',
            'value' => $stock_code,
            'compare' => "LIKE",
        ]
    ]
];

$story_query = new WP_Query($args);
$stock_related_post_id = wp_list_pluck($story_query->posts, 'ID');

?>

    <div class="container text-center border-0 border-b-[3px] border-solid border-[#5d853a] my-8">
        <div class="text-center text-3xl pb-8 font-medium">Stories About <?= $stock_code; ?></div>
    </div>

    <main id="content" class="site-main flex page type-page status-publish hentry" role="main">
        <div class="page-content-left mt-8">

            <?php if ( $story_query->have_posts() ) : ?>
                <div class="grid gap-6">
                    <?php while ($story_query->have_posts()) : $story_query->the_post(); ?>
                        <?php get_template_part('templates/content', 'media2') ?>
                    <?php endwhile; ?>
                </div>

                <?php wp_link_pages(); ?>
            <?php else : ?>
                <p>No related photos.</p>
            <?php endif; ?>
        </div>

        <div class="page-content-right sidebar right-sidebar">
            <?php get_sidebar(); ?>
        </div>
    </main>

<?php

get_footer();
<?php
/*
YARPP Template: Card
Description: This template returns the related posts as thumbnails in an ordered list. Requires a theme which supports post thumbnails.
Author: YARPP Team
*/

$company = get_post($_GET['rid']);
?>

<div class="container text-center border-0 border-b-[3px] border-solid border-[#5d853a] my-8">
    <div class="text-center text-3xl pb-8 font-medium">Relate Posts About <?= $company->post_title; ?></div>
</div>

<main id="content" class="site-main flex page type-page status-publish hentry" role="main">
    <div class="page-content-left mt-8">

        <?php if ( have_posts() ) : ?>
            <div class="grid gap-6">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('templates/content', 'media2') ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p>No related photos.</p>
        <?php endif; ?>
    </div>

    <div class="page-content-right sidebar right-sidebar">
        <?php get_sidebar(); ?>
    </div>
</main>
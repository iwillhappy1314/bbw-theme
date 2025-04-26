<?php

/**
 * The template for displaying archive pages.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$taxonomy = get_queried_object();
?>

<?php get_header(); ?>

<main id="content" class="site-main">

    <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
        <header class="page-header mt-6">
            <?php
            the_archive_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header>
    <?php endif; ?>

    <?php if ($taxonomy->parent == 0) : ?>
        <?php $children = get_terms('industry', array('parent' => $taxonomy->term_id)); ?>
        <?php if ($children) : ?>
            <div class="mt-12">
                <ul>
                    <?php foreach ($children as $child) : ?>
                        <li><a href="<?php echo esc_url(get_term_link($child)); ?>"><?php echo esc_html($child->name); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="mt-12">
        <?php
        while (have_posts()) {
            the_post();
            $post_link = get_permalink();
        ?>
            <?php get_template_part('templates/content', 'company') ?>
        <?php } ?>
    </div>

    <?php wp_link_pages(); ?>

    <?php
    global $wp_query;
    if ($wp_query->max_num_pages > 1) :
    ?>
        <nav class="pagination">
            <?php /* Translators: HTML arrow */ ?>
            <div class="nav-previous"><?php next_posts_link(sprintf(__('%s older', 'hello-elementor'), '<span class="meta-nav">&larr;</span>')); ?></div>
            <?php /* Translators: HTML arrow */ ?>
            <div class="nav-next"><?php previous_posts_link(sprintf(__('newer %s', 'hello-elementor'), '<span class="meta-nav">&rarr;</span>')); ?></div>
        </nav>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
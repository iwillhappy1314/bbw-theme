<?php

/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elisi
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="rs-entry">
        <?php if (has_post_thumbnail()) { ?>
            <div class="aspect-w-5 aspect-h-3 rs-entry__image rounded-xl overflow-hidden">
                <a href="<?php the_permalink(); ?>">
                    <?= get_the_post_thumbnail(get_the_id(), 'index-thumbnail', ['class' => 'object-cover']); ?>
                </a>
            </div>
        <?php } ?>

        <div class="rs-entry__body mt-2">
            <h3 class="text-lg">
                <a class="inline-block text-lg leading-tight" href="<?php the_permalink(); ?>">
                    <?php the_title() ?>
                </a>
            </h3>

            <div class="mt-3 text-gray-600">
                <?php the_time('Y-m-d'); ?>
            </div>
        </div>
    </div>

</article>
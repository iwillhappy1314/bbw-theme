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
    <div class="grid grid-cols-4 gap-6">
        <?php if (has_post_thumbnail()) { ?>
            <div class="col-span-2 lg:col-span-1 aspect-w-5 aspect-h-3 rounded-xl overflow-hidden">
                <a href="<?php the_permalink(); ?>">
                    <?= get_the_post_thumbnail(get_the_id(), 'index-thumbnail', ['class' => 'object-cover']); ?>
                </a>
            </div>
        <?php } ?>
        <div class="col-span-2 lg:col-span-3 leading-tight">
            <h3 class="font-medium">
                <a class="inline-block leading-tight text-lg" href="<?php the_permalink(); ?>">
                    <?php the_title() ?>
                </a>
            </h3>

            <div class="mt-3 text-gray-600">
                <?php if (has_excerpt()) : ?>
                    <?php the_excerpt(); ?>
                <?php else: ?>
                    <?= mb_strimwidth(strip_tags(bbw_get_first_paragraph(get_the_ID())), 0, 160, "…"); ?>
                <?php endif; ?>
            </div>

            <div class="mt-3 text-gray-600">
                <?php the_time('Y-m-d'); ?>
            </div>
        </div>
    </div>
</article>
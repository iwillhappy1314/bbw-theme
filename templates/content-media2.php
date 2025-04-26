<?php

/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elisi
 */

?>


<article id="post-<?php the_ID(); ?>" <?php post_class('border-0 border-b border-solid border-gray-300 pb-8'); ?>>
    <div class="grid grid-cols-3 gap-6">
        <?php if (has_post_thumbnail()) { ?>
            <div class="col-span-1 lg:col-span-1 aspect-w-5 aspect-h-3 rounded-xl overflow-hidden">
                <a href="<?php the_permalink(); ?>">
                    <?= get_the_post_thumbnail(get_the_id(), 'index-thumbnail', ['class' => 'object-cover']); ?>
                </a>
            </div>
        <?php } ?>
        <div class="col-span-2 lg:col-span-2 leading-tight">
            <h3 class="font-medium">
                <a class="inline-block leading-tight text-lg" href="<?php the_permalink(); ?>">
                    <?php the_title() ?>
                </a>
            </h3>

            <div class="mt-3 text-gray-600">
                <?php the_time('Y-m-d'); ?>
            </div>
        </div>
    </div>
</article>
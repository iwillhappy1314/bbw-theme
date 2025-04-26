<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elisi
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('border-0 border-b-[1px] border-solid border-gray-300 mb-8 pb-8'); ?>>
    <div class="grid grid-cols-4 gap-6">
        <?php if (has_post_thumbnail()) { ?>
            <div class="col-span-2 lg:col-span-1 border border-solid border-gray-300 rounded-lg overflow-hidden p-6">
                <div class="aspect-w-5 aspect-h-3">
                    <?= get_the_post_thumbnail(get_the_id(), 'index-thumbnail', ['class' => 'object-contain']); ?>
                </div>
            </div>
        <?php }else{ ?>
            <div class="col-span-2 lg:col-span-1 border border-solid border-gray-300 rounded-lg overflow-hidden p-6">
                <img src="<?=  get_post_meta(get_the_ID(), '_stock_logo_url', true); ?>" alt="">
            </div>
        <?php } ?>
        <div class="col-span-2 lg:col-span-3 leading-tight">
            <h3 class="font-medium">
                <a class="inline-block leading-tight text-2xl" href="<?php the_permalink(); ?>">
                    <?php the_title() ?>
                </a>
            </h3>

            <div class="text-gray-800">
                <?= mb_strimwidth(strip_tags( get_the_excerpt() ), 0, 200, "…"); ?>
            </div>

            <div class="mt-3 text-gray-600">
                <?php the_time('Y-m-d'); ?>
            </div>
        </div>
    </div>
</article>
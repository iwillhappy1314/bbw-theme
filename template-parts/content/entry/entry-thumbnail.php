<?php if ( get_post_thumbnail_id() ):?>
<div class="entry-thumbnail">
  <a href="<?php echo esc_url( get_permalink() ); ?>"  class="entry-thumbnail-inner">
    <?php  echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
    </a>
</div>
<?php endif; ?>
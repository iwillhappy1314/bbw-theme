<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
  <div class="post-inner">
    <div class="entry-content">
    	<div class="entry-meta"><?php hello_posted_on(); ?></div>
      	<h3><a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-title"><?php echo clearNbsp(get_the_title()); ?></a></h3>
      	<?php echo eodhistoricaldata(get_the_ID()); ?>
    </div>
  </div>
</article>
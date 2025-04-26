<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
	<div class="post-inner">

		<?php get_template_part('template-parts/content/entry/entry', 'thumbnail'); ?>
		
		<div class="entry-content">
		    <h3><a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-title"><?php echo clearNbsp(get_the_title()); ?></a></h3>
		    <div class="entry-meta-group">
			    <div class="entry-meta"><?php hello_posted_on(false); ?></div>
		    </div>
		</div>
	</div>
</article>
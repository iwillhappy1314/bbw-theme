<?php
/*
YARPP Template: Card
Description: This template returns the related posts as thumbnails in an ordered list. Requires a theme which supports post thumbnails.
Author: YARPP Team
*/
?>

<?php if ( have_posts() ) : ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('templates/content', 'card') ?>
        <?php endwhile; ?>
    </div>
<?php else : ?>
<p>No related photos.</p>
<?php endif; ?>

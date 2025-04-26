<?php
/**
 * Template Name: PR Newswire News List
 *
 * This template is used for displaying the PR Newswire news list on the frontend
 */

get_header();

// Get the plugin instance
global $pr_newswire_frontend;
if (!isset($pr_newswire_frontend)) {
    $pr_newswire_frontend = new PRNewswireFrontend();
}
?>

    <div class="prn-page-container">
        <div class="prn-content-wrap">
            <?php
            // Render the news list using the plugin's render function
            echo $pr_newswire_frontend->render_news_page();
            ?>
        </div>

        <?php
        // If your theme supports sidebar, uncomment the following line
        // get_sidebar();
        ?>
    </div>

<?php
get_footer();
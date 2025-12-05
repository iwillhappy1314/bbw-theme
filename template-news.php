<?php
/**
 * Template Name: News List
 *
 * This template is used for displaying the PR Newswire news list on the frontend
 */

use BbwStockData\Models\RssModel;

get_header();

$company_id = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : '';
$stock = isset($_GET['stk']) ? sanitize_text_field($_GET['stk']) : '';

$paged = max(1, get_query_var('paged', 1));
$items_per_page = 12;
$offset = ($paged - 1) * $items_per_page;

$query = RssModel::query()
                 ->where('company_id', '=', "$company_id")
                 ->orWhere('title', 'LIKE', "%$stock%");

$total_query = clone $query;

$total_items = $query->count();

$total_pages = ceil($total_items / $items_per_page);

$items = $query->offset($offset)->limit($items_per_page)->get();
?>

    <div class="prn-page-container">
        <div class="prn-content-wrap">
            <div class="container">

                <!-- 新闻列表 -->
                <?php if (empty($items)): ?>
                    <div class="prn-no-results">No news items found</div>
                <?php else: ?>
                    <div class="prn-news-list mt-6">
                        <?php foreach ($items as $item): ?>
                            <article class="prn-news-item">
                                <div class="font-medium text-lg">
                                    <a href="<?php echo esc_url($item->link); ?>" target="_blank">
                                        <?php echo esc_html($item->title); ?>
                                    </a>
                                </div>
                                <div class="text-gray-700 text-sm mt-1">
                                    <?php echo date('F j, Y', strtotime($item->pub_date)); ?>
                                </div>
                                <div class="text-sm text-gray-700 mt-4 mb-4">
                                    <?php echo mb_strimwidth(strip_tags($item->description), 0, 200, '...'); ?>
                                </div>
                                <a href="<?php echo esc_url($item->link); ?>" target="_blank" class="prn-read-more border border-solid border-gray-300 hover:text-white">
                                    Read More
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- 分页 -->
                    <?php if ($total_pages > 1): ?>
                        <div class="prn-pagination mb-8">
                            <?php
                            $big = 999999999;
                            echo paginate_links([
                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format' => '?paged=%#%',
                                'current' => $paged,
                                'total' => $total_pages,
                                'prev_text' => '&laquo; Previous',
                                'next_text' => 'Next &raquo;',
                                'add_args' => array_filter([
                                    'company' => $company_id,
                                    'stk' => $stock
                                ])
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php
        // If your theme supports sidebar, uncomment the following line
        // get_sidebar();
        ?>
    </div>

<?php
get_footer();
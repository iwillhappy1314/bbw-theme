<?php
class PRNewswireFrontend
{
    private int $items_per_page = 12;

    public function __construct()
    {
        // 注册重写规则
        add_action('init', [$this, 'add_rewrite_rules']);

        // 添加查询变量
        add_filter('query_vars', [$this, 'add_query_vars']);

        // 添加模板
        add_filter('template_include', [$this, 'load_news_template']);
    }


    public function add_rewrite_rules(): void
    {
        add_rewrite_rule(
            'prnews/page/([0-9]+)/?$',
            'index.php?prnews=1&prn_page=$matches[1]',
            'top'
        );

        add_rewrite_rule(
            'prnews/?$',
            'index.php?prnews=1',
            'top'
        );

        // 刷新重写规则
        flush_rewrite_rules();
    }

    public function add_query_vars($query_vars): array
    {
        $query_vars[] = 'prnews';
        $query_vars[] = 'prn_page';
        $query_vars[] = 'company';
        $query_vars[] = 'stock';
        return $query_vars;
    }

    public function load_news_template($template): string
    {
        if (get_query_var('prnews')) {
            return get_theme_file_path('template-rss.php');
        }

        return $template;
    }

    public function get_news_items($company = '', $stock = '', $paged = 1): array
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'prn_rss_items';

        // Calculate offset
        $offset = ($paged - 1) * $this->items_per_page;

        // Build search query
        $where_clause = '';
        $search_params = [];

        if (!empty($company) || !empty($stock)) {
            $search_terms = $wpdb->esc_like($stock) . ' ' . $wpdb->esc_like($company);
            $where_clause = "WHERE MATCH(title, description) AGAINST(%s IN BOOLEAN MODE)";
            $search_params[] = $search_terms;
        }

        // Get total items count
        $total_query = "SELECT COUNT(*) FROM $table_name $where_clause";
        $total_items = $wpdb->get_var($wpdb->prepare($total_query, $search_params));

        // Get paginated items
        $items_query = "SELECT * FROM $table_name $where_clause ORDER BY pub_date DESC LIMIT %d OFFSET %d";
        $query_params = array_merge($search_params, [$this->items_per_page, $offset]);
        $items = $wpdb->get_results($wpdb->prepare($items_query, $query_params));

        return [
            'items' => $items,
            'total' => $total_items,
            'pages' => ceil($total_items / $this->items_per_page)
        ];
    }

    public function render_news_page($atts = []): string
    {
        $company = isset($_GET['company']) ? sanitize_text_field($_GET['company']) : '';
        $stock = isset($_GET['stock']) ? sanitize_text_field($_GET['stock']) : '';
        $paged = max(1, get_query_var('prn_page', 1));

        $results = $this->get_news_items($company, $stock, $paged);

        ob_start();
?>
        <div class="container">

            <!-- 新闻列表 -->
            <?php if (empty($results['items'])): ?>
                <div class="prn-no-results">No news items found.</div>
            <?php else: ?>
                <div class="prn-news-list">
                    <?php foreach ($results['items'] as $item): ?>
                        <article class="prn-news-item">
                            <div class="font-medium text-2xl">
                                <a href="<?php echo esc_url($item->link); ?>" target="_blank">
                                    <?php echo esc_html($item->title); ?>
                                </a>
                            </div>
                            <div class="text-gray-700 mt-1">
                                <?php echo date('F j, Y', strtotime($item->pub_date)); ?>
                            </div>
                            <div class="text-base text-gray-700 mt-4 mb-4">
                                <?php echo mb_strimwidth(strip_tags($item->description), 0, 200, '...'); ?>
                            </div>
                            <a href="<?php echo esc_url($item->link); ?>" target="_blank" class="prn-read-more border border-solid border-gray-300 hover:text-white">
                                Read More
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- 分页 -->
                <?php if ($results['pages'] > 1): ?>
                    <div class="prn-pagination mb-8">
                        <?php
                        $big = 999999999;
                        echo paginate_links([
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format' => '?paged=%#%',
                            'current' => $paged,
                            'total' => $results['pages'],
                            'prev_text' => '&laquo; Previous',
                            'next_text' => 'Next &raquo;',
                            'add_args' => array_filter([
                                'company' => $company,
                                'stock' => $stock
                            ])
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
<?php
        return ob_get_clean();
    }
}

// 初始化前端类
new PRNewswireFrontend();

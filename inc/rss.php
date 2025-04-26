<?php

use BbwStockData\Models\RssModel;

class PRNewswireRSS
{
    private string $feed_url = 'https://www.prnewswire.com/rss/news-releases-list.rss';
    private int $cache_duration = 3600; // 缓存时间：1小时

    public function __construct()
    {
        // 激活插件时创建数据表
        $this->create_database_table();

        // 添加定时任务钩子
        add_action('prn_rss_update_cron', [$this, 'update_feed_items']);

        // 注册定时任务
        if ( ! wp_next_scheduled('prn_rss_update_cron')) {
            wp_schedule_event(time(), 'hourly', 'prn_rss_update_cron');
        }

        // 注册短代码
        add_shortcode('pr_newswire_feed', [$this, 'display_feed_shortcode']);

        // 添加管理菜单
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    // 创建数据表
    public function create_database_table(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'prn_rss_items';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            link text NOT NULL,
            description text NOT NULL,
            pub_date datetime NOT NULL,
            guid varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY guid (guid),
            FULLTEXT KEY ft_title_desc (title, description)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // 更新订阅源内容
    public function update_feed_items(): bool
    {
        include_once(ABSPATH . WPINC . '/feed.php');

        $rss = fetch_feed($this->feed_url);

        if (is_wp_error($rss)) {
            return false;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'prn_rss_items';

        $items = $rss->get_items();

        foreach ($items as $item) {
            $wpdb->replace(
                $table_name,
                [
                    'title'       => $item->get_title(),
                    'link'        => $item->get_permalink(),
                    'description' => $item->get_description(),
                    'pub_date'    => date('Y-m-d H:i:s', $item->get_date('U')),
                    'guid'        => $item->get_id(),
                ],
                ['%s', '%s', '%s', '%s', '%s']
            );
        }

        return true;
    }

    // 显示订阅源内容的短代码
    public function display_feed_shortcode($atts): string
    {
        // 分割搜索词
        $stock = $atts['stock'];
        $stock_id = $atts['stock_id'];
        $company = $atts['company'];
        $limit = $atts['limit'];
        $keywords = get_post_meta($stock_id, 'businesswire_keyword', true);

        // 构建并执行查询
        $query = RssModel::query()->where('title', 'LIKE', "%$stock%")
                                  ->orWhere('title', 'LIKE', "%$keywords%")
                                  ->orWhere('description', 'LIKE', "%$company%")
                                  ->orWhere('description', 'LIKE', "%$keywords%")
                                  ->limit($limit);

        $items = $query->get();

        if (empty($items)) {
            return '<p>No news items available.</p>';
        }

        $output = '<div class="prn-feed-container">';

        foreach ($items as $item) {
            $output .= '<div class="prn-feed-item">';
            $output .= '<h3><a class="inline-block text-lg leading-tight" href="' . esc_url($item->link) . '" target="_blank">' .
                       esc_html($item->title) . '</a></h3>';

            if ($atts[ 'show_desc' ]) {
                $output .= '<div class="prn-feed-desc">' . mb_strimwidth(strip_tags(wp_kses_post($item->description)), 0, 220, "…") . '</div>';
            }

            $output .= '<div class="prn-feed-date">' .
                       date('F j, Y', strtotime($item->pub_date)) . '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';

        // 添加基本样式
        $output .= '<style>
            .prn-feed-item { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
            .prn-feed-item h3 { margin-bottom: 10px; }
            .prn-feed-desc { margin: 10px 0; }
            .prn-feed-date { color: #666; font-size: 0.9em; }
        </style>';

        return $output;
    }

    // 添加管理页面
    public function add_admin_menu(): void
    {
        add_options_page(
            'PR Newswire RSS',
            'PR Newswire',
            'manage_options',
            'pr-newswire-rss',
            [$this, 'admin_page'],
            'dashicons-rss'
        );
    }

    // 管理页面内容
    public function admin_page(): void
    {
        // 手动更新按钮处理
        if (isset($_POST[ 'update_feed' ]) && check_admin_referer('update_prn_feed')) {
            if ($this->update_feed_items()) {
                echo '<div class="notice notice-success"><p>Feed updated successfully!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Failed to update feed.</p></div>';
            }
        }

        // 管理页面HTML
        ?>
        <div class="wrap">
            <h1>PR Newswire RSS Feed Manager</h1>

            <form method="post" action="">
                <?php wp_nonce_field('update_prn_feed'); ?>
                <p>Click the button below to manually update the feed:</p>
                <input type="submit" name="update_feed" class="button button-primary" value="Update Feed Now">
            </form>

            <h2>Usage Instructions</h2>
            <p>Use this shortcode to display the feed in your posts or pages:</p>
            <code>[pr_newswire_feed limit="10" show_desc="true"]</code>

            <h3>Parameters:</h3>
            <ul>
                <li><strong>limit</strong>: Number of items to display (default: 10)</li>
                <li><strong>show_desc</strong>: Show description (true/false, default: true)</li>
            </ul>
        </div>
        <?php
    }
}

// 初始化插件
new PRNewswireRSS();
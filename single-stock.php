<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
$stock_id   = get_queried_object_id();
$company_id = get_post_meta($stock_id, 'company', true);

$stock   = get_post($stock_id);
$company = get_post($company_id);

$company_name = $company->post_title;
$stock_code   = $stock->post_title;

$stock_currency = get_post_meta($stock_id, '_stock_currency', true);
$currency = (empty($stock_currency) || $stock_currency === '$') ? 'US$' : $stock_currency;
?>

<?php while (have_posts()) : ?>
    <?php the_post(); ?>

    <main id="content" <?php post_class('site-main'); ?> role="main">
        <div class="layout4 mt-8">
            <div class="grid lg:grid-cols-4 pb-4 relative z-1" style="z-index: 1;">
                <div class="block col-span-3">

                    <div class="flex flex-wrap lg:flex-nowrap items-center gap-4 mb-4 lg:mb-8 o-company">
                        <div class="max-w-24 inline-flex items-center">
                            <?php if (has_post_thumbnail($company_id)) {
                                echo get_the_post_thumbnail($company_id, 'full');
                            } else {
                                echo '<img class="h-[34px]" src="' . get_post_meta($company_id, '_stock_logo_url', true) . '" />';
                            } ?>
                        </div>
                        <h2 class="text-3xl mb-0"><?= $company_name ?> (<?php the_title(); ?>)</h2>

                        <?= do_shortcode('[bbw_subscribe_button]'); ?>
                    </div>

                    <div class="grid gap-6 stock-chart-wrap">
                        <div class="relative z-50 mt-4">
                            <div class="flex flex-wrap lg:flex-nowrap gap-4 items-end o-price">
                                <div class="whitespace-nowrap">
                                    <?= $currency; ?> <span class="text-2xl"><?= get_post_meta($stock_id, '_stock_close', true); ?></span>
                                </div>
                                <div class="text-red-700 text-2xl whitespace-nowrap">
                                    <?= bbw_stock_change_html($stock_id); ?>
                                </div>
                                <div class="text-sm whitespace-nowrap text-gray-600">
                                    as of <?= bbw_format_stock_timestamp($stock_id); ?>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="stock-chart-container" data-type="<?= bbw_stock_is_negative($stock_id) ? 'down' : 'up'; ?>" class="h-20" data-id="<?= $stock_id; ?>"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:flex gap-4 justify-between border-0 border-t border-solid border-gray-300 pt-4 -mt-4">
                        <div class="block">
                            <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('Previous Close', 'wprs'); ?></div>
                            <div class="text-sm"><span class="text-xs"><?= $currency; ?></span> <?= get_post_meta($stock_id, '_stock_previous_close', true); ?></div>
                        </div>

                        <div class="block">
                            <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('Market Cap.', 'wprs'); ?></div>
                            <div class="text-sm"><span class="text-xs"><?= $currency; ?></span> <?= bbw_format_market_cap(get_post_meta($stock_id, '_stock_market_cap', true)); ?></div>
                        </div>

                        <div class="block">
                            <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('Volume / 30 day Avg.', 'wprs'); ?></div>
                            <div class="text-sm">
                                <?= bbw_format_number(get_post_meta($stock_id, '_stock_volume', true)); ?> /
                                <?= bbw_format_number(get_post_meta($stock_id, '_stock_30day_avg', true)); ?>
                            </div>
                        </div>

                        <div class="block">
                            <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('52 Week High/Low', 'wprs'); ?></div>
                            <div class="text-sm"><?= get_post_meta($stock_id, '_stock_yearly_high', true); ?> / <?= get_post_meta($stock_id, '_stock_yearly_low', true); ?></div>
                        </div>

                        <div class="block">
                            <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('PE Ratio(TTM)', 'wprs'); ?></div>
                            <div class="text-sm"><?= get_post_meta($stock_id, '_stock_pe_ratio', true); ?></div>
                        </div>

                        <div class="block">
                            <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('Dividend Yield', 'wprs'); ?></div>
                            <?php if (get_post_meta($stock_id, '_stock_dividend_yield', true)) : ?>
                                <div class="text-sm">
                                    <?= get_post_meta($stock_id, '_stock_dividend_yield', true); ?>%
                                </div>
                            <?php else : ?>
                                <div class="text-sm">No dividend</div>
                            <?php endif; ?>
                        </div>

                        <?php
                        $stock_secondary    = bbw_get_secondary_listing($stock_id);
                        $currency_secondary = get_post_meta($stock_id, '_stock_currency', true);
                        ?>

                        <?php if ($stock_secondary) : ?>
                            <div class="block">
                                <div class="text-gray-600 text-xs whitespace-nowrap mb-1"><?= __('Secondary Listing', 'wprs'); ?></div>
                                <div class="text-sm">
                                    <a href="<?= get_the_permalink($stock_secondary->ID); ?>">
                                        <div class="inline-flex gap-1">
                                            <div><?= $stock_secondary->post_title; ?></div>
                                            <div><?= get_post_meta($stock_secondary->ID, '_stock_open', true); ?></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="block border-0 border-t lg:border-t-0 lg:border-l border-solid border-gray-300 pt-6 lg:pt-0 lg:pl-4 lg:ml-4 mt-6 lg:mt-0">
                    <?php
                    $sectors = wp_get_post_terms($company_id, 'industry', [
                        'parent' => 0,
                    ]);

                    // 获取子分类（GicSubIndustry）
                    $sub_industries = wp_get_post_terms($company_id, 'industry', [
                        'parent' => ! empty($sectors) ? $sectors[0]->term_id : 0,
                    ]);
                    ?>

                    <div class="border-0 border-l border-solid border-green-600 pl-2">
                        <?php if (! empty($sectors) && ! is_wp_error($sectors)) : ?>
                            <a href="<?= get_term_link($sectors[0]); ?>"><?= $sectors[0]->name ?></a>
                        <?php endif; ?>
                    </div>

                    <div class="border-0 border-l border-solid border-blue-600 pl-2 mt-1">
                        <?php if (! empty($sub_industries) && ! is_wp_error($sub_industries)) : ?>
                            <a href="<?= get_term_link($sub_industries[0]); ?>"><?= $sub_industries[0]->name ?></a>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 text-sm">
                        <div><span class="text-gray-700"><?= __('Primary exchange: ', 'wprs'); ?></span> <?= get_post_meta(get_the_ID(), '_stock_exchange', true); ?></div>
                        <?php if (get_post_meta(get_the_ID(), 'secondary_exchange', true)) : ?>
                            <div><span class="text-gray-700"><?= __('Secondary exchange: ', 'wprs'); ?></span> <?= get_post_meta(get_the_ID(), 'secondary_exchange', true); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <div class="text-gray-700"><?= __('About', 'wprs'); ?></div>

                        <div class="text-sm bbw-links">
                            <?php
                            $url                 = get_post_meta($company_id, '_stock_web_url', true);
                            $company_description = mb_strimwidth(strip_tags($company->post_excerpt), 0, 120, "…");
                            ?>

                            <?= bbw_str_replace_first($company_name, "<a target=_blank href='$url'>$company_name</a>", $company_description); ?>
                            <a href="<?= get_permalink($company_id); ?>">More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 lg:mt-8">
            <?php $company_corporate = wp_get_post_terms($stock_id, 'corporation'); ?>

            <div class="postgrid-heading border-0  border-t-[2px] pt-3 border-solid border-green-900">
                <h2><?= __('Stories', 'wprs'); ?></h2>
                <a target=_blank href="<?= (! is_wp_error($company_corporate) && $company_corporate) ? get_term_link($company_corporate[0]) : '#'; ?>">
                    <?= __('View All', 'wprs'); ?> >>
                </a>
            </div>

            <?php
            // 获取与该股票相关的文章
            $args = [
                'post_type'      => 'post',
                'posts_per_page' => 5,
                'ignore_sticky_posts' => 1,
                'meta_query'     => [
                    [
                        'key'   => 'eodhistoricaldata',
                        'value' => $stock_code,
                        'compare' => "LIKE",
                    ]
                ]
            ];

            $story_query = new WP_Query($args);
            $stock_related_post_id = wp_list_pluck($story_query->posts, 'ID');
            ?>

            <div>
                <?php if ($story_query->have_posts()): ?>
                    <?php $i = 1; ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php while ($story_query->have_posts()) : $story_query->the_post(); ?>
                            <?php if ($i === 1) : ?>
                                <div class="col-span-1 lg:col-span-4 mb-2">
                                    <?php get_template_part('templates/content', 'media') ?>
                                </div>
                            <?php else: ?>
                                <div>
                                    <?php get_template_part('templates/content', 'card') ?>
                                </div>
                            <?php endif; ?>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </div>

                <?php endif ?>
            </div>
        </div>

        <div class="layout4 mt-8">
            <div class="postgrid-heading border-0 border-t-[2px] pt-3 border-solid border-green-900">
                <h2><?= __('Related', 'wprs'); ?></h2>
                <a target=_blank href="<?= home_url('/related-posts/?rid=' . $company_id); ?>">
                    <?= __('View All', 'wprs'); ?> >>
                </a>
            </div>

            <div>
                <?php
                // 1. 通过当前股票的公司获取相关公司
                // 2. 通过相关公司获取相关股票
                // 3. 通过相关股票获取相关文章
                $related_comanies = get_post_meta($company_id, '_related_company', true);
                $sectors = wp_get_post_terms($company_id, 'industry', [
                    'parent' => 0,
                ]);

                // 获取子分类（GicSubIndustry）
                $sub_industries = wp_get_post_terms($company_id, 'industry', [
                    'parent' => ! empty($sectors) ? $sectors[0]->term_id : 0,
                ]);

                // 如果没有相关公司，通过子行业获取相关公司
                if (empty($related_comanies)) {
                    $args = [
                        'post_type'      => 'company',
                        'post__not_in'   => [$company_id],
                        'posts_per_page' => 5,
                        'ignore_sticky_posts' => 1,
                        'tax_query'      => [
                            [
                                'taxonomy' => 'industry',
                                'field'    => 'term_id',
                                'terms'    => $sub_industries[0]->term_id
                            ]
                        ]
                    ];

                    $related_comanies = wp_list_pluck(get_posts($args), 'ID');
                }

                // 如果没有相关公司，通过行业获取相关公司
                if (empty($related_comanies)) {
                    $args = [
                        'post_type'      => 'company',
                        'post__not_in'   => [$company_id],
                        'posts_per_page' => 5,
                        'ignore_sticky_posts' => 1,
                        'tax_query'      => [
                            [
                                'taxonomy' => 'industry',
                                'field'    => 'term_id',
                                'terms'    => $sectors[0]->term_id
                            ]
                        ]
                    ];

                    $related_comanies = wp_list_pluck(get_posts($args), 'ID');
                }

                // 2. 通过相关公司获取相关股票
                $related_stocks = [];

                if (!empty($related_comanies)) {
                    foreach ($related_comanies as $related_company) {
                        $stock = get_posts(
                            [
                                'post_type'      => 'stock',
                                'posts_per_page' => -1,
                                'ignore_sticky_posts' => 1,
                                'meta_query'     => [
                                    [
                                        'key'   => 'company',
                                        'value' => $related_company,
                                    ]
                                ]
                            ]
                        );

                        $related_stocks[] = $stock[0]->ID;
                    }
                }

                // 3. 通过相关股票获取相关文章
                $meta_queries = [
                    'relation' => 'OR'
                ];

                foreach ($related_stocks as $related_stock) {
                    $stock_code = get_post($related_stock)->post_title;
                    $meta_queries[] = [
                        'key'   => 'eodhistoricaldata',
                        'value' => $stock_code,
                        'compare' => "LIKE",
                    ];
                }

                $six_months_ago = date('Y-m-d', strtotime('-6 months'));
                $date_query = array(
                    array(
                        'after' => $six_months_ago,
                        'inclusive' => true,
                    ),
                );

                $args = [
                    'post_type'      => 'post',
                    'post__not_in'   => $stock_related_post_id,
                    'posts_per_page' => 4,
                    'ignore_sticky_posts' => 1,
                    'meta_query'     => $meta_queries
                ];

                $related_query = new WP_Query($args);
                ?>

                <div>
                    <?php if ($related_query->have_posts()): ?>
                        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                <div>
                                    <?php get_template_part('templates/content', 'card') ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="postgrid-wrapper layout4 mt-8">
                <div class="postgrid-heading">
                    <h2><?= __('Press Release', 'wprs'); ?></h2>
                    <a href="<?= home_url('/prnews/?company=' . $company_name . '&stock=' . $stock_code); ?>"><?= __('View All', 'wprs'); ?> >></a>
                </div>

                <div>
                    <?= do_shortcode('[pr_newswire_feed limit="6" show_desc="true" stock_id="' . $stock_id . '" stock="' . $stock_code . '" company="' . $company_name . '"]'); ?>
                </div>
            </div>
    </main>

<?php endwhile; ?>

<?php get_footer();

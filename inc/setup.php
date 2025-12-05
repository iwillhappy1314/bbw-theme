<?php

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('bambooworks-styles', get_theme_file_uri('frontend/dist/styles/main.css'));

    wp_enqueue_script('bambooworks-scripts', get_theme_file_uri('frontend/dist/scripts/main.js'), ['jquery'], '2.0.8');

    $template_path = parse_url(get_theme_file_uri(), PHP_URL_PATH);

    wp_localize_script('bambooworks-scripts', 'wenpriseSettings', [
        'staticPath' => $template_path . '/frontend/static/',
    ]);

    // 先加载依赖
    wp_enqueue_style('bambooworks-stock-chart', get_theme_file_uri('frontend/dist/styles/stock-chart.css'));

    // 加载编译后的组件
    wp_enqueue_script(
        'stock-chart',
        get_theme_file_uri('frontend/dist/scripts/index.js'),  // 编译后的文件路径
        [],
        '1.0.0',
        true
    );
}, 999);


add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('bambooworks-admin', get_theme_file_uri('frontend/dist/styles/admin.css'));
});

add_filter('wprs_tax_args_industry', function ($args) {
    $args['rewrite'] = [
        'slug'         => 'industry',
        'with_front'   => true,
        'hierarchical' => true,
    ];

    return $args;
});


add_action('wprs_type_args_stock', function ($args) {
    $args['yarpp_support'] = true;

    return $args;
});

add_action('wprs_type_args_company', function ($args) {
    $args['yarpp_support'] = true;

    return $args;
});

add_action('init', function () {
    \WenpriseContentTypes\ContentType::register('stock', 'Stock', ['title', 'thumbnail'], true, '', get_theme_file_uri('assets/images/stock.svg'));
    \WenpriseContentTypes\ContentType::register('company', 'Company', ['title', 'thumbnail', 'excerpt', 'editor'], false, '', get_theme_file_uri('assets/images/company.svg'));

    \WenpriseContentTypes\Taxonomy::register('company', 'post', 'Company', true, false);
    \WenpriseContentTypes\Taxonomy::register('industry', 'company', 'Industry', true, true);
    \WenpriseContentTypes\Taxonomy::register('corporation', 'stock', 'Group', true, true);

    register_taxonomy_for_object_type('post_tag', 'stock');
    register_taxonomy_for_object_type('corporation', 'stock');
    register_taxonomy_for_object_type('corporation', 'company');
    register_taxonomy_for_object_type('company', 'stock');
});


add_filter('post_thumbnail_html', function ($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // 使用 get_attached_file 获取文件系统路径
    $file_path = get_attached_file($post_thumbnail_id);

    // 检查是否是SVG
    if ($file_path && preg_match('/\.svg$/i', $file_path)) {
        // 直接从文件系统读取SVG内容
        $svg_content = file_get_contents($file_path);

        if ($svg_content) {
            // 创建 SimpleXML 对象
            $svg = simplexml_load_string($svg_content);

            if ($svg) {
                // 获取 viewBox 属性
                $viewbox = (string)$svg['viewBox'];
                if ($viewbox) {
                    [$x, $y, $width, $height] = explode(' ', $viewbox);
                } else {
                    // 如果没有 viewBox，尝试获取 width 和 height 属性
                    $width  = (string)$svg['width'];
                    $height = (string)$svg['height'];
                }

                // 如果找到尺寸，修改 HTML
                if ($width && $height) {
                    // 移除现有的宽度和高度属性
                    $html = preg_replace('/(width|height)=["\']\d*["\']\s?/', '', $html);

                    // 添加正确的尺寸
                    $html = preg_replace('/<img /', '<img width="' . $width . '" height="' . $height . '" ', $html);
                }
            }
        }
    }

    return $html;
}, 10, 5);


function bbw_extract_targets($string)
{
    $pattern = '/\[eod_live\s+target="([^"]+)"\]/';
    preg_match_all($pattern, $string, $matches);

    return $matches[ 1 ]; // 返回所有匹配的target值数组
}


add_action('set_object_terms', function($object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids) {
    // 只处理文章的分类
    if ($taxonomy === 'company') {
        $new_terms = array_diff($tt_ids, $old_tt_ids);

        $post_stock_shortcode = get_post_meta($object_id, 'eodhistoricaldata' , true);
        $stock_codes = bbw_extract_targets($post_stock_shortcode);

        error_log("文章 {$object_id} 新增了分类: {$post_stock_shortcode}");
        error_log("stock_codes " . print_r($stock_codes, true));

        if (!empty($new_terms)) {
            foreach ($new_terms as $tt_id) {
                $term = get_term_by('term_taxonomy_id', $tt_id);
                error_log("文章 {$object_id} 新增了分类: {$term->name}");

                if(!empty($stock_codes)){
                    foreach ($stock_codes as $stock_code) {
                        // 检查是否已存在相同标题的 stock 文章
                        $existing_post = get_page_by_title($stock_code, OBJECT, 'stock');

                        if ($existing_post) {
                            error_log("Stock文章已存在: {$stock_code} (ID: {$existing_post->ID})");

                            // 如果文章已存在，可以选择更新其 company terms
                            wp_set_object_terms($existing_post->ID, $tt_ids, 'company');
                            error_log("为已存在的文章 {$existing_post->ID} 更新了 company terms");
                        } else {
                            error_log("新增文章: {$stock_code}");
                            $new_post_id = wp_insert_post([
                                'post_type' => 'stock',
                                'post_title' => $stock_code,
                                'post_status' => 'draft',
                            ]);

                            // 为新创建的文章设置相同的 company terms
                            if ($new_post_id && !is_wp_error($new_post_id)) {
                                wp_set_object_terms($new_post_id, $tt_ids, 'company');
                                error_log("为新文章 {$new_post_id} 设置了 company terms");
                            }
                        }
                    }
                }
            }
        }
    }
}, 10, 6);




add_filter('acf/load_field/name=_related_company', function ($field) {
    $terms = get_terms([
        'taxonomy' => 'company',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms)) {
        $choices = [];
        foreach ($terms as $term) {
            $choices[$term->term_id] = $term->name;
        }
        $field['choices'] = $choices;
    }

    return $field;
});


// 添加 Meta Box
add_action('add_meta_boxes', function () {
    add_meta_box(
        'related_company_meta_box',
        '相关公司',
        'render_related_company_meta',
        'post', // 如有自定义 post type，可改为 'product' 等
        'side',
        'default'
    );
});

function render_related_company_meta($post) {
    $selected = get_post_meta($post->ID, '_related_company', true);
    if (!is_array($selected)) $selected = [];

    // 获取所有 company 分类法项
    $terms = get_terms(['taxonomy' => 'company', 'hide_empty' => false]);

    echo '<select id="related_company_select" name="_related_company[]" multiple style="width:100%">';
    foreach ($terms as $term) {
        $selected_attr = in_array($term->term_id, $selected) ? 'selected' : '';
        echo "<option value='{$term->term_id}' $selected_attr>{$term->name}</option>";
    }
    echo '</select>';

    // 加载 Select2（CDN 版）
    echo '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />';
    echo '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';
    echo "<script>jQuery(function($){ $('#related_company_select').select2({ placeholder: '选择相关公司', allowClear: true }); });</script>";
}

// 保存 Meta Box 数据
add_action('save_post', function ($post_id) {
    if (isset($_POST['_related_company']) && is_array($_POST['_related_company'])) {
        $cleaned = array_map('intval', $_POST['_related_company']);
        update_post_meta($post_id, '_related_company', $cleaned);
    } else {
        delete_post_meta($post_id, '_related_company');
    }
});
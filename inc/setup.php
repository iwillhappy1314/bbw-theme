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

    \WenpriseContentTypes\Taxonomy::register('industry', 'company', 'Industry', true, true);
    \WenpriseContentTypes\Taxonomy::register('corporation', 'stock', 'Group', true, true);

    register_taxonomy_for_object_type('post_tag', 'stock');
    register_taxonomy_for_object_type('corporation', 'stock');
    register_taxonomy_for_object_type('corporation', 'company');
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

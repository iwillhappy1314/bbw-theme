<?php

if ( ! function_exists('bambooworks_assets')) {
    /**
     * 获取前端资源
     *
     * @param $path               string 文件名
     * @param $manifest_directory string 文件名
     *
     * @return string 文件路径
     */
    function bambooworks_assets(string $path, string $manifest_directory = 'frontend'): string
    {
        static $manifest;
        static $manifest_path;

        if ( ! $manifest_path) {
            $manifest_path = get_theme_file_path($manifest_directory . '/mix-manifest.json');
        }

        // Bailout if manifest could not be found
        if ( ! file_exists($manifest_path)) {
            return get_theme_file_uri($path);
        }

        if ( ! $manifest) {
            // @codingStandardsIgnoreLine
            $manifest = json_decode(file_get_contents($manifest_path), true);
        }

        // Remove manifest directory from path
        $path = str_replace($manifest_directory, '', $path);
        // Make sure there’s a leading slash
        $path = '/' . ltrim($path, '/');

        // Bailout with default theme path if file could not be found in manifest
        if ( ! array_key_exists($path, $manifest)) {
            return get_theme_file_uri($path);
        }

        // Get file URL from manifest file
        $path = $manifest[ $path ];
        // Make sure there’s no leading slash
        $path = ltrim($path, '/');

        return get_theme_file_uri(trailingslashit($manifest_directory) . $path);
    }
}


function bbw_format_stock_timestamp($post_id)
{
    // 获取时间戳和 GMT 偏移
    $timestamp = get_post_meta($post_id, '_stock_timestamp', true);
    $gmtoffset = get_post_meta($post_id, '_stock_gmtoffset', true);

    if ( ! $timestamp) {
        return '';
    }

    // 创建 DateTime 对象，使用 UTC 时区
    $date = new DateTime();
    $date->setTimestamp($timestamp);
    $date->setTimezone(new DateTimeZone('America/New_York')); // EDT/EST 时区

    // 格式化时间
    // M = 月份简写 (Oct)
    // j = 日期，不补零 (17)
    // g = 12小时制小时，不补零
    // i = 分钟，补零
    // a = am/pm
    // T = 时区缩写 (EDT/EST)
    return $date->format('M j, g:ia T');
}


function bbw_str_replace_first($search, $replace, $subject)
{
    // 基础清理
    $search = trim($search);
    if ($search === '') {
        return $subject;
    }

    // 公司名称后缀映射
    $variations = array(
        'Ltd' => 'Limited',
        'Limited' => 'Ltd',
        'Ltd.' => 'Limited',
        'Limited.' => 'Ltd'
    );

    // 清理并准备搜索字符串
    $search_clean = str_replace(array(',', '.'), '', $search);
    $subject_clean = str_replace(array(',', '.'), '', $subject);

    // 尝试直接匹配
    $pos = stripos($subject_clean, $search_clean);

    // 如果直接匹配失败，尝试替换后缀后匹配
    if ($pos === false) {
        foreach ($variations as $variant => $full) {
            // 创建两个版本的搜索字符串
            $search_with_variant = preg_replace('/\b(Ltd|Limited)(\.)?$/i', $variant, $search_clean);
            $search_with_full = preg_replace('/\b(Ltd|Limited)(\.)?$/i', $full, $search_clean);

            // 尝试两个版本的匹配
            $pos_variant = stripos($subject_clean, $search_with_variant);
            if ($pos_variant !== false) {
                // 在原始字符串中找到对应位置
                $match_length = strlen($search_with_variant);
                return substr_replace($subject, $replace, $pos_variant, $match_length);
            }

            $pos_full = stripos($subject_clean, $search_with_full);
            if ($pos_full !== false) {
                $match_length = strlen($search_with_full);
                return substr_replace($subject, $replace, $pos_full, $match_length);
            }
        }
    }

    // 如果找到了直接匹配
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search_clean));
    }

    return $subject;
}


function bbw_stock_is_negative($post_id){
    $change            = get_post_meta($post_id, '_stock_change', true);
    $change_percentage = get_post_meta($post_id, '_stock_change_percentage', true);

    // 判断涨跌
    return $change < 0 || $change_percentage < 0;
}


function bbw_stock_yearly_is_negative($post_id){
    $change            = get_post_meta($post_id, '_stock_change', true);
    $change_percentage = get_post_meta($post_id, '_stock_change_percentage', true);

    // 判断涨跌
    return $change < 0 || $change_percentage < 0;
}


function bbw_stock_change_html($post_id)
{
    $change            = get_post_meta($post_id, '_stock_change', true);
    $change_percentage = get_post_meta($post_id, '_stock_change_percentage', true);

    // 判断涨跌
    $is_negative = bbw_stock_is_negative($post_id);

    // 获取绝对值并格式化到小数点后2位
    $abs_change     = abs(floatval($change));
    $abs_percentage = abs(floatval($change_percentage));

    // 设置颜色和箭头
    $color_class = !$is_negative ? 'text-green-900' : 'text-red-500';
    $arrow       = !$is_negative ? '<svg focusable="false" width="24" height="24" viewBox="0 0 24 24" class=" NMm5M"><path fill="currentColor" d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"></path></svg>' : '<svg width="24" height="24" viewBox="0 0 24 24" focusable="false" class=" NMm5M"><path fill="currentColor" d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.58-5.59L4 12l8 8 8-8z"></path></svg>';

    // 构建HTML
    return sprintf(
        '<div class="flex items-center font-bold %s">
            <span class="inline-flex items-center">%s%.2f%%</span>
            <span class="mx-1">%s%.2f</span>
        </div>',
        $color_class,
        $arrow,
        $abs_percentage,
        $is_negative ? '-' : '+',
        $abs_change
    );
}



function bbw_format_number($value, $decimals = 1)
{
    // 如果值为空或0，直接返回"0"
    if ($value === null || $value === 0) {
        return "0";
    }

    // 定义单位数组
    $units = ['', 'K', 'M', 'B', 'T'];
    $k = 1000;

    // 计算数值的量级
    $magnitude = floor(log10(abs((float)$value)) / 3);

    // 确保不超过最大单位
    $magnitude = min($magnitude, count($units) - 1);

    // 计算显示的数值
    $val = $value / pow($k, $magnitude);

    // 如果值小于10，保留指定小数位数，否则取整
    if ($val < 10) {
        return number_format($val, $decimals) . $units[$magnitude];
    } else {
        return number_format($val, 0) . $units[$magnitude];
    }
}


function bbw_format_market_cap($market_cap, $decimal_places = 2)
{
    if (empty($market_cap)) {
        return 'N/A';
    }

    $market_cap = floatval($market_cap);

    // 定义单位转换
    $units = [
        'T' => 1e12,
        'B' => 1e9,
        'M' => 1e6,
    ];

    // 找到合适的单位
    foreach ($units as $unit => $value) {
        if ($market_cap >= $value) {
            $formatted = number_format($market_cap / $value, $decimal_places);

            return $formatted . $unit;
        }
    }

    // 如果小于1M，直接显示原始数字
    return number_format($market_cap);
}



function bbw_get_secondary_listing($stock_id)
{
    $company = wp_get_post_terms($stock_id, 'corporation', true);

    if(!$company || is_wp_error($company)) {
        return null;
    }

    $company_id = $company[0]->term_id;

    if ($company_id) {
        $args = [
            'post_type'      => 'stock',
            'posts_per_page' => 1,
            'post__not_in'    => [$stock_id],
            'tax_query'     => [
                [
                    'taxonomy'   => 'corporation',
                    'terms' => $company_id,
                ],
            ],
        ];

        $posts = get_posts($args);

        if ($posts) {
            return $posts[ 0 ];
        }
    }

    return null;
}


function bbw_extract_stock_code($str) {
    $targets = [];
    if (preg_match_all('/target="([^"]+)"/', $str, $matches)) {
        $targets = $matches[1];
    }
    return $targets;
}
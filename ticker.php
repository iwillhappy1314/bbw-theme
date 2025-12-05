<?php
$stock_post = get_page_by_title($target, 'OBJECT', 'stock');
?>

<div class="rs-ticker" style="display: flex; align-items: center; gap: 16px;">
    <span class="eod_ticker<?= $error ? ' error' : '' ?>">
        <a href="<?= get_permalink($stock_post->ID); ?>"><span class="name"><?= $title === false ? $target : $title ?></span></a>

        <span class="close <?= $type ?> eod_t_<?= $key ?>"
              data-target="<?= $target ?>"
              <?= $ndap === '0' || $ndap ? "data-ndap='$ndap'" : '' ?>
            <?= $ndape === '0' || $ndape ? "data-ndape='$ndape'" : '' ?>>
        </span>
        <span class="evolution eod_t_<?= $key ?>_evol"></span>
    </span>

    <?= do_shortcode('[bbw_subscribe_button article_title=' . $target . ']'); ?>
</div>
<?php
/**
 * Template for the MailChimp's field checkbox
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-mailchimp/field.php
 *
 * Used:   Account page, Registration page, shortcodes
 * Call:   function um_mc_field( $data )
 * Parent: fieldset.php
 * Parent: fieldset_ch.php
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( $enabled ) {
    $active = 'active';
    $iclass = "um-icon-android-checkbox-outline";
} else {
    $active = '';
    $iclass = "um-icon-android-checkbox-outline-blank";
}
$namepref = "um-mailchimp[{$wp_list->ID}]";
?>

<div class="um-field um-field-checkbox um-field-checkbox-mailchimp">

    <?php if ( ! empty( $label ) ) { ?>
        <div class="um-field-label">
            <label><?php echo esc_html( $label ); ?></label>
        </div>
    <?php } ?>

    <div class="um-field-area">
        <input type="hidden" name="<?php echo esc_attr( $namepref ); ?>[wp_list_id]" value="<?php echo esc_attr( $wp_list->ID ); ?>" />
        <label class="um-field-checkbox <?php echo esc_attr( $active ); ?>">
            <input type="checkbox" name="<?php echo esc_attr( $namepref ); ?>[enabled]" value="<?php echo esc_attr( $value ); ?>" <?php echo checked( $enabled ); ?>>
            <span class="um-field-checkbox-state"><i class="<?php echo esc_attr( $iclass ); ?>"></i></span>
            <span class="um-field-checkbox-option"><?php echo esc_html( $wp_list->post_title ); ?></span>
        </label>
    </div>

    <?php if ( ! empty( $notice ) ) { ?>
        <div class="um-field-notice"><span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span><?php echo $notice; ?></div>
    <?php } ?>

    <?php if ( ! empty( $error ) ) { ?>
        <div class="um-field-error"><span class="um-field-arrow"><i class="um-faicon-caret-up"></i></span><?php echo $error; ?></div>
    <?php } ?>

</div>
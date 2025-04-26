<?php

if (!function_exists('has_term_access_restriction')) {
	function has_term_access_restriction($term_id) {
		$restriction = get_term_meta( $term_id, 'um_content_restriction', true );

		if ($restriction) {
			if ($restriction['_um_custom_access_settings']) {
				return true;
			}
		}
		return false;
	}
}


if (!function_exists('leaky_is_content_restricted')) {
	function leaky_is_content_restricted() {
		$is_restricted = false;

		if (class_exists('Leaky_Paywall_Restrictions')) {
			$Leaky = new Leaky_Paywall_Restrictions();

			$is_restricted = $Leaky->is_content_restricted();
		}

		return $is_restricted;
	}
}



if (!function_exists('leaky_subscriber_can_view')) {
	function leaky_subscriber_can_view() {
		if (class_exists('Leaky_Paywall_Restrictions')) {
			$Leaky = new Leaky_Paywall_Restrictions();
			return $Leaky->subscriber_can_view();
		}
		return true;
	}
}



function clearNbsp($str) {
    $entities = str_replace('&nbsp;', ' ', htmlentities($str));
    return html_entity_decode($entities);
}
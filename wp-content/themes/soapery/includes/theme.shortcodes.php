<?php
if (!function_exists('soapery_theme_shortcodes_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_theme_shortcodes_setup', 1 );
	function soapery_theme_shortcodes_setup() {
		add_filter('soapery_filter_googlemap_styles', 'soapery_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'soapery_theme_shortcodes_googlemap_styles' ) ) {
	function soapery_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'soapery');
		$list['greyscale']	= esc_html__('Greyscale', 'soapery');
		$list['inverse']	= esc_html__('Inverse', 'soapery');
		return $list;
	}
}
?>
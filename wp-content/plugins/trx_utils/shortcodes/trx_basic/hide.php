<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_hide_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_hide_theme_setup' );
	function soapery_sc_hide_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('soapery_sc_hide')) {	
	function soapery_sc_hide($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	soapery_require_shortcode('trx_hide', 'soapery_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_hide_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_hide_reg_shortcodes');
	function soapery_sc_hide_reg_shortcodes() {
	
		soapery_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'soapery'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'soapery'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'soapery'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'soapery') ),
					"value" => "yes",
					"size" => "small",
					"options" => soapery_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>
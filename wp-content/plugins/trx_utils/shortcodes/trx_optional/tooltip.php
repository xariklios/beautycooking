<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_tooltip_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_tooltip_theme_setup' );
	function soapery_sc_tooltip_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('soapery_sc_tooltip')) {	
	function soapery_sc_tooltip($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	soapery_require_shortcode('trx_tooltip', 'soapery_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_tooltip_reg_shortcodes');
	function soapery_sc_tooltip_reg_shortcodes() {
	
		soapery_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'soapery'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'soapery'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'soapery'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'soapery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => soapery_get_sc_param('id'),
				"class" => soapery_get_sc_param('class'),
				"css" => soapery_get_sc_param('css')
			)
		));
	}
}
?>
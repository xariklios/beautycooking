<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_br_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_br_theme_setup' );
	function soapery_sc_br_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_br_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('soapery_sc_br')) {	
	function soapery_sc_br($atts, $content = null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('soapery_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	soapery_require_shortcode("trx_br", "soapery_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_br_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_br_reg_shortcodes');
	function soapery_sc_br_reg_shortcodes() {
	
		soapery_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'soapery'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'soapery'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'soapery'),
						'left' => esc_html__('Left', 'soapery'),
						'right' => esc_html__('Right', 'soapery'),
						'both' => esc_html__('Both', 'soapery')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_br_reg_shortcodes_vc');
	function soapery_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'soapery'),
			"description" => wp_kses_data( __("Line break or Clear Floating", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'soapery'),
					"description" => wp_kses_data( __("Select clear side (if need)", 'soapery') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'soapery') => 'none',
						esc_html__('Left', 'soapery') => 'left',
						esc_html__('Right', 'soapery') => 'right',
						esc_html__('Both', 'soapery') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends SOAPERY_VC_ShortCodeSingle {}
*/
	}
}
?>
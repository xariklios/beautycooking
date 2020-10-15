<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_gap_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_gap_theme_setup' );
	function soapery_sc_gap_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_gap_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('soapery_sc_gap')) {	
	function soapery_sc_gap($atts, $content = null) {
		if (soapery_in_shortcode_blogger()) return '';
		$output = soapery_gap_start() . do_shortcode($content) . soapery_gap_end();
		return apply_filters('soapery_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	soapery_require_shortcode("trx_gap", "soapery_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_gap_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_gap_reg_shortcodes');
	function soapery_sc_gap_reg_shortcodes() {
	
		soapery_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'soapery'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'soapery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'soapery'),
					"desc" => wp_kses_data( __("Gap inner content", 'soapery') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_gap_reg_shortcodes_vc');
	function soapery_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'soapery'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'soapery') ),
			"category" => esc_html__('Structure', 'soapery'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", 'soapery'),
					"description" => wp_kses_data( __("Gap inner content", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends SOAPERY_VC_ShortCodeCollection {}
	}
}
?>
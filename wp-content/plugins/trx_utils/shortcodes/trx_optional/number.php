<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_number_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_number_theme_setup' );
	function soapery_sc_number_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_number_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('soapery_sc_number')) {	
	function soapery_sc_number($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < soapery_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(soapery_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	soapery_require_shortcode('trx_number', 'soapery_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_number_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_number_reg_shortcodes');
	function soapery_sc_number_reg_shortcodes() {
	
		soapery_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'soapery'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'soapery'),
					"desc" => wp_kses_data( __("Number or any word", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'soapery'),
					"desc" => wp_kses_data( __("Select block alignment", 'soapery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('align')
				),
				"top" => soapery_get_sc_param('top'),
				"bottom" => soapery_get_sc_param('bottom'),
				"left" => soapery_get_sc_param('left'),
				"right" => soapery_get_sc_param('right'),
				"id" => soapery_get_sc_param('id'),
				"class" => soapery_get_sc_param('class'),
				"animation" => soapery_get_sc_param('animation'),
				"css" => soapery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_number_reg_shortcodes_vc');
	function soapery_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'soapery'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'soapery'),
					"description" => wp_kses_data( __("Number or any word to separate", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'soapery'),
					"description" => wp_kses_data( __("Select block alignment", 'soapery') ),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('align')),
					"type" => "dropdown"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('animation'),
				soapery_get_vc_param('css'),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
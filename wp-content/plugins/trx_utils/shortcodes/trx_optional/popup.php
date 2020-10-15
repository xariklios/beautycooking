<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_popup_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_popup_theme_setup' );
	function soapery_sc_popup_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_popup_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('soapery_sc_popup')) {	
	function soapery_sc_popup($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		soapery_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	soapery_require_shortcode('trx_popup', 'soapery_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_popup_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_popup_reg_shortcodes');
	function soapery_sc_popup_reg_shortcodes() {
	
		soapery_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", 'soapery'),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'soapery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Container content", 'soapery'),
					"desc" => wp_kses_data( __("Content for section container", 'soapery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => soapery_get_sc_param('top'),
				"bottom" => soapery_get_sc_param('bottom'),
				"left" => soapery_get_sc_param('left'),
				"right" => soapery_get_sc_param('right'),
				"id" => soapery_get_sc_param('id'),
				"class" => soapery_get_sc_param('class'),
				"css" => soapery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_popup_reg_shortcodes_vc');
	function soapery_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", 'soapery'),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'soapery'),
					"description" => wp_kses_data( __("Content for popup container", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css'),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends SOAPERY_VC_ShortCodeCollection {}
	}
}
?>
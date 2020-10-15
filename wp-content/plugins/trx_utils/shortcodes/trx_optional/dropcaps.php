<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_dropcaps_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_dropcaps_theme_setup' );
	function soapery_sc_dropcaps_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_dropcaps_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('soapery_sc_dropcaps')) {	
	function soapery_sc_dropcaps($atts, $content=null){
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= soapery_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = soapery_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(soapery_substr($content, 0, 1)) . '</span>' . trim(soapery_substr($content, 1))
			. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	soapery_require_shortcode('trx_dropcaps', 'soapery_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_dropcaps_reg_shortcodes');
	function soapery_sc_dropcaps_reg_shortcodes() {
	
		soapery_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'soapery'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'soapery'),
					"desc" => wp_kses_data( __("Dropcaps style", 'soapery') ),
					"value" => "1",
					"type" => "checklist",
					"options" => soapery_get_list_styles(1, 4)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'soapery'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'soapery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => soapery_shortcodes_width(),
				"height" => soapery_shortcodes_height(),
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
if ( !function_exists( 'soapery_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_dropcaps_reg_shortcodes_vc');
	function soapery_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'soapery'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'soapery'),
					"description" => wp_kses_data( __("Dropcaps style", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Paragraph text", 'soapery'),
					"description" => wp_kses_data( __("Paragraph with dropcaps content", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('animation'),
				soapery_get_vc_param('css'),
				soapery_vc_width(),
				soapery_vc_height(),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends SOAPERY_VC_ShortCodeContainer {}
	}
}
?>
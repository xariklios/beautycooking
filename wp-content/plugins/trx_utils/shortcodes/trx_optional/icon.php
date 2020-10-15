<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_icon_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_icon_theme_setup' );
	function soapery_sc_icon_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_icon_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('soapery_sc_icon')) {	
	function soapery_sc_icon($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
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
		$css2 = ($font_weight != '' && !soapery_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(soapery_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || soapery_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !soapery_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('soapery_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	soapery_require_shortcode('trx_icon', 'soapery_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_icon_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_icon_reg_shortcodes');
	function soapery_sc_icon_reg_shortcodes() {
	
		soapery_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'soapery'),
			"desc" => wp_kses_data( __("Insert icon", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'soapery'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'soapery'),
					"desc" => wp_kses_data( __("Icon's color", 'soapery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'soapery'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'soapery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'soapery'),
						'round' => esc_html__('Round', 'soapery'),
						'square' => esc_html__('Square', 'soapery')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'soapery'),
					"desc" => wp_kses_data( __("Icon's background color", 'soapery') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'soapery'),
					"desc" => wp_kses_data( __("Icon's font size", 'soapery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'soapery'),
					"desc" => wp_kses_data( __("Icon font weight", 'soapery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'soapery'),
						'300' => esc_html__('Light (300)', 'soapery'),
						'400' => esc_html__('Normal (400)', 'soapery'),
						'700' => esc_html__('Bold (700)', 'soapery')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'soapery'),
					"desc" => wp_kses_data( __("Icon text alignment", 'soapery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'soapery'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'soapery') ),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'soapery_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_icon_reg_shortcodes_vc');
	function soapery_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'soapery'),
			"description" => wp_kses_data( __("Insert the icon", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'soapery'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'soapery'),
					"description" => wp_kses_data( __("Icon's color", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'soapery'),
					"description" => wp_kses_data( __("Background color for the icon", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'soapery'),
					"description" => wp_kses_data( __("Shape of the icon background", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'soapery') => 'none',
						esc_html__('Round', 'soapery') => 'round',
						esc_html__('Square', 'soapery') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'soapery'),
					"description" => wp_kses_data( __("Icon's font size", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'soapery'),
					"description" => wp_kses_data( __("Icon's font weight", 'soapery') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'soapery') => 'inherit',
						esc_html__('Thin (100)', 'soapery') => '100',
						esc_html__('Light (300)', 'soapery') => '300',
						esc_html__('Normal (400)', 'soapery') => '400',
						esc_html__('Bold (700)', 'soapery') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'soapery'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'soapery'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css'),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_highlight_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_highlight_theme_setup' );
	function soapery_sc_highlight_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_highlight_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('soapery_sc_highlight')) {	
	function soapery_sc_highlight($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(soapery_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	soapery_require_shortcode('trx_highlight', 'soapery_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_highlight_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_highlight_reg_shortcodes');
	function soapery_sc_highlight_reg_shortcodes() {
	
		soapery_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'soapery'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'soapery'),
					"desc" => wp_kses_data( __("Highlight type", 'soapery') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'soapery'),
						1 => esc_html__('Type 1', 'soapery'),
						2 => esc_html__('Type 2', 'soapery'),
						3 => esc_html__('Type 3', 'soapery')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'soapery'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'soapery'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'soapery'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'soapery'),
					"desc" => wp_kses_data( __("Content for highlight", 'soapery') ),
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


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_highlight_reg_shortcodes_vc');
	function soapery_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'soapery'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'soapery'),
					"description" => wp_kses_data( __("Highlight type", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'soapery') => 0,
							esc_html__('Type 1', 'soapery') => 1,
							esc_html__('Type 2', 'soapery') => 2,
							esc_html__('Type 3', 'soapery') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'soapery'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'soapery'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'soapery'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'soapery'),
					"description" => wp_kses_data( __("Content for highlight", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
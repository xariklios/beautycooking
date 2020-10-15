<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_infobox_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_infobox_theme_setup' );
	function soapery_sc_infobox_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_infobox_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('soapery_sc_infobox')) {	
	function soapery_sc_infobox($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
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
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (soapery_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !soapery_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	soapery_require_shortcode('trx_infobox', 'soapery_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_infobox_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_infobox_reg_shortcodes');
	function soapery_sc_infobox_reg_shortcodes() {
	
		soapery_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'soapery'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'soapery'),
					"desc" => wp_kses_data( __("Infobox style", 'soapery') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'soapery'),
						'info' => esc_html__('Info', 'soapery'),
						'success' => esc_html__('Success', 'soapery'),
						'error' => esc_html__('Error', 'soapery')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'soapery'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'soapery') ),
					"value" => "no",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'soapery'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'soapery'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'soapery'),
					"desc" => wp_kses_data( __("Content for infobox", 'soapery') ),
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
				"animation" => soapery_get_sc_param('animation'),
				"css" => soapery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_infobox_reg_shortcodes_vc');
	function soapery_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'soapery'),
			"description" => wp_kses_data( __("Box with info or error message", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'soapery'),
					"description" => wp_kses_data( __("Infobox style", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'soapery') => 'regular',
							esc_html__('Info', 'soapery') => 'info',
							esc_html__('Success', 'soapery') => 'success',
							esc_html__('Error', 'soapery') => 'error',
							esc_html__('Result', 'soapery') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'soapery'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'soapery') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'soapery'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'soapery'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Message text", 'soapery'),
					"description" => wp_kses_data( __("Message for the infobox", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('animation'),
				soapery_get_vc_param('css'),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends SOAPERY_VC_ShortCodeContainer {}
	}
}
?>
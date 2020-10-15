<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_button_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_button_theme_setup' );
	function soapery_sc_button_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_button_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('soapery_sc_button')) {	
	function soapery_sc_button($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
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
		$css .= soapery_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (soapery_param_is_on($popup)) soapery_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (soapery_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	soapery_require_shortcode('trx_button', 'soapery_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_button_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_button_reg_shortcodes');
	function soapery_sc_button_reg_shortcodes() {
	
		soapery_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'soapery'),
			"desc" => wp_kses_data( __("Button with link", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'soapery'),
					"desc" => wp_kses_data( __("Button caption", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'soapery'),
					"desc" => wp_kses_data( __("Select button's shape", 'soapery') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'soapery'),
						'round' => esc_html__('Round', 'soapery')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'soapery'),
					"desc" => wp_kses_data( __("Select button's style", 'soapery') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'soapery'),
						'border' => esc_html__('Border', 'soapery'),
						'inverse' => esc_html__('Inverse', 'soapery')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'soapery'),
					"desc" => wp_kses_data( __("Select button's size", 'soapery') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'soapery'),
						'medium' => esc_html__('Medium', 'soapery'),
						'large' => esc_html__('Large', 'soapery')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'soapery'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'soapery') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'soapery'),
					"desc" => wp_kses_data( __("Any color for button's background", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'soapery'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'soapery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'soapery'),
					"desc" => wp_kses_data( __("URL for link on button click", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'soapery'),
					"desc" => wp_kses_data( __("Target for link on button click", 'soapery') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'soapery'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'soapery') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'soapery'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'soapery') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'soapery_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_button_reg_shortcodes_vc');
	function soapery_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'soapery'),
			"description" => wp_kses_data( __("Button with link", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'soapery'),
					"description" => wp_kses_data( __("Button caption", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'soapery'),
					"description" => wp_kses_data( __("Select button's shape", 'soapery') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'soapery') => 'square',
						esc_html__('Round', 'soapery') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'soapery'),
					"description" => wp_kses_data( __("Select button's style", 'soapery') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'soapery') => 'filled',
						esc_html__('Border', 'soapery') => 'border',
						esc_html__('Inverse', 'soapery') => 'inverse'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'soapery'),
					"description" => wp_kses_data( __("Select button's size", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'soapery') => 'small',
						esc_html__('Medium', 'soapery') => 'medium',
						esc_html__('Large', 'soapery') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'soapery'),
					"description" => wp_kses_data( __("Any color for button's caption", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'soapery'),
					"description" => wp_kses_data( __("Any color for button's background", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'soapery'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'soapery') ),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'soapery'),
					"description" => wp_kses_data( __("URL for the link on button click", 'soapery') ),
					"class" => "",
					"group" => esc_html__('Link', 'soapery'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'soapery'),
					"description" => wp_kses_data( __("Target for the link on button click", 'soapery') ),
					"class" => "",
					"group" => esc_html__('Link', 'soapery'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'soapery'),
					"description" => wp_kses_data( __("Open link target in popup window", 'soapery') ),
					"class" => "",
					"group" => esc_html__('Link', 'soapery'),
					"value" => array(esc_html__('Open in popup', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'soapery'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'soapery') ),
					"class" => "",
					"group" => esc_html__('Link', 'soapery'),
					"value" => "",
					"type" => "textfield"
				),
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
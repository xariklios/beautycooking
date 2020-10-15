<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_list_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_list_theme_setup' );
	function soapery_sc_list_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_list_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('soapery_sc_list')) {	
	function soapery_sc_list($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
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
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		soapery_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || soapery_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	soapery_require_shortcode('trx_list', 'soapery_sc_list');
}


if (!function_exists('soapery_sc_list_item')) {	
	function soapery_sc_list_item($atts, $content=null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		soapery_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || soapery_param_is_inherit($icon)) $icon = soapery_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || soapery_param_is_inherit($icon_color)) $icon_color = soapery_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (soapery_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (soapery_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (soapery_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	soapery_require_shortcode('trx_list_item', 'soapery_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_list_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_list_reg_shortcodes');
	function soapery_sc_list_reg_shortcodes() {
	
		soapery_sc_map("trx_list", array(
			"title" => esc_html__("List", 'soapery'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'soapery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'soapery'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'soapery') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => soapery_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'soapery'),
					"desc" => wp_kses_data( __("List items color", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'soapery'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'soapery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'soapery'),
					"desc" => wp_kses_data( __("List icons color", 'soapery') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => soapery_get_sc_param('top'),
				"bottom" => soapery_get_sc_param('bottom'),
				"left" => soapery_get_sc_param('left'),
				"right" => soapery_get_sc_param('right'),
				"id" => soapery_get_sc_param('id'),
				"class" => soapery_get_sc_param('class'),
				"animation" => soapery_get_sc_param('animation'),
				"css" => soapery_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'soapery'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'soapery') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'soapery'),
						"desc" => wp_kses_data( __("Current list item content", 'soapery') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'soapery'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'soapery') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'soapery'),
						"desc" => wp_kses_data( __("Text color for this item", 'soapery') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'soapery'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'soapery') ),
						"value" => "",
						"type" => "icons",
						"options" => soapery_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'soapery'),
						"desc" => wp_kses_data( __("Icon color for this item", 'soapery') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'soapery'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'soapery') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'soapery'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'soapery') ),
						"value" => "",
						"type" => "text"
					),
					"id" => soapery_get_sc_param('id'),
					"class" => soapery_get_sc_param('class'),
					"css" => soapery_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_list_reg_shortcodes_vc');
	function soapery_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'soapery'),
			"description" => wp_kses_data( __("List items with specific bullets", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'soapery'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'soapery') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(soapery_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'soapery'),
					"description" => wp_kses_data( __("List items color", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'soapery'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'soapery'),
					"description" => wp_kses_data( __("List icons color", 'soapery') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('animation'),
				soapery_get_vc_param('css'),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'soapery'),
			"description" => wp_kses_data( __("List item with specific bullet", 'soapery') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'soapery'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'soapery'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'soapery'),
					"description" => wp_kses_data( __("Link target for the current list item", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'soapery'),
					"description" => wp_kses_data( __("Text color for this item", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'soapery'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'soapery'),
					"description" => wp_kses_data( __("Icon color for this item", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("List item text", 'soapery'),
					"description" => wp_kses_data( __("Current list item content", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends SOAPERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends SOAPERY_VC_ShortCodeContainer {}
	}
}
?>
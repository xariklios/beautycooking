<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_accordion_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_accordion_theme_setup' );
	function soapery_sc_accordion_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_accordion_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_accordion_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_accordion counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('soapery_sc_accordion')) {	
	function soapery_sc_accordion($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-down",
			"icon_opened" => "icon-right",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$initial = max(0, (int) $initial);
		soapery_storage_set('sc_accordion_data', array(
			'counter' => 0,
            'show_counter' => soapery_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || soapery_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || soapery_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (soapery_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	soapery_require_shortcode('trx_accordion', 'soapery_sc_accordion');
}


if (!function_exists('soapery_sc_accordion_item')) {	
	function soapery_sc_accordion_item($atts, $content=null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		soapery_storage_inc_array('sc_accordion_data', 'counter');
		if (empty($icon_closed) || soapery_param_is_inherit($icon_closed)) $icon_closed = soapery_storage_get_array('sc_accordion_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || soapery_param_is_inherit($icon_opened)) $icon_opened = soapery_storage_get_array('sc_accordion_data', 'icon_opened', '', "icon-minus");
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. (soapery_storage_get_array('sc_accordion_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
				. (soapery_storage_get_array('sc_accordion_data', 'counter') == 1 ? ' first' : '') 
				. '">'
				. '<h5 class="sc_accordion_title">'
				. (!soapery_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!soapery_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. (soapery_storage_get_array('sc_accordion_data', 'show_counter') ? '<span class="sc_items_counter">'.(soapery_storage_get_array('sc_accordion_data', 'counter')).'</span>' : '')
				. ($title)
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	soapery_require_shortcode('trx_accordion_item', 'soapery_sc_accordion_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_accordion_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_accordion_reg_shortcodes');
	function soapery_sc_accordion_reg_shortcodes() {
	
		soapery_sc_map("trx_accordion", array(
			"title" => esc_html__("Accordion", 'soapery'),
			"desc" => wp_kses_data( __("Accordion items", 'soapery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'soapery'),
					"desc" => wp_kses_data( __("Display counter before each accordion title", 'soapery') ),
					"value" => "off",
					"type" => "switch",
					"options" => soapery_get_sc_param('on_off')
				),
				"initial" => array(
					"title" => esc_html__("Initially opened item", 'soapery'),
					"desc" => wp_kses_data( __("Number of initially opened item", 'soapery') ),
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the closed accordion item from Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the opened accordion item from Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
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
				"name" => "trx_accordion_item",
				"title" => esc_html__("Item", 'soapery'),
				"desc" => wp_kses_data( __("Accordion item", 'soapery') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Accordion item title", 'soapery'),
						"desc" => wp_kses_data( __("Title for current accordion item", 'soapery') ),
						"value" => "",
						"type" => "text"
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'soapery'),
						"desc" => wp_kses_data( __('Select icon for the closed accordion item from Fontello icons set',  'soapery') ),
						"value" => "",
						"type" => "icons",
						"options" => soapery_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'soapery'),
						"desc" => wp_kses_data( __('Select icon for the opened accordion item from Fontello icons set',  'soapery') ),
						"value" => "",
						"type" => "icons",
						"options" => soapery_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Accordion item content", 'soapery'),
						"desc" => wp_kses_data( __("Current accordion item content", 'soapery') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
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
if ( !function_exists( 'soapery_sc_accordion_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_accordion_reg_shortcodes_vc');
	function soapery_sc_accordion_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_accordion",
			"name" => esc_html__("Accordion", 'soapery'),
			"description" => wp_kses_data( __("Accordion items", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_accordion',
			"class" => "trx_sc_collection trx_sc_accordion",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'soapery'),
					"description" => wp_kses_data( __("Display counter before each accordion title", 'soapery') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened item", 'soapery'),
					"description" => wp_kses_data( __("Number of initially opened item", 'soapery') ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the closed accordion item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the opened accordion item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
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
			),
			'default_content' => '
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'soapery' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'soapery' ) . '"][/trx_accordion_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'soapery').'">'.esc_html__("Add item", 'soapery').'</button>
				</div>
			',
			'js_view' => 'VcTrxAccordionView'
		) );
		
		
		vc_map( array(
			"base" => "trx_accordion_item",
			"name" => esc_html__("Accordion item", 'soapery'),
			"description" => wp_kses_data( __("Inner accordion item", 'soapery') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_accordion_item',
			"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_accordion'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'soapery'),
					"description" => wp_kses_data( __("Title for current accordion item", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the closed accordion item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the opened accordion item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css')
			),
		  'js_view' => 'VcTrxAccordionTabView'
		) );

		class WPBakeryShortCode_Trx_Accordion extends SOAPERY_VC_ShortCodeAccordion {}
		class WPBakeryShortCode_Trx_Accordion_Item extends SOAPERY_VC_ShortCodeAccordionItem {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_toggles_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_toggles_theme_setup' );
	function soapery_sc_toggles_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_toggles_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_toggles_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('soapery_sc_toggles')) {	
	function soapery_sc_toggles($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
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
		soapery_storage_set('sc_toggle_data', array(
			'counter' => 0,
            'show_counter' => soapery_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || soapery_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || soapery_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (soapery_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	soapery_require_shortcode('trx_toggles', 'soapery_sc_toggles');
}


if (!function_exists('soapery_sc_toggles_item')) {	
	function soapery_sc_toggles_item($atts, $content=null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		soapery_storage_inc_array('sc_toggle_data', 'counter');
		if (empty($icon_closed) || soapery_param_is_inherit($icon_closed)) $icon_closed = soapery_storage_get_array('sc_toggles_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || soapery_param_is_inherit($icon_opened)) $icon_opened = soapery_storage_get_array('sc_toggles_data', 'icon_opened', '', "icon-minus");
		$css .= soapery_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(soapery_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (soapery_storage_get_array('sc_toggle_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (soapery_storage_get_array('sc_toggle_data', 'counter') == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(soapery_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!soapery_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!soapery_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. (soapery_storage_get_array('sc_toggle_data', 'show_counter') ? '<span class="sc_items_counter">'.(soapery_storage_get_array('sc_toggle_data', 'counter')).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	soapery_require_shortcode('trx_toggles_item', 'soapery_sc_toggles_item');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_toggles_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_toggles_reg_shortcodes');
	function soapery_sc_toggles_reg_shortcodes() {
	
		soapery_sc_map("trx_toggles", array(
			"title" => esc_html__("Toggles", 'soapery'),
			"desc" => wp_kses_data( __("Toggles items", 'soapery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'soapery'),
					"desc" => wp_kses_data( __("Display counter before each toggles title", 'soapery') ),
					"value" => "off",
					"type" => "switch",
					"options" => soapery_get_sc_param('on_off')
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the closed toggles item from Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the opened toggles item from Fontello icons set',  'soapery') ),
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
				"name" => "trx_toggles_item",
				"title" => esc_html__("Toggles item", 'soapery'),
				"desc" => wp_kses_data( __("Toggles item", 'soapery') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Toggles item title", 'soapery'),
						"desc" => wp_kses_data( __("Title for current toggles item", 'soapery') ),
						"value" => "",
						"type" => "text"
					),
					"open" => array(
						"title" => esc_html__("Open on show", 'soapery'),
						"desc" => wp_kses_data( __("Open current toggles item on show", 'soapery') ),
						"value" => "no",
						"type" => "switch",
						"options" => soapery_get_sc_param('yes_no')
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'soapery'),
						"desc" => wp_kses_data( __('Select icon for the closed toggles item from Fontello icons set',  'soapery') ),
						"value" => "",
						"type" => "icons",
						"options" => soapery_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'soapery'),
						"desc" => wp_kses_data( __('Select icon for the opened toggles item from Fontello icons set',  'soapery') ),
						"value" => "",
						"type" => "icons",
						"options" => soapery_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Toggles item content", 'soapery'),
						"desc" => wp_kses_data( __("Current toggles item content", 'soapery') ),
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
if ( !function_exists( 'soapery_sc_toggles_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_toggles_reg_shortcodes_vc');
	function soapery_sc_toggles_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_toggles",
			"name" => esc_html__("Toggles", 'soapery'),
			"description" => wp_kses_data( __("Toggles items", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_toggles',
			"class" => "trx_sc_collection trx_sc_toggles",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_toggles_item'),
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'soapery'),
					"description" => wp_kses_data( __("Display counter before each toggles title", 'soapery') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the closed toggles item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the opened toggles item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'soapery' ) . '"][/trx_toggles_item]
				[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'soapery' ) . '"][/trx_toggles_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'soapery').'">'.esc_html__("Add item", 'soapery').'</button>
				</div>
			',
			'js_view' => 'VcTrxTogglesView'
		) );
		
		
		vc_map( array(
			"base" => "trx_toggles_item",
			"name" => esc_html__("Toggles item", 'soapery'),
			"description" => wp_kses_data( __("Single toggles item", 'soapery') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_toggles_item',
			"as_child" => array('only' => 'trx_toggles'),
			"as_parent" => array('except' => 'trx_toggles'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'soapery'),
					"description" => wp_kses_data( __("Title for current toggles item", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Open on show", 'soapery'),
					"description" => wp_kses_data( __("Open current toggle item on show", 'soapery') ),
					"class" => "",
					"value" => array("Opened" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the closed toggles item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the opened toggles item from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css')
			),
			'js_view' => 'VcTrxTogglesTabView'
		) );
		class WPBakeryShortCode_Trx_Toggles extends SOAPERY_VC_ShortCodeToggles {}
		class WPBakeryShortCode_Trx_Toggles_Item extends SOAPERY_VC_ShortCodeTogglesItem {}
	}
}
?>
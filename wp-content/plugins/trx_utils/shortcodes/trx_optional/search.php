<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_search_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_search_theme_setup' );
	function soapery_sc_search_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_search_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('soapery_sc_search')) {	
	function soapery_sc_search($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'soapery'),
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
		if (empty($ajax)) $ajax = soapery_get_theme_option('use_ajax_search');
		// Load core messages
		soapery_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (soapery_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" data-text="'.esc_html__('Search','soapery').'" class="search_submit sc_button sc_button_square sc_button_style_filled sc_button_size_small" title="' . ($state=='closed' ? esc_attr__('Open search', 'soapery') : esc_attr__('Start search', 'soapery')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !soapery_param_is_off($scheme) && !soapery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	soapery_require_shortcode('trx_search', 'soapery_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_search_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_search_reg_shortcodes');
	function soapery_sc_search_reg_shortcodes() {
	
		soapery_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'soapery'),
			"desc" => wp_kses_data( __("Show search form", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'soapery'),
					"desc" => wp_kses_data( __("Select style to display search field", 'soapery') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'soapery'),
						"rounded" => esc_html__('Rounded', 'soapery')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'soapery'),
					"desc" => wp_kses_data( __("Select search field initial state", 'soapery') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'soapery'),
						"opened" => esc_html__('Opened', 'soapery'),
						"closed" => esc_html__('Closed', 'soapery')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'soapery'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'soapery') ),
					"value" => esc_html__("Search &hellip;", 'soapery'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'soapery'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'soapery') ),
					"value" => "yes",
					"options" => soapery_get_sc_param('yes_no'),
					"type" => "switch"
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
if ( !function_exists( 'soapery_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_search_reg_shortcodes_vc');
	function soapery_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'soapery'),
			"description" => wp_kses_data( __("Insert search form", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'soapery'),
					"description" => wp_kses_data( __("Select style to display search field", 'soapery') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'soapery') => "regular",
						esc_html__('Flat', 'soapery') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'soapery'),
					"description" => wp_kses_data( __("Select search field initial state", 'soapery') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'soapery')  => "fixed",
						esc_html__('Opened', 'soapery') => "opened",
						esc_html__('Closed', 'soapery') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'soapery'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'soapery'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'soapery'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'soapery') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'soapery') => 'yes'),
					"type" => "checkbox"
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
		
		class WPBakeryShortCode_Trx_Search extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
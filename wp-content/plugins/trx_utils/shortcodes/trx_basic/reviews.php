<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_reviews_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_reviews_theme_setup' );
	function soapery_sc_reviews_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_reviews_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_reviews_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_reviews]
*/

if (!function_exists('soapery_sc_reviews')) {	
	function soapery_sc_reviews($atts, $content = null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
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
		$output = soapery_param_is_off(soapery_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(soapery_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('soapery_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	soapery_require_shortcode("trx_reviews", "soapery_sc_reviews");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_reviews_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_reviews_reg_shortcodes');
	function soapery_sc_reviews_reg_shortcodes() {
	
		soapery_sc_map("trx_reviews", array(
			"title" => esc_html__("Reviews", 'soapery'),
			"desc" => wp_kses_data( __("Insert reviews block in the single post", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment", 'soapery'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'soapery') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('align')
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
if ( !function_exists( 'soapery_sc_reviews_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_reviews_reg_shortcodes_vc');
	function soapery_sc_reviews_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_reviews",
			"name" => esc_html__("Reviews", 'soapery'),
			"description" => wp_kses_data( __("Insert reviews block in the single post", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_reviews',
			"class" => "trx_sc_single trx_sc_reviews",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'soapery'),
					"description" => wp_kses_data( __("Align counter to left, center or right", 'soapery') ),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('align')),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Reviews extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
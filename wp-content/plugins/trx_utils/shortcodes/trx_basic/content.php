<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_content_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_content_theme_setup' );
	function soapery_sc_content_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_content_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('soapery_sc_content')) {	
	function soapery_sc_content($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !soapery_param_is_off($scheme) && !soapery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	soapery_require_shortcode('trx_content', 'soapery_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_content_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_content_reg_shortcodes');
	function soapery_sc_content_reg_shortcodes() {
	
		soapery_sc_map("trx_content", array(
			"title" => esc_html__("Content block", 'soapery'),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", 'soapery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'soapery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"options" => soapery_get_sc_param('schemes')
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'soapery'),
					"desc" => wp_kses_data( __("Content for section container", 'soapery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => soapery_get_sc_param('top'),
				"bottom" => soapery_get_sc_param('bottom'),
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
if ( !function_exists( 'soapery_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_content_reg_shortcodes_vc');
	function soapery_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", 'soapery'),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'soapery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"group" => esc_html__('Colors and Images', 'soapery'),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'soapery'),
					"description" => wp_kses_data( __("Content for section container", 'soapery') ),
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
				soapery_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends SOAPERY_VC_ShortCodeCollection {}
	}
}
?>
<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_countdown_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_countdown_theme_setup' );
	function soapery_sc_countdown_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_countdown_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_countdown_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_countdown date="" time=""]

if (!function_exists('soapery_sc_countdown')) {	
	function soapery_sc_countdown($atts, $content = null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "1",
			"align" => "center",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= soapery_get_css_dimensions_from_values($width, $height);
		if (empty($interval)) $interval = 1;
		wp_enqueue_script( 'soapery-jquery-plugin-script', soapery_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );	
		wp_enqueue_script( 'soapery-countdown-script', soapery_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
			. '>'
				. ($align=='center' ? '<div class="sc_countdown_inner">' : '')
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Days', 'soapery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Hours', 'soapery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Minutes', 'soapery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Seconds', 'soapery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
				. ($align=='center' ? '</div>' : '')
			. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	soapery_require_shortcode("trx_countdown", "soapery_sc_countdown");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_countdown_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_countdown_reg_shortcodes');
	function soapery_sc_countdown_reg_shortcodes() {
	
		soapery_sc_map("trx_countdown", array(
			"title" => esc_html__("Countdown", 'soapery'),
			"desc" => wp_kses_data( __("Insert countdown object", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"date" => array(
					"title" => esc_html__("Date", 'soapery'),
					"desc" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'soapery') ),
					"value" => "",
					"format" => "yy-mm-dd",
					"type" => "date"
				),
				"time" => array(
					"title" => esc_html__("Time", 'soapery'),
					"desc" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", 'soapery'),
					"desc" => wp_kses_data( __("Countdown style", 'soapery') ),
					"value" => "1",
					"type" => "checklist",
					"options" => soapery_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'soapery'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'soapery') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('align')
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
if ( !function_exists( 'soapery_sc_countdown_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_countdown_reg_shortcodes_vc');
	function soapery_sc_countdown_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_countdown",
			"name" => esc_html__("Countdown", 'soapery'),
			"description" => wp_kses_data( __("Insert countdown object", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_countdown',
			"class" => "trx_sc_single trx_sc_countdown",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "date",
					"heading" => esc_html__("Date", 'soapery'),
					"description" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "time",
					"heading" => esc_html__("Time", 'soapery'),
					"description" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'soapery'),
					"description" => wp_kses_data( __("Countdown style", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
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
				soapery_vc_width(),
				soapery_vc_height(),
				soapery_get_vc_param('margin_top'),
				soapery_get_vc_param('margin_bottom'),
				soapery_get_vc_param('margin_left'),
				soapery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Countdown extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
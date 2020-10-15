<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_zoom_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_zoom_theme_setup' );
	function soapery_sc_zoom_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_zoom_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_zoom_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/

if (!function_exists('soapery_sc_zoom')) {	
	function soapery_sc_zoom($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"effect" => "zoom",
			"src" => "",
			"url" => "",
			"over" => "",
			"align" => "",
			"bg_image" => "",
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		wp_enqueue_script( 'soapery-elevate-zoom-script', soapery_get_file_url('js/jquery.elevateZoom-3.0.4.js'), array(), null, true );
	
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = soapery_get_css_dimensions_from_values($width, $height);
		$css_bg = soapery_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
		$width  = soapery_prepare_css_value($width);
		$height = soapery_prepare_css_value($height);
		if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($over > 0) {
			$attach = wp_get_attachment_image_src( $over, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$over = $attach[0];
		}
		if ($effect=='lens' && ((int) $width > 0 && soapery_substr($width, -2, 2)=='px') || ((int) $height > 0 && soapery_substr($height, -2, 2)=='px')) {
			if ($src)
				$src = soapery_get_resized_image_url($src, (int) $width > 0 && soapery_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && soapery_substr($height, -2, 2)=='px' ? (int) $height : null);
			if ($over)
				$over = soapery_get_resized_image_url($over, (int) $width > 0 && soapery_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && soapery_substr($height, -2, 2)=='px' ? (int) $height : null);
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
		$output = empty($src) 
				? '' 
				: (
					(!empty($bg_image) 
						? '<div class="sc_zoom_wrap'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
								. '"'
							. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
							. ($css_bg!='' ? ' style="'.esc_attr($css_bg).'"' : '') 
							. '>' 
						: '')
					.'<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_zoom' 
								. (empty($bg_image) && !empty($class) ? ' '.esc_attr($class) : '') 
								. (empty($bg_image) && $align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (empty($bg_image) && !soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. '<img src="'.esc_url($src).'"' . ($css_dim!='' ? ' style="'.esc_attr($css_dim).'"' : '') . ' data-zoom-image="'.esc_url($over).'" alt="" />'
					. '</div>'
					. (!empty($bg_image) 
						? '</div>' 
						: '')
				);
		return apply_filters('soapery_shortcode_output', $output, 'trx_zoom', $atts, $content);
	}
	soapery_require_shortcode('trx_zoom', 'soapery_sc_zoom');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_zoom_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_zoom_reg_shortcodes');
	function soapery_sc_zoom_reg_shortcodes() {
	
		soapery_sc_map("trx_zoom", array(
			"title" => esc_html__("Zoom", 'soapery'),
			"desc" => wp_kses_data( __("Insert the image with zoom/lens effect", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"effect" => array(
					"title" => esc_html__("Effect", 'soapery'),
					"desc" => wp_kses_data( __("Select effect to display overlapping image", 'soapery') ),
					"value" => "lens",
					"size" => "medium",
					"type" => "switch",
					"options" => array(
						"lens" => esc_html__('Lens', 'soapery'),
						"zoom" => esc_html__('Zoom', 'soapery')
					)
				),
				"url" => array(
					"title" => esc_html__("Main image", 'soapery'),
					"desc" => wp_kses_data( __("Select or upload main image", 'soapery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"over" => array(
					"title" => esc_html__("Overlaping image", 'soapery'),
					"desc" => wp_kses_data( __("Select or upload overlaping image", 'soapery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"align" => array(
					"title" => esc_html__("Float zoom", 'soapery'),
					"desc" => wp_kses_data( __("Float zoom to left or right side", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('float')
				), 
				"bg_image" => array(
					"title" => esc_html__("Background image", 'soapery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", 'soapery') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", 'soapery'),
					"desc" => wp_kses_data( __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", 'soapery') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", 'soapery'),
					"desc" => wp_kses_data( __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", 'soapery') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", 'soapery'),
					"desc" => wp_kses_data( __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", 'soapery') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", 'soapery'),
					"desc" => wp_kses_data( __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", 'soapery') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
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
if ( !function_exists( 'soapery_sc_zoom_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_zoom_reg_shortcodes_vc');
	function soapery_sc_zoom_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_zoom",
			"name" => esc_html__("Zoom", 'soapery'),
			"description" => wp_kses_data( __("Insert the image with zoom/lens effect", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_zoom',
			"class" => "trx_sc_single trx_sc_zoom",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "effect",
					"heading" => esc_html__("Effect", 'soapery'),
					"description" => wp_kses_data( __("Select effect to display overlapping image", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"std" => "zoom",
					"value" => array(
						esc_html__('Lens', 'soapery') => 'lens',
						esc_html__('Zoom', 'soapery') => 'zoom'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Main image", 'soapery'),
					"description" => wp_kses_data( __("Select or upload main image", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "over",
					"heading" => esc_html__("Overlaping image", 'soapery'),
					"description" => wp_kses_data( __("Select or upload overlaping image", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'soapery'),
					"description" => wp_kses_data( __("Float zoom to left or right side", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'soapery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'soapery') ),
					"group" => esc_html__('Background', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", 'soapery'),
					"description" => wp_kses_data( __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", 'soapery') ),
					"group" => esc_html__('Background', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", 'soapery'),
					"description" => wp_kses_data( __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", 'soapery') ),
					"group" => esc_html__('Background', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", 'soapery'),
					"description" => wp_kses_data( __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", 'soapery') ),
					"group" => esc_html__('Background', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", 'soapery'),
					"description" => wp_kses_data( __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", 'soapery') ),
					"group" => esc_html__('Background', 'soapery'),
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Zoom extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
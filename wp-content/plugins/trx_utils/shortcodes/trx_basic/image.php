<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_image_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_image_theme_setup' );
	function soapery_sc_image_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_image_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('soapery_sc_image')) {	
	function soapery_sc_image($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= soapery_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = soapery_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) soapery_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('soapery_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	soapery_require_shortcode('trx_image', 'soapery_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_image_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_image_reg_shortcodes');
	function soapery_sc_image_reg_shortcodes() {
	
		soapery_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'soapery'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'soapery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'soapery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'soapery'),
					"desc" => wp_kses_data( __("Image title (if need)", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'soapery'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'soapery') ),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'soapery'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'soapery'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'soapery') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'soapery'),
						"round" => esc_html__('Round', 'soapery')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'soapery'),
					"desc" => wp_kses_data( __("The link URL from the image", 'soapery') ),
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
if ( !function_exists( 'soapery_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_image_reg_shortcodes_vc');
	function soapery_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'soapery'),
			"description" => wp_kses_data( __("Insert image", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'soapery'),
					"description" => wp_kses_data( __("Select image from library", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'soapery'),
					"description" => wp_kses_data( __("Align image to left or right side", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'soapery'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'soapery') => 'square',
						esc_html__('Round', 'soapery') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'soapery'),
					"description" => wp_kses_data( __("Image's title", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'soapery'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'soapery') ),
					"class" => "",
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'soapery'),
					"description" => wp_kses_data( __("The link URL from the image", 'soapery') ),
					"admin_label" => true,
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
		
		class WPBakeryShortCode_Trx_Image extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
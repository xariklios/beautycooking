<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_parallax_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_parallax_theme_setup' );
	function soapery_sc_parallax_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_parallax_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_parallax_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_parallax id="unique_id" style="light|dark" dir="up|down" image="" color='']Content for parallax block[/trx_parallax]
*/

if (!function_exists('soapery_sc_parallax')) {	
	function soapery_sc_parallax($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"gap" => "no",
			"dir" => "up",
			"speed" => 0.3,
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_image_x" => "",
			"bg_image_y" => "",
			"bg_video" => "",
			"bg_video_ratio" => "16:9",
			"bg_overlay" => "",
			"bg_texture" => "",
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
		if ($bg_video!='') {
			$info = pathinfo($bg_video);
			$ext = !empty($info['extension']) ? $info['extension'] : 'mp4';
			$bg_video_ratio = empty($bg_video_ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $bg_video_ratio);
			$ratio = explode(':', $bg_video_ratio);
			$bg_video_width = !empty($width) && soapery_substr($width, -1) >= '0' && soapery_substr($width, -1) <= '9'  ? $width : 1280;
			$bg_video_height = round($bg_video_width / $ratio[0] * $ratio[1]);
			if (soapery_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		$bg_image_x = $bg_image_x!='' ? str_replace('%', '', $bg_image_x).'%' : "50%";
		$bg_image_y = $bg_image_y!='' ? str_replace('%', '', $bg_image_y).'%' : "50%";
		$speed = ($dir=='down' ? -1 : 1) * abs($speed);
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = soapery_get_scheme_color('bg');
			$rgb = soapery_hex2rgb($bg_color);
		}
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= soapery_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			;
		$output = (soapery_param_is_on($gap) ? soapery_gap_start() : '')
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_parallax' 
					. ($bg_video!='' ? ' sc_parallax_with_video' : '') 
					. ($scheme && !soapery_param_is_off($scheme) && !soapery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. ' data-parallax-speed="'.esc_attr($speed).'"'
				. ' data-parallax-x-pos="'.esc_attr($bg_image_x).'"'
				. ' data-parallax-y-pos="'.esc_attr($bg_image_y).'"'
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. '>'
			. ($bg_video!='' 
				? '<div class="sc_video_bg_wrapper"><video class="sc_video_bg"'
					. ' width="'.esc_attr($bg_video_width).'" height="'.esc_attr($bg_video_height).'" data-width="'.esc_attr($bg_video_width).'" data-height="'.esc_attr($bg_video_height).'" data-ratio="'.esc_attr($bg_video_ratio).'" data-frame="no"'
					. ' preload="metadata" autoplay="autoplay" loop="loop" src="'.esc_attr($bg_video).'"><source src="'.esc_url($bg_video).'" type="video/'.esc_attr($ext).'"></source></video></div>' 
				: '')
			. '<div class="sc_parallax_content" style="' . ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . '); background-position:'.esc_attr($bg_image_x).' '.esc_attr($bg_image_y).';' : '').'">'
			. ($bg_overlay>0 || $bg_texture!=''
				? '<div class="sc_parallax_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
					. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
						. (soapery_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
						. '"'
						. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
						. '>' 
				: '')
			. do_shortcode($content)
			. ($bg_overlay > 0 || $bg_texture!='' ? '</div>' : '')
			. '</div>'
			. '</div>'
			. (soapery_param_is_on($gap) ? soapery_gap_end() : '');
		return apply_filters('soapery_shortcode_output', $output, 'trx_parallax', $atts, $content);
	}
	soapery_require_shortcode('trx_parallax', 'soapery_sc_parallax');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_parallax_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_parallax_reg_shortcodes');
	function soapery_sc_parallax_reg_shortcodes() {
	
		soapery_sc_map("trx_parallax", array(
			"title" => esc_html__("Parallax", 'soapery'),
			"desc" => wp_kses_data( __("Create the parallax container (with asinc background image)", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"gap" => array(
					"title" => esc_html__("Create gap", 'soapery'),
					"desc" => wp_kses_data( __("Create gap around parallax container", 'soapery') ),
					"value" => "no",
					"size" => "small",
					"options" => soapery_get_sc_param('yes_no'),
					"type" => "switch"
				), 
				"dir" => array(
					"title" => esc_html__("Dir", 'soapery'),
					"desc" => wp_kses_data( __("Scroll direction for the parallax background", 'soapery') ),
					"value" => "up",
					"size" => "medium",
					"options" => array(
						'up' => esc_html__('Up', 'soapery'),
						'down' => esc_html__('Down', 'soapery')
					),
					"type" => "switch"
				), 
				"speed" => array(
					"title" => esc_html__("Speed", 'soapery'),
					"desc" => wp_kses_data( __("Image motion speed (from 0.0 to 1.0)", 'soapery') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0.3",
					"type" => "spinner"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'soapery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"options" => soapery_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'soapery'),
					"desc" => wp_kses_data( __("Select color for text object inside parallax block", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'soapery'),
					"desc" => wp_kses_data( __("Select color for parallax background", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", 'soapery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the parallax background", 'soapery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image_x" => array(
					"title" => esc_html__("Image X position", 'soapery'),
					"desc" => wp_kses_data( __("Image horizontal position (as background of the parallax block) - in percent", 'soapery') ),
					"min" => "0",
					"max" => "100",
					"value" => "50",
					"type" => "spinner"
				),
				"bg_video" => array(
					"title" => esc_html__("Video background", 'soapery'),
					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site to show it as parallax background", 'soapery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'soapery'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'soapery'),
							'update' => esc_html__('Select video file', 'soapery')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"bg_video_ratio" => array(
					"title" => esc_html__("Video ratio", 'soapery'),
					"desc" => wp_kses_data( __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", 'soapery') ),
					"value" => "16:9",
					"type" => "text"
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'soapery'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'soapery') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'soapery'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'soapery') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"_content_" => array(
					"title" => esc_html__("Content", 'soapery'),
					"desc" => wp_kses_data( __("Content for the parallax container", 'soapery') ),
					"divider" => true,
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
if ( !function_exists( 'soapery_sc_parallax_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_parallax_reg_shortcodes_vc');
	function soapery_sc_parallax_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_parallax",
			"name" => esc_html__("Parallax", 'soapery'),
			"description" => wp_kses_data( __("Create the parallax container (with asinc background image)", 'soapery') ),
			"category" => esc_html__('Structure', 'soapery'),
			'icon' => 'icon_trx_parallax',
			"class" => "trx_sc_collection trx_sc_parallax",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "gap",
					"heading" => esc_html__("Create gap", 'soapery'),
					"description" => wp_kses_data( __("Create gap around parallax container (not need in fullscreen pages)", 'soapery') ),
					"class" => "",
					"value" => array(esc_html__('Create gap', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", 'soapery'),
					"description" => wp_kses_data( __("Scroll direction for the parallax background", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Up', 'soapery') => 'up',
							esc_html__('Down', 'soapery') => 'down'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "speed",
					"heading" => esc_html__("Speed", 'soapery'),
					"description" => wp_kses_data( __("Parallax background motion speed (from 0.0 to 1.0)", 'soapery') ),
					"class" => "",
					"value" => "0.3",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'soapery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'soapery') ),
					"group" => esc_html__('Colors and Images', 'soapery'),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'soapery'),
					"description" => wp_kses_data( __("Select color for text object inside parallax block", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Backgroud color", 'soapery'),
					"description" => wp_kses_data( __("Select color for parallax background", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'soapery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the parallax background", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image_x",
					"heading" => esc_html__("Image X position", 'soapery'),
					"description" => wp_kses_data( __("Parallax background X position (in percents)", 'soapery') ),
					"class" => "",
					"value" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_video",
					"heading" => esc_html__("Video background", 'soapery'),
					"description" => wp_kses_data( __("Paste URL for video file to show it as parallax background", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_video_ratio",
					"heading" => esc_html__("Video ratio", 'soapery'),
					"description" => wp_kses_data( __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", 'soapery') ),
					"class" => "",
					"value" => "16:9",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'soapery'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'soapery'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Content", 'soapery'),
					"description" => wp_kses_data( __("Content for the parallax container", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
		
		class WPBakeryShortCode_Trx_Parallax extends SOAPERY_VC_ShortCodeCollection {}
	}
}
?>
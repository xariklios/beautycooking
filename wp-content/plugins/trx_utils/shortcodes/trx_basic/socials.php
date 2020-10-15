<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_socials_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_socials_theme_setup' );
	function soapery_sc_socials_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_socials_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('soapery_sc_socials')) {	
	function soapery_sc_socials($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => soapery_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		soapery_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? soapery_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) soapery_storage_set_array('sc_social_data', 'icons', $list);
		} else if (soapery_param_is_off($custom))
			$content = do_shortcode($content);
		if (soapery_storage_get_array('sc_social_data', 'icons')===false) soapery_storage_set_array('sc_social_data', 'icons', soapery_get_custom_option('social_icons'));
		$output = soapery_prepare_socials(soapery_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('soapery_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	soapery_require_shortcode('trx_socials', 'soapery_sc_socials');
}


if (!function_exists('soapery_sc_social_item')) {	
	function soapery_sc_social_item($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = soapery_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(soapery_get_socials_dir($name.'.png')))
					$icon = soapery_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (soapery_storage_get_array('sc_social_data', 'icons')===false) soapery_storage_set_array('sc_social_data', 'icons', array());
			soapery_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	soapery_require_shortcode('trx_social_item', 'soapery_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_socials_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_socials_reg_shortcodes');
	function soapery_sc_socials_reg_shortcodes() {
	
		soapery_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'soapery'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'soapery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'soapery'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'soapery') ),
					"value" => soapery_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'soapery'),
						'images' => esc_html__('Images', 'soapery')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'soapery'),
					"desc" => wp_kses_data( __("Size of the icons", 'soapery') ),
					"value" => "small",
					"options" => soapery_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'soapery'),
					"desc" => wp_kses_data( __("Shape of the icons", 'soapery') ),
					"value" => "square",
					"options" => soapery_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'soapery'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'soapery'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'soapery') ),
					"divider" => true,
					"value" => "no",
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
			),
			"children" => array(
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'soapery'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'soapery') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'soapery'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'soapery') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'soapery'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'soapery') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'soapery'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'soapery') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_socials_reg_shortcodes_vc');
	function soapery_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'soapery'),
			"description" => wp_kses_data( __("Custom social icons", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'soapery'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'soapery') ),
					"class" => "",
					"std" => soapery_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'soapery') => 'icons',
						esc_html__('Images', 'soapery') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'soapery'),
					"description" => wp_kses_data( __("Size of the icons", 'soapery') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(soapery_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'soapery'),
					"description" => wp_kses_data( __("Shape of the icons", 'soapery') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(soapery_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'soapery'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'soapery'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'soapery') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'soapery') => 'yes'),
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'soapery'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'soapery') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'soapery'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'soapery'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'soapery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends SOAPERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
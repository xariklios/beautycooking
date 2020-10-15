<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_title_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_title_theme_setup' );
	function soapery_sc_title_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_title_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('soapery_sc_title')) {	
	function soapery_sc_title($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= soapery_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !soapery_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !soapery_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(soapery_strpos($image, 'http:')!==false ? $image : soapery_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !soapery_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	soapery_require_shortcode('trx_title', 'soapery_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_title_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_title_reg_shortcodes');
	function soapery_sc_title_reg_shortcodes() {
	
		soapery_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'soapery'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'soapery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'soapery'),
					"desc" => wp_kses_data( __("Title content", 'soapery') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'soapery'),
					"desc" => wp_kses_data( __("Title type (header level)", 'soapery') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'soapery'),
						'2' => esc_html__('Header 2', 'soapery'),
						'3' => esc_html__('Header 3', 'soapery'),
						'4' => esc_html__('Header 4', 'soapery'),
						'5' => esc_html__('Header 5', 'soapery'),
						'6' => esc_html__('Header 6', 'soapery'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'soapery'),
					"desc" => wp_kses_data( __("Title style", 'soapery') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'soapery'),
						'underline' => esc_html__('Underline', 'soapery'),
						'divider' => esc_html__('Divider', 'soapery'),
						'iconed' => esc_html__('With icon (image)', 'soapery')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'soapery'),
					"desc" => wp_kses_data( __("Title text alignment", 'soapery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'soapery'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'soapery'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'soapery') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'soapery'),
						'100' => esc_html__('Thin (100)', 'soapery'),
						'300' => esc_html__('Light (300)', 'soapery'),
						'400' => esc_html__('Normal (400)', 'soapery'),
						'600' => esc_html__('Semibold (600)', 'soapery'),
						'700' => esc_html__('Bold (700)', 'soapery'),
						'900' => esc_html__('Black (900)', 'soapery')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'soapery'),
					"desc" => wp_kses_data( __("Select color for the title", 'soapery') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'soapery'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'soapery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => soapery_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'soapery'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'soapery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => soapery_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'soapery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'soapery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'soapery'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'soapery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'soapery'),
						'medium' => esc_html__('Medium', 'soapery'),
						'large' => esc_html__('Large', 'soapery')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'soapery'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'soapery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'soapery'),
						'left' => esc_html__('Left', 'soapery')
					)
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
if ( !function_exists( 'soapery_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_title_reg_shortcodes_vc');
	function soapery_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'soapery'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'soapery'),
					"description" => wp_kses_data( __("Title content", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'soapery'),
					"description" => wp_kses_data( __("Title type (header level)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'soapery') => '1',
						esc_html__('Header 2', 'soapery') => '2',
						esc_html__('Header 3', 'soapery') => '3',
						esc_html__('Header 4', 'soapery') => '4',
						esc_html__('Header 5', 'soapery') => '5',
						esc_html__('Header 6', 'soapery') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'soapery'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'soapery') => 'regular',
						esc_html__('Underline', 'soapery') => 'underline',
						esc_html__('Divider', 'soapery') => 'divider',
						esc_html__('With icon (image)', 'soapery') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'soapery'),
					"description" => wp_kses_data( __("Title text alignment", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'soapery'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'soapery'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'soapery') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'soapery') => 'inherit',
						esc_html__('Thin (100)', 'soapery') => '100',
						esc_html__('Light (300)', 'soapery') => '300',
						esc_html__('Normal (400)', 'soapery') => '400',
						esc_html__('Semibold (600)', 'soapery') => '600',
						esc_html__('Bold (700)', 'soapery') => '700',
						esc_html__('Black (900)', 'soapery') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'soapery'),
					"description" => wp_kses_data( __("Select color for the title", 'soapery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'soapery'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'soapery') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'soapery'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => soapery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'soapery'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'soapery') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'soapery'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => soapery_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'soapery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'soapery') ),
					"group" => esc_html__('Icon &amp; Image', 'soapery'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'soapery'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'soapery') ),
					"group" => esc_html__('Icon &amp; Image', 'soapery'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'soapery') => 'small',
						esc_html__('Medium', 'soapery') => 'medium',
						esc_html__('Large', 'soapery') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'soapery'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'soapery') ),
					"group" => esc_html__('Icon &amp; Image', 'soapery'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'soapery') => 'top',
						esc_html__('Left', 'soapery') => 'left'
					),
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
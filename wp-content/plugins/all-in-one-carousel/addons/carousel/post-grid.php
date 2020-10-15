<?php
include('post-grid-shortcode.php');
if(!class_exists('svc_carousel_layout'))
{
	class svc_carousel_layout
	{
		function __construct()
		{
			add_action('admin_init',array($this,'svc_carousel_layout_init'));
			add_shortcode('svc_carousel_layout','svc_carousel_layout_shortcode');
		}
		function svc_carousel_layout_init()
		{

			if(function_exists('vc_map'))
			{
				$animations = array(
				'None' => '',
				'bounce'		=>	'bounce',
				'flash'			=>	'flash',
				'pulse'			=>	'pulse',
				'rubberBand'	=>	'rubberBand',
				'shake'			=>	'shake',
				'swing'			=>	'swing',
				'tada'			=>	'tada',
				'bounce'		=>	'bounce',
				'wobble'		=>	'wobble',
				'bounceIn'		=>	'bounceIn',
				'bounceInDown'	=>	'bounceInDown',
				'bounceInLeft'	=>	'bounceInLeft',
				'bounceInRight'	=>	'bounceInRight',
				'bounceInUp'	=>	'bounceInUp',
				'fadeIn'			=>	'fadeIn',
				'fadeInDown'		=>	'fadeInDown',
				'fadeInDownBig'		=>	'fadeInDownBig',
				'fadeInLeft'		=>	'fadeInLeft',
				'fadeInLeftBig'		=>	'fadeInLeftBig',
				'fadeInRight'		=>	'fadeInRight',
				'fadeInRightBig'	=>	'fadeInRightBig',
				'fadeInUp'			=>	'fadeInUp',
				'fadeInUpBig'		=>	'fadeInUpBig',
				'flip'	=>	'flip',
				'flipInX'	=>	'flipInX',
				'flipInY'	=>	'flipInY',
				'lightSpeedIn'	=>	'lightSpeedIn',
				'rotateIn'			=>	'rotateIn',
				'rotateInDownLeft'	=>	'rotateInDownLeft',
				'rotateInDownRight'	=>	'rotateInDownRight',
				'rotateInUpLeft'	=>	'rotateInUpLeft',
				'rotateInUpRight'	=>	'rotateInUpRight',
				'slideInUp' => 'slideInUp',
				'slideInDown' => 'slideInDown',
				'slideInLeft' => 'slideInLeft',
				'slideInRight' => 'slideInRight',
				'zoomIn'		=>	'zoomIn',
				'zoomInDown'	=>	'zoomInDown',
				'zoomInLeft'	=>	'zoomInLeft',
				'zoomInRight'	=>	'zoomInRight',
				'zoomInUp'		=>	'zoomInUp',
				'rollIn'	=>	'rollIn',
				'twisterInDown'	=>	'twisterInDown',
				'twisterInUp'	=>	'twisterInUp',
				'swap'			=>	'swap',
				'puffIn'	=>	'puffIn',
				'vanishIn'	=>	'vanishIn',
				'openDownLeftRetourn'	=>	'openDownLeftRetourn',
				'openDownRightRetourn'	=>	'openDownRightRetourn',
				'openUpLeftRetourn'		=>	'openUpLeftRetourn',
				'openUpRightRetourn'	=>	'openUpRightRetourn',
				'perspectiveDownRetourn'	=>	'perspectiveDownRetourn',
				'perspectiveUpRetourn'		=>	'perspectiveUpRetourn',
				'perspectiveLeftRetourn'	=>	'perspectiveLeftRetourn',
				'perspectiveRightRetourn'	=>	'perspectiveRightRetourn',
				'slideDownRetourn'	=>	'slideDownRetourn',
				'slideUpRetourn'	=>	'slideUpRetourn',
				'slideLeftRetourn'	=>	'slideLeftRetourn',
				'slideRightRetourn'	=>	'slideRightRetourn',
				'swashIn'		=>	'swashIn',
				'foolishIn'		=>	'foolishIn',
				'tinRightIn'	=>	'tinRightIn',
				'tinLeftIn'		=>	'tinLeftIn',
				'tinUpIn'		=>	'tinUpIn',
				'tinDownIn'		=>	'tinDownIn',
				'boingInUp'		=>	'boingInUp',
				'spaceInUp'		=>	'spaceInUp',
				'spaceInRight'	=>	'spaceInRight',
				'spaceInDown'	=>	'spaceInDown',
				'spaceInLeft'	=>	'spaceInLeft'
			);
				$svc_layout_sub_controls = array(
				  array( '', __( '', 'js_composer'  ) )
				);
				vc_map( array(
					"name" => __('All In One Carousel','js_composer'),		
					"base" => 'svc_carousel_layout',		
					"icon" => 'vc-animate-icon',		
					"category" => __('Carousel','js_composer'),
					'description' => __( 'Set Carousel for post,Image,Video','js_composer' ),
					"params" => array(
						array(
							'type' => 'textfield',
							'heading' => __( 'Title', 'js_composer' ),
							'param_name' => 'title',
							'holder' => 'div',
							'description' => __( 'Enter Carousel title', 'js_composer' )
						),
						array(
							"type" => "dropdown",
							"heading" => __("Carousel type" , 'js_composer' ),
							"param_name" => "svc_type",
							"value" =>array(
								__("Post Carousel", 'js_composer' )=>"post_layout",
								__("Image Carousel", 'js_composer' )=>"carousel",
								__("Video Carousel", 'js_composer' )=>"video",
								__("Custom Layout for Post Type Carousel", 'js_composer' )=>"custom_layout"
								),
							"std" => "post_layout",
							"description" => __("Choose Carousel type.", 'js_composer' ),
						),
						array(
							'type' => 'loop',
							'heading' => __('Build Post Query','js_composer'),
							'param_name' => 'query_loop',
							'settings' => array(
								'post_type' => array('value' => 'post'),
								'size' => array( 'hidden' => false, 'value' => 10 ),
								'order_by' => array( 'value' => 'date' ),
								'order' => array('value' => 'DESC')
							),
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __('Create WordPress loop, to populate content from your site.','js_composer')
						),
						array(
							"type" => "dropdown",
							"heading" => __("Skin type" , 'js_composer' ),
							"param_name" => "skin_type",
							"value" =>array(
								__("Style1", 'js_composer' )=>"s1",
								__("Style2", 'js_composer' )=>"s2",
								__("Style3", 'js_composer' )=>"s3",
								__("Style4", 'js_composer' )=>"s4",
								__("Style5", 'js_composer' )=>"s5",
								__("Style6 for List View", 'js_composer' )=>"s6"
								),
							'dependency' => array('element' => 'svc_type','value' => 'post_layout'),
							"description" => __("Choose skin type for grid layout.", 'js_composer' ),
						),
						array(
							"type" => "attach_images",
							"heading" => __("Select Images" , 'js_composer' ),
							"param_name" => "images_car",
							'dependency' => array('element' => 'svc_type','value' => 'carousel'),
							"description" => __("Choose Images for Carousel.", 'js_composer' ),
						),
						array(
							"type" => "exploded_textarea",
							"heading" => __("Enter Video URL" , 'js_composer' ),
							"param_name" => "video_car",
							'dependency' => array('element' => 'svc_type','value' => 'video'),
							"description" => __("Enter Youtube, Vimeo URL. Divide each with comma separate.
							ex : https://www.youtube.com/watch?v=BBQCHfQJLKs,https://vimeo.com/76940387,http://www.demo.comdemo.mp4", 'js_composer' ),
						),
						array(
							'type' => 'num',
							'heading' => __( 'Set Video Height', 'js_composer' ),
							'param_name' => 'video_height',
							'value' => '315',
							'min' => 0,
							'max' => 1500,
							'suffix' => '',
							'step' => 1,
							'dependency' => array('element' => 'svc_type','value' => 'video'),
							'description' => __( 'Set Video Height. if you set height "0" then work like "auto".', 'js_composer' )
						),
						array(
							  'type' => 'sorted_list',
							  'heading' => __( 'Teaser layout','js_composer'  ),
							  'param_name' => 'svc_teasr_carousel_layout',
							  'description' => __( 'Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.', 'js_composer'  ),
							  'value' => 'title,image,text',
							  'options' => array(
								  array( 'image', __( 'Thumbnail', 'js_composer'  ), $svc_layout_sub_controls ),
								  array( 'title', __( 'Title', 'js_composer'  ), $svc_layout_sub_controls ),
								  array( 'text', __( 'Text', 'js_composer'  ), array(
									  array( 'excerpt', __( 'Teaser/Excerpt', 'js_composer'  ) ),
									  array( 'text', __( 'Full content', 'js_composer'  ) )
								  ) ),
								  array( 'link', __( 'Read more link', 'js_composer'  ) )
							  ),
								'dependency' => array('element' => 'svc_type','value' => array( 'custom_layout' )
							),
						 ),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Image View', 'js_composer' ),
							'param_name' => 'image_view',
							'dependency' => array('element' => 'svc_type','value' => 'carousel'),
							'value' => array('Square' => 'square','Round' => 'round'),
						),
						array(
							'type' => 'num',
							'heading' => __( 'Items Display', 'js_composer' ),
							'param_name' => 'car_display_item',
							'value' => '4',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'This variable allows you to set the maximum amount of items displayed at a time with the widest browser width', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsDesktop Display', 'js_composer' ),
							'param_name' => 'car_desktop_display_item',
							'value' => '4',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 1199px and 979px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsDesktopSmall Display', 'js_composer' ),
							'param_name' => 'car_desktopsmall_display_item',
							'value' => '3',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 979px and 768px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsTablet Display', 'js_composer' ),
							'param_name' => 'car_tablet_display_item',
							'value' => '2',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 768px and 479px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsMobile Display', 'js_composer' ),
							'param_name' => 'car_mobile_display_item',
							'value' => '1',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 479px and 200px', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Show pagination', 'js_composer' ),
							'param_name' => 'car_pagination',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'description' => __( 'Show pagination', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Show pagination Numbers', 'js_composer' ),
							'param_name' => 'car_pagination_num',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'car_pagination','value' => 'yes'),
							'description' => __( 'Show numbers inside pagination buttons.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Hide navigation', 'js_composer' ),
							'param_name' => 'car_navigation',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'description' => __( 'Display "next" and "prev" buttons.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'AutoPlay', 'js_composer' ),
							'param_name' => 'car_autoplay',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'description' => __( 'Set Slider Autoplay.', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'autoPlay Time', 'js_composer' ),
							'param_name' => 'car_autoplay_time',
							'value' => '5',
							'min' => 1,
							'max' => 100,
							'suffix' => 'seconds',
							'step' => 1,
							'dependency' => array('element' => 'car_autoplay','value' => 'yes'),
							'description' => __( 'Set Autoplay slider speed.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'LazyLoad', 'js_composer' ),
							'param_name' => 'lazyload',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'svc_type','value' => 'carousel'),
							'description' => __( 'Set Lazy load.', 'js_composer' ),
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Synced Slider', 'js_composer' ),
							'param_name' => 'synced',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'car_display_item','value' => '1'),
							'description' => __( 'Set Synced Slider.see Example <a href="http://owlgraphic.com/owlcarousel/demos/sync.html" target="_black">here</a>', 'js_composer' ),
						),
						array(
							'type' => 'num',
							'heading' => __( 'Synced Display', 'js_composer' ),
							'param_name' => 'synced_display',
							'value' => '10',
							'min' => 1,
							'max' => 1000,
							'suffix' => '',
							'step' => 1,
							'dependency' => array('element' => 'synced','value' => 'yes'),
							'description' => __( 'Set Synces Slider Display elements.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Show more', 'js_composer' ),
							'param_name' => 'loadmore',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'svc_type','value' => 'post_layout'),
							'description' => __( 'add Show more Post last element of Carousel.', 'js_composer' ),
						),
						array(
							'type' => 'num',
							'heading' => __( 'Margin', 'js_composer' ),
							'param_name' => 'car_margin',
							'value' => '10',
							'min' => 0,
							'max' => 1000,
							'suffix' => 'px',
							'step' => 1,
							'dependency' => array('element' => 'svc_type','value' => array('carousel','video','custom_layout')),
							'description' => __( 'Set Two Element Between Margin.', 'js_composer' )
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Transition effect', 'js_composer' ),
							'param_name' => 'car_transition',
							'value' => array(
								__( 'None', 'js_composer' ) => '',
								__( 'fade', 'js_composer' ) => 'fade',
								__( 'backSlide', 'js_composer' ) => 'backSlide',
								__( 'goDown', 'js_composer' ) => 'goDown',
								__( 'fadeUp', 'js_composer' ) => 'fadeUp'
							),
							'dependency' => array('element' => 'car_display_item','value' => '1'),
							'description' => __( 'Add CSS3 transition style. Works only with one item on screen.', 'js_composer' )
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Link target', 'js_composer' ),
							'param_name' => 'grid_link_target',
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'value' => array('Same Window' => 'sw','New Window' => 'nw'),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Exclude taxonomies', 'js_composer' ),
							'param_name' => 'exclude_texo',
							'dependency' => array('element' => 'svc_type','value' => 'post_layout'),
							'description' => __( 'Enter Exclude taxonomies slug, Divide each with comma separate.get texonomy slug <a href="http://plugin.saragna.com/vc-addon/wp-content/uploads/2015/04/slug.png" target="_blank">click here</a>', 'js_composer' )
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Thumbnail size', 'js_composer' ),
							'param_name' => 'grid_thumb_size',
							'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height).', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'Minimum Height', 'js_composer' ),
							'param_name' => 's5_min_height',
							'value' => '150',
							'min' => 50,
							'max' => 1000,
							'suffix' => 'px',
							'step' => 1,
							'dependency' => array('element' => 'skin_type','value' => 's5'),
							'description' => __( 'if you not set fetured image so set Minimum Height for artical.default:170px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'Excerpt Length', 'js_composer' ),
							'param_name' => 'svc_excerpt_length',
							'value' => '20',
							'min' => 0,
							'max' => 9000,
							'suffix' => '',
							'step' => 1,
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'set excerpt length.default:20', 'js_composer' )
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Read More Translate', 'js_composer' ),
							'param_name' => 'read_more',
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'set Translate for "Read More" text.default : Read More', 'js_composer' )
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Show more text', 'js_composer' ),
							'param_name' => 'loadmore_text',
							'dependency' => array('element' => 'loadmore','value' => 'yes'),
							'description' => __( 'add Show more button text.Default:Show More', 'js_composer' )
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Extra class name', 'js_composer' ),
							'param_name' => 'svc_class',
							'holder' => 'div',
							'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Hide Excerpt', 'js_composer' ),
							'param_name' => 'dexcerpt',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'hide Excerpt content.', 'js_composer' ),
							'group' => __('Display Setting', 'js_composer')
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Hide Category', 'js_composer' ),
							'param_name' => 'dcategory',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'svc_type','value' => 'post_layout'),
							'description' => __( 'hide category content.', 'js_composer' ),
							'group' => __('Display Setting', 'js_composer')
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Hide meta data', 'js_composer' ),
							'param_name' => 'dmeta_data',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'svc_type','value' => 'post_layout'),
							'description' => __( 'hide meta content.like date,author,comment counter', 'js_composer' ),
							'group' => __('Display Setting', 'js_composer')
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Hide Social icon', 'js_composer' ),
							'param_name' => 'dsocial',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'svc_type','value' => 'post_layout'),
							'description' => __( 'hide social icon.', 'js_composer' ),
							'group' => __('Display Setting', 'js_composer')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Post Background Color', 'js_composer' ),
							'param_name' => 'pbgcolor',
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'set post background color.', 'js_composer' ),
							'group' => __('Color Setting', 'js_composer')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Post hover Background Color', 'js_composer' ),
							'param_name' => 'pbghcolor',
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'set post hover background color.', 'js_composer' ),
							'group' => __('Color Setting', 'js_composer')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Image below / top line color', 'js_composer' ),
							'param_name' => 'line_color',
							'description' => __( 'set Image below / top color.', 'js_composer' ),
							'dependency' => array('element' => 'skin_type','value' => array('s1','s2','s4')),
							'group' => __('Color Setting', 'js_composer')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Title Color', 'js_composer' ),
							'param_name' => 'tcolor',
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'set Title color.', 'js_composer' ),
							'group' => __('Color Setting', 'js_composer')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Title Hover Color', 'js_composer' ),
							'param_name' => 'thcolor',
							'dependency' => array('element' => 'svc_type','value' => array('post_layout','custom_layout')),
							'description' => __( 'set Title hover color.', 'js_composer' ),
							'group' => __('Color Setting', 'js_composer')
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Navigation and Pagination color', 'js_composer' ),
							'param_name' => 'car_navigation_color',
							'description' => __( 'Set Navigation and pagination color.', 'js_composer' ),
							'group' => __('Color Setting', 'js_composer')
						),
					)
				) );
				
			}

		}

	}
	
	
	//instantiate the class
	$svc_carousel_layout = new svc_carousel_layout;
}




if(!class_exists('svc_carousel_anything')){
	class svc_carousel_anything
	{
		function __construct()
		{
			add_action('admin_init',array($this,'svc_carousel_anything_init'));
			add_shortcode('svc_carousel_anything','svc_carousel_anything_shortcode');
		}
		function svc_carousel_anything_init(){
	
			if(function_exists('vc_map')){

				vc_map( array(		
					"name" => __('Anything HTML Carousel','js_composer'),		
					"base" => 'svc_carousel_anything',		
					"icon" => 'vc-animate-icon',		
					"category" => __('Carousel','js_composer'),
					"content_element" => true,
					"show_settings_on_create" => true,
					"as_parent" => array('only' => 'vc_column_text'),
					"description" => __( 'Set HTML Carousel','js_composer' ),
					"js_view" => 'VcColumnView',
					"params" => array(
						array(
							'type' => 'textfield',
							'heading' => __( 'Title', 'js_composer' ),
							'param_name' => 'title',
							'holder' => 'div',
							'description' => __( 'Enter Carousel title', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'Items Display', 'js_composer' ),
							'param_name' => 'car_display_item',
							'value' => '4',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'This variable allows you to set the maximum amount of items displayed at a time with the widest browser width', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsDesktop Display', 'js_composer' ),
							'param_name' => 'car_desktop_display_item',
							'value' => '4',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 1199px and 979px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsDesktopSmall Display', 'js_composer' ),
							'param_name' => 'car_desktopsmall_display_item',
							'value' => '3',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 979px and 768px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsTablet Display', 'js_composer' ),
							'param_name' => 'car_tablet_display_item',
							'value' => '2',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 768px and 479px', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'itemsMobile Display', 'js_composer' ),
							'param_name' => 'car_mobile_display_item',
							'value' => '1',
							'min' => 1,
							'max' => 100,
							'suffix' => '',
							'step' => 1,
							'description' => __( 'Display items between 479px and 200px', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Show pagination', 'js_composer' ),
							'param_name' => 'car_pagination',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'description' => __( 'Show pagination', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Show pagination Numbers', 'js_composer' ),
							'param_name' => 'car_pagination_num',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'dependency' => array('element' => 'car_pagination','value' => 'yes'),
							'description' => __( 'Show numbers inside pagination buttons.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'Hide navigation', 'js_composer' ),
							'param_name' => 'car_navigation',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'description' => __( 'Display "next" and "prev" buttons.', 'js_composer' )
						),
						array(
							'type' => 'checkbox',
							'heading' => __( 'AutoPlay', 'js_composer' ),
							'param_name' => 'car_autoplay',
							'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
							'description' => __( 'Set Slider Autoplay.', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'autoPlay Time', 'js_composer' ),
							'param_name' => 'car_autoplay_time',
							'value' => '5',
							'min' => 1,
							'max' => 100,
							'suffix' => 'seconds',
							'step' => 1,
							'dependency' => array('element' => 'car_autoplay','value' => 'yes'),
							'description' => __( 'Set Autoplay slider speed.', 'js_composer' )
						),
						array(
							'type' => 'num',
							'heading' => __( 'Margin', 'js_composer' ),
							'param_name' => 'car_margin',
							'value' => '10',
							'min' => 0,
							'max' => 1000,
							'suffix' => 'px',
							'step' => 1,
							'description' => __( 'Set Two Element Between Margin.', 'js_composer' )
						),
						array(
							'type' => 'dropdown',
							'heading' => __( 'Transition effect', 'js_composer' ),
							'param_name' => 'car_transition',
							'value' => array(
								__( 'None', 'js_composer' ) => '',
								__( 'fade', 'js_composer' ) => 'fade',
								__( 'backSlide', 'js_composer' ) => 'backSlide',
								__( 'goDown', 'js_composer' ) => 'goDown',
								__( 'fadeUp', 'js_composer' ) => 'fadeUp'
							),
							'dependency' => array('element' => 'car_display_item','value' => '1'),
							'description' => __( 'Add CSS3 transition style. Works only with one item on screen.', 'js_composer' )
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Extra class name', 'js_composer' ),
							'param_name' => 'svc_class',
							'holder' => 'div',
							'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
						),
						array(
							'type' => 'colorpicker',
							'heading' => __( 'Navigation and Pagination color', 'js_composer' ),
							'param_name' => 'car_navigation_color',
							'description' => __( 'Set Navigation and pagination color.', 'js_composer' ),
							'group' => __('Color Setting', 'js_composer')
						),
					)
				) );
				
			}
		}
		
	}
	
	
	//instantiate the class
	$svc_carousel_anything = new svc_carousel_anything;
}

add_action('admin_init','svc_carousel_anything_extends');
function svc_carousel_anything_extends(){
	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
		class WPBakeryShortCode_Svc_Carousel_Anything extends WPBakeryShortCodesContainer {
		}
	}
}

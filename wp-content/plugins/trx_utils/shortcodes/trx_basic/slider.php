<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('soapery_sc_slider_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_sc_slider_theme_setup' );
	function soapery_sc_slider_theme_setup() {
		add_action('soapery_action_shortcodes_list', 		'soapery_sc_slider_reg_shortcodes');
		if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
			add_action('soapery_action_shortcodes_list_vc','soapery_sc_slider_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('soapery_sc_slider')) {	
	function soapery_sc_slider($atts, $content=null){	
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "no",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => soapery_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
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

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && soapery_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		soapery_storage_set('sc_slider_data', array(
			'engine' => $engine,
            'width'  => soapery_prepare_css_value($width),
            'height' => soapery_prepare_css_value($height),
            'links'  => soapery_param_is_on($links),
            'bg_image' => soapery_get_theme_setting('slides_type')=='bg',
            'crop_image' => $crop
            )
        );
	
		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
		
		$class2 = soapery_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = soapery_get_css_dimensions_from_values($width);
		$hs = soapery_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
		
		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
		
		$output = (in_array($pagination, array('full', 'over')) 
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class2 ? ' '.esc_attr($class2) : '')
							. '"'
						. (!soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
						. ($hs ? ' style="'.esc_attr($hs).'"' : '') 
						.'>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (soapery_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (soapery_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (soapery_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. (soapery_storage_get_array('sc_slider_data', 'bg_image') ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) 
							? ($class2 ? ' '.esc_attr($class2) : '') . ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							: '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !soapery_param_is_off($animation) ? ' data-animation="'.esc_attr(soapery_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per_view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && soapery_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && soapery_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';
	
		soapery_enqueue_slider($engine);
	
		if ($engine=='revo') {
			if (!empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {
			
			$caption = '';
	
			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && soapery_storage_get_array('sc_slider_data', 'bg_image') ? ' style="'.esc_attr($hs).'"' : '')
				.'>';
	
			$content = do_shortcode($content);
			
			if (soapery_param_is_on($custom) && $content) {
				$output .= $content;
			} else {
				global $post;
		
				if (!empty($ids)) {
					$posts = explode(',', $ids);
					$count = count($posts);
				}
			
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);
		
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
		
				$args = soapery_query_add_sort_order($args, $orderby, $order);
				$args = soapery_query_add_filters($args, 'thumbs');
				$args = soapery_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
				$query = new WP_Query( $args );
	
				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating

				$post_rating = 'soapery_reviews_avg'.(soapery_get_theme_option('reviews_first')=='author' ? '' : '2');
				
				while ( $query->have_posts() ) { 
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (soapery_param_is_on($crop)) {
						$post_attachment = soapery_storage_get_array('sc_slider_data', 'bg_image')
							? soapery_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: soapery_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!soapery_storage_get_array('sc_slider_data', 'bg_image')) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';
	
					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(soapery_get_file_dir('templates/_parts/widgets-posts.php'))) {
							soapery_template_set_args('widgets-posts', array(
								'post_number' => $post_number,
								'post_rating' => $post_rating,
								'show_date' => $show_date,
								'show_image' => $show_image,
								'show_author' => $show_author,
								'show_links' => $show_links,
								'show_counters' => $show_counters
							));
							get_template_part(soapery_get_file_slug('templates/_parts/widgets-posts.php'));
							$output .= soapery_storage_get('widgets_posts_output');
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div' 
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. (soapery_storage_get_array('sc_slider_data', 'bg_image') ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>' 
						. (soapery_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!soapery_storage_get_array('sc_slider_data', 'bg_image') ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!soapery_param_is_off($titles)) {
						$post_hover_bg  = soapery_get_scheme_color('accent1');
						$post_bg = '';
						if ($post_hover_bg!='' && !soapery_is_inherit_option($post_hover_bg)) {
							$rgb = soapery_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = soapery_get_post_excerpt();
						if (soapery_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = soapery_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = soapery_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(soapery_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (soapery_get_custom_option('show_reviews')=='yes' && soapery_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = soapery_reviews_marks_to_display(get_post_meta($post_id, 'soapery_reviews_avg'.((soapery_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (soapery_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(soapery_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (soapery_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (soapery_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(soapery_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (soapery_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}
	
			$output .= '</div>';
			if ($engine=='swiper') {
				if (soapery_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (soapery_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}
		
		} else
			$output = '';
		
		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}
	
		return apply_filters('soapery_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	soapery_require_shortcode('trx_slider', 'soapery_sc_slider');
}


if (!function_exists('soapery_sc_slider_item')) {	
	function soapery_sc_slider_item($atts, $content=null) {
		if (soapery_in_shortcode_blogger()) return '';
		extract(soapery_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}

		$engine = soapery_storage_get_array('sc_slider_data', 'engine');
		$crop_image = soapery_storage_get_array('sc_slider_data', 'crop_image');
		$bg_image = soapery_storage_get_array('sc_slider_data', 'bg_image');
		$links = soapery_storage_get_array('sc_slider_data', 'links');
		$width = soapery_storage_get_array('sc_slider_data', 'width');
		$height = soapery_storage_get_array('sc_slider_data', 'height');
	
		if ($src && soapery_param_is_on($crop_image)) {
			$src = soapery_storage_get_array('sc_slider_data', 'bg_image')
				? soapery_get_resized_image_url($src, !empty($width) && soapery_strpos($width, '%')===false ? $width : null, !empty($height) && soapery_strpos($height, '%')===false ? $height : null)
				: soapery_get_resized_image_tag($src, !empty($width) && soapery_strpos($width, '%')===false ? $width : null, !empty($height) && soapery_strpos($height, '%')===false ? $height : null);
		} else if ($src && !$bg_image) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}
	
		$css .= ($bg_image ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($width)  ? 'width:'  . esc_attr($width)  . ';' : '')
				. (!empty($height) ? 'height:' . esc_attr($height) . ';' : '');
	
		$content = do_shortcode($content);
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($engine).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>' 
				. ($src && soapery_param_is_on($links) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$bg_image ? $src : $content)
				. ($src && soapery_param_is_on($links) ? '</a>' : '')
			. '</div>';
		return apply_filters('soapery_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	soapery_require_shortcode('trx_slider_item', 'soapery_sc_slider_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_slider_reg_shortcodes' ) ) {
	//add_action('soapery_action_shortcodes_list', 'soapery_sc_slider_reg_shortcodes');
	function soapery_sc_slider_reg_shortcodes() {
	
		soapery_sc_map("trx_slider", array(
			"title" => esc_html__("Slider", 'soapery'),
			"desc" => wp_kses_data( __("Insert slider into your post (page)", 'soapery') ),
			"decorate" => true,
			"container" => false,
			"params" => array_merge(array(
				"engine" => array(
					"title" => esc_html__("Slider engine", 'soapery'),
					"desc" => wp_kses_data( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'soapery') ),
					"value" => "swiper",
					"type" => "checklist",
					"options" => soapery_get_sc_param('sliders')
				),
				"align" => array(
					"title" => esc_html__("Float slider", 'soapery'),
					"desc" => wp_kses_data( __("Float slider to left or right side", 'soapery') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => soapery_get_sc_param('float')
				),
				"custom" => array(
					"title" => esc_html__("Custom slides", 'soapery'),
					"desc" => wp_kses_data( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'soapery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				)
				),
				function_exists('soapery_exists_revslider') && soapery_exists_revslider() ? array(
				"alias" => array(
					"title" => esc_html__("Revolution slider alias", 'soapery'),
					"desc" => wp_kses_data( __("Select Revolution slider to display", 'soapery') ),
					"dependency" => array(
						'engine' => array('revo')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"options" => soapery_get_sc_param('revo_sliders')
				)) : array(), array(
				"cat" => array(
					"title" => esc_html__("Swiper: Category list", 'soapery'),
					"desc" => wp_kses_data( __("Select category to show post's images. If empty - select posts from any category or from IDs list", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => soapery_array_merge(array(0 => esc_html__('- Select category -', 'soapery')), soapery_get_sc_param('categories'))
				),
				"count" => array(
					"title" => esc_html__("Swiper: Number of posts", 'soapery'),
					"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Swiper: Offset before select posts", 'soapery'),
					"desc" => wp_kses_data( __("Skip posts before select next part.", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Swiper: Post order by", 'soapery'),
					"desc" => wp_kses_data( __("Select desired posts sorting method", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "date",
					"type" => "select",
					"options" => soapery_get_sc_param('sorting')
				),
				"order" => array(
					"title" => esc_html__("Swiper: Post order", 'soapery'),
					"desc" => wp_kses_data( __("Select desired posts order", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
				),
				"ids" => array(
					"title" => esc_html__("Swiper: Post IDs list", 'soapery'),
					"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Swiper: Show slider controls", 'soapery'),
					"desc" => wp_kses_data( __("Show arrows inside slider", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				),
				"pagination" => array(
					"title" => esc_html__("Swiper: Show slider pagination", 'soapery'),
					"desc" => wp_kses_data( __("Show bullets for switch slides", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						'no'   => esc_html__('None', 'soapery'),
						'yes'  => esc_html__('Dots', 'soapery'), 
						'full' => esc_html__('Side Titles', 'soapery'),
						'over' => esc_html__('Over Titles', 'soapery')
					)
				),
				"titles" => array(
					"title" => esc_html__("Swiper: Show titles section", 'soapery'),
					"desc" => wp_kses_data( __("Show section with post's title and short post's description", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						"no"    => esc_html__('Not show', 'soapery'),
						"slide" => esc_html__('Show/Hide info', 'soapery'),
						"fixed" => esc_html__('Fixed info', 'soapery')
					)
				),
				"descriptions" => array(
					"title" => esc_html__("Swiper: Post descriptions", 'soapery'),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"desc" => wp_kses_data( __("Show post's excerpt max length (characters)", 'soapery') ),
					"value" => 0,
					"min" => 0,
					"max" => 1000,
					"step" => 10,
					"type" => "spinner"
				),
				"links" => array(
					"title" => esc_html__("Swiper: Post's title as link", 'soapery'),
					"desc" => wp_kses_data( __("Make links from post's titles", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				),
				"crop" => array(
					"title" => esc_html__("Swiper: Crop images", 'soapery'),
					"desc" => wp_kses_data( __("Crop images in each slide or live it unchanged", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				),
				"autoheight" => array(
					"title" => esc_html__("Swiper: Autoheight", 'soapery'),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
				),
				"slides_per_view" => array(
					"title" => esc_html__("Swiper: Slides per view", 'soapery'),
					"desc" => wp_kses_data( __("Slides per view showed in this slider", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 1,
					"min" => 1,
					"max" => 6,
					"step" => 1,
					"type" => "spinner"
				),
				"slides_space" => array(
					"title" => esc_html__("Swiper: Space between slides", 'soapery'),
					"desc" => wp_kses_data( __("Size of space (in px) between slides", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"step" => 10,
					"type" => "spinner"
				),
				"interval" => array(
					"title" => esc_html__("Swiper: Slides change interval", 'soapery'),
					"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'soapery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 5000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
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
			)),
			"children" => array(
				"name" => "trx_slider_item",
				"title" => esc_html__("Slide", 'soapery'),
				"desc" => wp_kses_data( __("Slider item", 'soapery') ),
				"container" => false,
				"params" => array(
					"src" => array(
						"title" => esc_html__("URL (source) for image file", 'soapery'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current slide", 'soapery') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"id" => soapery_get_sc_param('id'),
					"class" => soapery_get_sc_param('class'),
					"css" => soapery_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'soapery_sc_slider_reg_shortcodes_vc' ) ) {
	//add_action('soapery_action_shortcodes_list_vc', 'soapery_sc_slider_reg_shortcodes_vc');
	function soapery_sc_slider_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_slider",
			"name" => esc_html__("Slider", 'soapery'),
			"description" => wp_kses_data( __("Insert slider", 'soapery') ),
			"category" => esc_html__('Content', 'soapery'),
			'icon' => 'icon_trx_slider',
			"class" => "trx_sc_collection trx_sc_slider",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_slider_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "engine",
					"heading" => esc_html__("Engine", 'soapery'),
					"description" => wp_kses_data( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('sliders')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Float slider", 'soapery'),
					"description" => wp_kses_data( __("Float slider to left or right side", 'soapery') ),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom slides", 'soapery'),
					"description" => wp_kses_data( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'soapery') ),
					"class" => "",
					"value" => array(esc_html__('Custom slides', 'soapery') => 'yes'),
					"type" => "checkbox"
				)
				),
				function_exists('soapery_exists_revslider') && soapery_exists_revslider() ? array(
				array(
					"param_name" => "alias",
					"heading" => esc_html__("Revolution slider alias", 'soapery'),
					"description" => wp_kses_data( __("Select Revolution slider to display", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'engine',
						'value' => array('revo')
					),
					"value" => array_flip(soapery_array_merge(array('none' => esc_html__('- Select slider -', 'soapery')), soapery_get_sc_param('revo_sliders'))),
					"type" => "dropdown"
				)) : array(), array(
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", 'soapery'),
					"description" => wp_kses_data( __("Select category. If empty - show posts from any category or from IDs list", 'soapery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(soapery_array_merge(array(0 => esc_html__('- Select category -', 'soapery')), soapery_get_sc_param('categories'))),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Swiper: Number of posts", 'soapery'),
					"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'soapery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Swiper: Offset before select posts", 'soapery'),
					"description" => wp_kses_data( __("Skip posts before select next part.", 'soapery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Swiper: Post sorting", 'soapery'),
					"description" => wp_kses_data( __("Select desired posts sorting method", 'soapery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('sorting')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Swiper: Post order", 'soapery'),
					"description" => wp_kses_data( __("Select desired posts order", 'soapery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(soapery_get_sc_param('ordering')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Swiper: Post IDs list", 'soapery'),
					"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'soapery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Swiper: Show slider controls", 'soapery'),
					"description" => wp_kses_data( __("Show arrows inside slider", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Show controls', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pagination",
					"heading" => esc_html__("Swiper: Show slider pagination", 'soapery'),
					"description" => wp_kses_data( __("Show bullets or titles to switch slides", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"std" => "no",
					"value" => array(
							esc_html__('None', 'soapery') => 'no',
							esc_html__('Dots', 'soapery') => 'yes', 
							esc_html__('Side Titles', 'soapery') => 'full',
							esc_html__('Over Titles', 'soapery') => 'over'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "titles",
					"heading" => esc_html__("Swiper: Show titles section", 'soapery'),
					"description" => wp_kses_data( __("Show section with post's title and short post's description", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(
							esc_html__('Not show', 'soapery') => "no",
							esc_html__('Show/Hide info', 'soapery') => "slide",
							esc_html__('Fixed info', 'soapery') => "fixed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "descriptions",
					"heading" => esc_html__("Swiper: Post descriptions", 'soapery'),
					"description" => wp_kses_data( __("Show post's excerpt max length (characters)", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Swiper: Post's title as link", 'soapery'),
					"description" => wp_kses_data( __("Make links from post's titles", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Titles as a links', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "crop",
					"heading" => esc_html__("Swiper: Crop images", 'soapery'),
					"description" => wp_kses_data( __("Crop images in each slide or live it unchanged", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Crop images', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Swiper: Autoheight", 'soapery'),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Autoheight', 'soapery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "slides_per_view",
					"heading" => esc_html__("Swiper: Slides per view", 'soapery'),
					"description" => wp_kses_data( __("Slides per view showed in this slider", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Swiper: Space between slides", 'soapery'),
					"description" => wp_kses_data( __("Size of space (in px) between slides", 'soapery') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Swiper: Slides change interval", 'soapery'),
					"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'soapery') ),
					"group" => esc_html__('Details', 'soapery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "5000",
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_slider_item",
			"name" => esc_html__("Slide", 'soapery'),
			"description" => wp_kses_data( __("Slider item - single slide", 'soapery') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_slider_item',
			"class" => "trx_sc_single trx_sc_slider_item",
			"as_child" => array('only' => 'trx_slider'),
			"as_parent" => array('except' => 'trx_slider'),
			"params" => array(
				array(
					"param_name" => "src",
					"heading" => esc_html__("URL (source) for image file", 'soapery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current slide", 'soapery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				soapery_get_vc_param('id'),
				soapery_get_vc_param('class'),
				soapery_get_vc_param('css')
			)
		) );
		
		class WPBakeryShortCode_Trx_Slider extends SOAPERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Slider_Item extends SOAPERY_VC_ShortCodeSingle {}
	}
}
?>
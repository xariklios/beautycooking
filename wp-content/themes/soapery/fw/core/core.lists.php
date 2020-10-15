<?php
/**
 * Soapery Framework: return lists
 *
 * @package soapery
 * @since soapery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'soapery_get_list_styles' ) ) {
	function soapery_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'soapery'), $i);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'soapery_get_list_margins' ) ) {
	function soapery_get_list_margins($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'soapery'),
				'tiny'		=> esc_html__('Tiny',		'soapery'),
				'small'		=> esc_html__('Small',		'soapery'),
				'medium'	=> esc_html__('Medium',		'soapery'),
				'large'		=> esc_html__('Large',		'soapery'),
				'huge'		=> esc_html__('Huge',		'soapery'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'soapery'),
				'small-'	=> esc_html__('Small (negative)',	'soapery'),
				'medium-'	=> esc_html__('Medium (negative)',	'soapery'),
				'large-'	=> esc_html__('Large (negative)',	'soapery'),
				'huge-'		=> esc_html__('Huge (negative)',	'soapery')
				);
			$list = apply_filters('soapery_filter_list_margins', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'soapery_get_list_animations' ) ) {
	function soapery_get_list_animations($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'soapery'),
				'bounced'		=> esc_html__('Bounced',		'soapery'),
				'flash'			=> esc_html__('Flash',		'soapery'),
				'flip'			=> esc_html__('Flip',		'soapery'),
				'pulse'			=> esc_html__('Pulse',		'soapery'),
				'rubberBand'	=> esc_html__('Rubber Band',	'soapery'),
				'shake'			=> esc_html__('Shake',		'soapery'),
				'swing'			=> esc_html__('Swing',		'soapery'),
				'tada'			=> esc_html__('Tada',		'soapery'),
				'wobble'		=> esc_html__('Wobble',		'soapery')
				);
			$list = apply_filters('soapery_filter_list_animations', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'soapery_get_list_line_styles' ) ) {
	function soapery_get_list_line_styles($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'soapery'),
				'dashed'=> esc_html__('Dashed', 'soapery'),
				'dotted'=> esc_html__('Dotted', 'soapery'),
				'double'=> esc_html__('Double', 'soapery'),
				'image'	=> esc_html__('Image', 'soapery')
				);
			$list = apply_filters('soapery_filter_list_line_styles', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'soapery_get_list_animations_in' ) ) {
	function soapery_get_list_animations_in($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'soapery'),
				'bounceIn'			=> esc_html__('Bounce In',			'soapery'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'soapery'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'soapery'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'soapery'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'soapery'),
				'fadeIn'			=> esc_html__('Fade In',			'soapery'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'soapery'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'soapery'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'soapery'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'soapery'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'soapery'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'soapery'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'soapery'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'soapery'),
				'flipInX'			=> esc_html__('Flip In X',			'soapery'),
				'flipInY'			=> esc_html__('Flip In Y',			'soapery'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'soapery'),
				'rotateIn'			=> esc_html__('Rotate In',			'soapery'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','soapery'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'soapery'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'soapery'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','soapery'),
				'rollIn'			=> esc_html__('Roll In',			'soapery'),
				'slideInUp'			=> esc_html__('Slide In Up',		'soapery'),
				'slideInDown'		=> esc_html__('Slide In Down',		'soapery'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'soapery'),
				'slideInRight'		=> esc_html__('Slide In Right',		'soapery'),
				'zoomIn'			=> esc_html__('Zoom In',			'soapery'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'soapery'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'soapery'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'soapery'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'soapery')
				);
			$list = apply_filters('soapery_filter_list_animations_in', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'soapery_get_list_animations_out' ) ) {
	function soapery_get_list_animations_out($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'soapery'),
				'bounceOut'			=> esc_html__('Bounce Out',			'soapery'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'soapery'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'soapery'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'soapery'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'soapery'),
				'fadeOut'			=> esc_html__('Fade Out',			'soapery'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'soapery'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'soapery'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'soapery'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'soapery'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'soapery'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'soapery'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'soapery'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'soapery'),
				'flipOutX'			=> esc_html__('Flip Out X',			'soapery'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'soapery'),
				'hinge'				=> esc_html__('Hinge Out',			'soapery'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'soapery'),
				'rotateOut'			=> esc_html__('Rotate Out',			'soapery'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'soapery'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'soapery'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'soapery'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'soapery'),
				'rollOut'			=> esc_html__('Roll Out',		'soapery'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'soapery'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'soapery'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'soapery'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'soapery'),
				'zoomOut'			=> esc_html__('Zoom Out',			'soapery'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'soapery'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'soapery'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'soapery'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'soapery')
				);
			$list = apply_filters('soapery_filter_list_animations_out', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('soapery_get_animation_classes')) {
	function soapery_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return soapery_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!soapery_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'soapery_get_list_categories' ) ) {
	function soapery_get_list_categories($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'soapery_get_list_terms' ) ) {
	function soapery_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = soapery_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = soapery_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'soapery_get_list_posts_types' ) ) {
	function soapery_get_list_posts_types($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_posts_types'))=='') {
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'soapery');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = soapery_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$list = apply_filters('soapery_filter_list_post_types', array());
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'soapery_get_list_posts' ) ) {
	function soapery_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = soapery_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'soapery');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set($hash, $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'soapery_get_list_pages' ) ) {
	function soapery_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return soapery_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'soapery_get_list_users' ) ) {
	function soapery_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = soapery_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'soapery');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_users', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'soapery_get_list_sliders' ) ) {
	function soapery_get_list_sliders($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'soapery')
			);
			$list = apply_filters('soapery_filter_list_sliders', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'soapery_get_list_slider_controls' ) ) {
	function soapery_get_list_slider_controls($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'soapery'),
				'side'		=> esc_html__('Side', 'soapery'),
				'bottom'	=> esc_html__('Bottom', 'soapery'),
				'pagination'=> esc_html__('Pagination', 'soapery')
				);
			$list = apply_filters('soapery_filter_list_slider_controls', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'soapery_get_slider_controls_classes' ) ) {
	function soapery_get_slider_controls_classes($controls) {
		if (soapery_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'soapery_get_list_popup_engines' ) ) {
	function soapery_get_list_popup_engines($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'soapery'),
				"magnific"	=> esc_html__("Magnific popup", 'soapery')
				);
			$list = apply_filters('soapery_filter_list_popup_engines', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'soapery_get_list_menus' ) ) {
	function soapery_get_list_menus($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'soapery');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'soapery_get_list_sidebars' ) ) {
	function soapery_get_list_sidebars($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_sidebars'))=='') {
			if (($list = soapery_storage_get('registered_sidebars'))=='') $list = array();
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'soapery_get_list_sidebars_positions' ) ) {
	function soapery_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'soapery'),
				'left'  => esc_html__('Left',  'soapery'),
				'right' => esc_html__('Right', 'soapery')
				);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'soapery_get_sidebar_class' ) ) {
	function soapery_get_sidebar_class() {
		$sb_main = soapery_get_custom_option('show_sidebar_main');
		$sb_outer = soapery_get_custom_option('show_sidebar_outer');
		return (soapery_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (soapery_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_body_styles' ) ) {
	function soapery_get_list_body_styles($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'soapery'),
				'wide'	=> esc_html__('Wide',		'soapery')
				);
			if (soapery_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'soapery');
				$list['fullscreen']	= esc_html__('Fullscreen',	'soapery');
			}
			$list = apply_filters('soapery_filter_list_body_styles', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'soapery_get_list_skins' ) ) {
	function soapery_get_list_skins($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_skins'))=='') {
			$list = soapery_get_list_folders("skins");
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'soapery_get_list_themes' ) ) {
	function soapery_get_list_themes($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_themes'))=='') {
			$list = soapery_get_list_files("css/themes");
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'soapery_get_list_templates' ) ) {
	function soapery_get_list_templates($mode='') {
		if (($list = soapery_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = soapery_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: soapery_strtoproper($v['layout'])
										);
				}
			}
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_templates_blog' ) ) {
	function soapery_get_list_templates_blog($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_templates_blog'))=='') {
			$list = soapery_get_list_templates('blog');
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_templates_blogger' ) ) {
	function soapery_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_templates_blogger'))=='') {
			$list = soapery_array_merge(soapery_get_list_templates('blogger'), soapery_get_list_templates('blog'));
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_templates_single' ) ) {
	function soapery_get_list_templates_single($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_templates_single'))=='') {
			$list = soapery_get_list_templates('single');
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_templates_header' ) ) {
	function soapery_get_list_templates_header($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_templates_header'))=='') {
			$list = soapery_get_list_templates('header');
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_templates_forms' ) ) {
	function soapery_get_list_templates_forms($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_templates_forms'))=='') {
			$list = soapery_get_list_templates('forms');
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_article_styles' ) ) {
	function soapery_get_list_article_styles($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'soapery'),
				"stretch" => esc_html__('Stretch', 'soapery')
				);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'soapery_get_list_post_formats_filters' ) ) {
	function soapery_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'soapery'),
				"thumbs"  => esc_html__('With thumbs', 'soapery'),
				"reviews" => esc_html__('With reviews', 'soapery'),
				"video"   => esc_html__('With videos', 'soapery'),
				"audio"   => esc_html__('With audios', 'soapery'),
				"gallery" => esc_html__('With galleries', 'soapery')
				);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'soapery_get_list_portfolio_filters' ) ) {
	function soapery_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'soapery'),
				"tags"		=> esc_html__('Tags', 'soapery'),
				"categories"=> esc_html__('Categories', 'soapery')
				);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_hovers' ) ) {
	function soapery_get_list_hovers($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'soapery');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'soapery');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'soapery');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'soapery');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'soapery');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'soapery');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'soapery');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'soapery');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'soapery');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'soapery');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'soapery');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'soapery');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'soapery');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'soapery');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'soapery');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'soapery');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'soapery');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'soapery');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'soapery');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'soapery');
			$list['square effect1']  = esc_html__('Square Effect 1',  'soapery');
			$list['square effect2']  = esc_html__('Square Effect 2',  'soapery');
			$list['square effect3']  = esc_html__('Square Effect 3',  'soapery');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'soapery');
			$list['square effect5']  = esc_html__('Square Effect 5',  'soapery');
			$list['square effect6']  = esc_html__('Square Effect 6',  'soapery');
			$list['square effect7']  = esc_html__('Square Effect 7',  'soapery');
			$list['square effect8']  = esc_html__('Square Effect 8',  'soapery');
			$list['square effect9']  = esc_html__('Square Effect 9',  'soapery');
			$list['square effect10'] = esc_html__('Square Effect 10',  'soapery');
			$list['square effect11'] = esc_html__('Square Effect 11',  'soapery');
			$list['square effect12'] = esc_html__('Square Effect 12',  'soapery');
			$list['square effect13'] = esc_html__('Square Effect 13',  'soapery');
			$list['square effect14'] = esc_html__('Square Effect 14',  'soapery');
			$list['square effect15'] = esc_html__('Square Effect 15',  'soapery');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'soapery');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'soapery');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'soapery');
			$list['square effect_more']  = esc_html__('Square Effect More',  'soapery');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'soapery');
			$list = apply_filters('soapery_filter_portfolio_hovers', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'soapery_get_list_blog_counters' ) ) {
	function soapery_get_list_blog_counters($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'soapery'),
				'likes'		=> esc_html__('Likes', 'soapery'),
				'rating'	=> esc_html__('Rating', 'soapery'),
				'comments'	=> esc_html__('Comments', 'soapery')
				);
			$list = apply_filters('soapery_filter_list_blog_counters', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'soapery_get_list_alter_sizes' ) ) {
	function soapery_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'soapery'),
					'1_2' => esc_html__('1x2', 'soapery'),
					'2_1' => esc_html__('2x1', 'soapery'),
					'2_2' => esc_html__('2x2', 'soapery'),
					'1_3' => esc_html__('1x3', 'soapery'),
					'2_3' => esc_html__('2x3', 'soapery'),
					'3_1' => esc_html__('3x1', 'soapery'),
					'3_2' => esc_html__('3x2', 'soapery'),
					'3_3' => esc_html__('3x3', 'soapery')
					);
			$list = apply_filters('soapery_filter_portfolio_alter_sizes', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'soapery_get_list_hovers_directions' ) ) {
	function soapery_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'soapery'),
				'right_to_left' => esc_html__('Right to Left',  'soapery'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'soapery'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'soapery'),
				'scale_up'      => esc_html__('Scale Up',  'soapery'),
				'scale_down'    => esc_html__('Scale Down',  'soapery'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'soapery'),
				'from_left_and_right' => esc_html__('From Left and Right',  'soapery'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'soapery')
			);
			$list = apply_filters('soapery_filter_portfolio_hovers_directions', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'soapery_get_list_label_positions' ) ) {
	function soapery_get_list_label_positions($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'soapery'),
				'bottom'	=> esc_html__('Bottom',		'soapery'),
				'left'		=> esc_html__('Left',		'soapery'),
				'over'		=> esc_html__('Over',		'soapery')
			);
			$list = apply_filters('soapery_filter_label_positions', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'soapery_get_list_bg_image_positions' ) ) {
	function soapery_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'soapery'),
				'center top'   => esc_html__("Center Top", 'soapery'),
				'right top'    => esc_html__("Right Top", 'soapery'),
				'left center'  => esc_html__("Left Center", 'soapery'),
				'center center'=> esc_html__("Center Center", 'soapery'),
				'right center' => esc_html__("Right Center", 'soapery'),
				'left bottom'  => esc_html__("Left Bottom", 'soapery'),
				'center bottom'=> esc_html__("Center Bottom", 'soapery'),
				'right bottom' => esc_html__("Right Bottom", 'soapery')
			);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'soapery_get_list_bg_image_repeats' ) ) {
	function soapery_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'soapery'),
				'repeat-x'	=> esc_html__('Repeat X', 'soapery'),
				'repeat-y'	=> esc_html__('Repeat Y', 'soapery'),
				'no-repeat'	=> esc_html__('No Repeat', 'soapery')
			);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'soapery_get_list_bg_image_attachments' ) ) {
	function soapery_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'soapery'),
				'fixed'		=> esc_html__('Fixed', 'soapery'),
				'local'		=> esc_html__('Local', 'soapery')
			);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'soapery_get_list_bg_tints' ) ) {
	function soapery_get_list_bg_tints($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'soapery'),
				'light'	=> esc_html__('Light', 'soapery'),
				'dark'	=> esc_html__('Dark', 'soapery')
			);
			$list = apply_filters('soapery_filter_bg_tints', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'soapery_get_list_field_types' ) ) {
	function soapery_get_list_field_types($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'soapery'),
				'textarea' => esc_html__('Text Area','soapery'),
				'password' => esc_html__('Password',  'soapery'),
				'radio'    => esc_html__('Radio',  'soapery'),
				'checkbox' => esc_html__('Checkbox',  'soapery'),
				'select'   => esc_html__('Select',  'soapery'),
				'date'     => esc_html__('Date','soapery'),
				'time'     => esc_html__('Time','soapery'),
				'button'   => esc_html__('Button','soapery')
			);
			$list = apply_filters('soapery_filter_field_types', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'soapery_get_list_googlemap_styles' ) ) {
	function soapery_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'soapery')
			);
			$list = apply_filters('soapery_filter_googlemap_styles', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'soapery_get_list_icons' ) ) {
	function soapery_get_list_icons($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_icons'))=='') {
			$list = soapery_parse_icons_classes(soapery_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'soapery_get_list_socials' ) ) {
	function soapery_get_list_socials($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_socials'))=='') {
			$list = soapery_get_list_files("images/socials", "png");
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'soapery_get_list_flags' ) ) {
	function soapery_get_list_flags($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_flags'))=='') {
			$list = soapery_get_list_files("images/flags", "png");
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'soapery_get_list_yesno' ) ) {
	function soapery_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'soapery'),
			'no'  => esc_html__("No", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'soapery_get_list_onoff' ) ) {
	function soapery_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'soapery'),
			"off" => esc_html__("Off", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'soapery_get_list_showhide' ) ) {
	function soapery_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'soapery'),
			"hide" => esc_html__("Hide", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'soapery_get_list_orderings' ) ) {
	function soapery_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'soapery'),
			"desc" => esc_html__("Descending", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'soapery_get_list_directions' ) ) {
	function soapery_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'soapery'),
			"vertical" => esc_html__("Vertical", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'soapery_get_list_shapes' ) ) {
	function soapery_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'soapery'),
			"square" => esc_html__("Square", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'soapery_get_list_sizes' ) ) {
	function soapery_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'soapery'),
			"small"  => esc_html__("Small", 'soapery'),
			"medium" => esc_html__("Medium", 'soapery'),
			"large"  => esc_html__("Large", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'soapery_get_list_controls' ) ) {
	function soapery_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'soapery'),
			"side" => esc_html__("Side", 'soapery'),
			"bottom" => esc_html__("Bottom", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'soapery_get_list_floats' ) ) {
	function soapery_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'soapery'),
			"left" => esc_html__("Float Left", 'soapery'),
			"right" => esc_html__("Float Right", 'soapery')
		);
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'soapery_get_list_alignments' ) ) {
	function soapery_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'soapery'),
			"left" => esc_html__("Left", 'soapery'),
			"center" => esc_html__("Center", 'soapery'),
			"right" => esc_html__("Right", 'soapery')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'soapery');
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'soapery_get_list_hpos' ) ) {
	function soapery_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'soapery');
		if ($center) $list['center'] = esc_html__("Center", 'soapery');
		$list['right'] = esc_html__("Right", 'soapery');
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'soapery_get_list_vpos' ) ) {
	function soapery_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'soapery');
		if ($center) $list['center'] = esc_html__("Center", 'soapery');
		$list['bottom'] = esc_html__("Bottom", 'soapery');
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'soapery_get_list_sortings' ) ) {
	function soapery_get_list_sortings($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'soapery'),
				"title" => esc_html__("Alphabetically", 'soapery'),
				"views" => esc_html__("Popular (views count)", 'soapery'),
				"comments" => esc_html__("Most commented (comments count)", 'soapery'),
				"author_rating" => esc_html__("Author rating", 'soapery'),
				"users_rating" => esc_html__("Visitors (users) rating", 'soapery'),
				"random" => esc_html__("Random", 'soapery')
			);
			$list = apply_filters('soapery_filter_list_sortings', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'soapery_get_list_columns' ) ) {
	function soapery_get_list_columns($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'soapery'),
				"1_1" => esc_html__("100%", 'soapery'),
				"1_2" => esc_html__("1/2", 'soapery'),
				"1_3" => esc_html__("1/3", 'soapery'),
				"2_3" => esc_html__("2/3", 'soapery'),
				"1_4" => esc_html__("1/4", 'soapery'),
				"3_4" => esc_html__("3/4", 'soapery'),
				"1_5" => esc_html__("1/5", 'soapery'),
				"2_5" => esc_html__("2/5", 'soapery'),
				"3_5" => esc_html__("3/5", 'soapery'),
				"4_5" => esc_html__("4/5", 'soapery'),
				"1_6" => esc_html__("1/6", 'soapery'),
				"5_6" => esc_html__("5/6", 'soapery'),
				"1_7" => esc_html__("1/7", 'soapery'),
				"2_7" => esc_html__("2/7", 'soapery'),
				"3_7" => esc_html__("3/7", 'soapery'),
				"4_7" => esc_html__("4/7", 'soapery'),
				"5_7" => esc_html__("5/7", 'soapery'),
				"6_7" => esc_html__("6/7", 'soapery'),
				"1_8" => esc_html__("1/8", 'soapery'),
				"3_8" => esc_html__("3/8", 'soapery'),
				"5_8" => esc_html__("5/8", 'soapery'),
				"7_8" => esc_html__("7/8", 'soapery'),
				"1_9" => esc_html__("1/9", 'soapery'),
				"2_9" => esc_html__("2/9", 'soapery'),
				"4_9" => esc_html__("4/9", 'soapery'),
				"5_9" => esc_html__("5/9", 'soapery'),
				"7_9" => esc_html__("7/9", 'soapery'),
				"8_9" => esc_html__("8/9", 'soapery'),
				"1_10"=> esc_html__("1/10", 'soapery'),
				"3_10"=> esc_html__("3/10", 'soapery'),
				"7_10"=> esc_html__("7/10", 'soapery'),
				"9_10"=> esc_html__("9/10", 'soapery'),
				"1_11"=> esc_html__("1/11", 'soapery'),
				"2_11"=> esc_html__("2/11", 'soapery'),
				"3_11"=> esc_html__("3/11", 'soapery'),
				"4_11"=> esc_html__("4/11", 'soapery'),
				"5_11"=> esc_html__("5/11", 'soapery'),
				"6_11"=> esc_html__("6/11", 'soapery'),
				"7_11"=> esc_html__("7/11", 'soapery'),
				"8_11"=> esc_html__("8/11", 'soapery'),
				"9_11"=> esc_html__("9/11", 'soapery'),
				"10_11"=> esc_html__("10/11", 'soapery'),
				"1_12"=> esc_html__("1/12", 'soapery'),
				"5_12"=> esc_html__("5/12", 'soapery'),
				"7_12"=> esc_html__("7/12", 'soapery'),
				"10_12"=> esc_html__("10/12", 'soapery'),
				"11_12"=> esc_html__("11/12", 'soapery')
			);
			$list = apply_filters('soapery_filter_list_columns', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'soapery_get_list_dedicated_locations' ) ) {
	function soapery_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'soapery'),
				"center"  => esc_html__('Above the text of the post', 'soapery'),
				"left"    => esc_html__('To the left the text of the post', 'soapery'),
				"right"   => esc_html__('To the right the text of the post', 'soapery'),
				"alter"   => esc_html__('Alternates for each post', 'soapery')
			);
			$list = apply_filters('soapery_filter_list_dedicated_locations', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'soapery_get_post_format_name' ) ) {
	function soapery_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'soapery') : esc_html__('galleries', 'soapery');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'soapery') : esc_html__('videos', 'soapery');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'soapery') : esc_html__('audios', 'soapery');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'soapery') : esc_html__('images', 'soapery');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'soapery') : esc_html__('quotes', 'soapery');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'soapery') : esc_html__('links', 'soapery');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'soapery') : esc_html__('statuses', 'soapery');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'soapery') : esc_html__('asides', 'soapery');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'soapery') : esc_html__('chats', 'soapery');
		else						$name = $single ? esc_html__('standard', 'soapery') : esc_html__('standards', 'soapery');
		return apply_filters('soapery_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'soapery_get_post_format_icon' ) ) {
	function soapery_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('soapery_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'soapery_get_list_fonts_styles' ) ) {
	function soapery_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','soapery'),
				'u' => esc_html__('U', 'soapery')
			);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'soapery_get_list_fonts' ) ) {
	function soapery_get_list_fonts($prepend_inherit=false) {
		if (($list = soapery_storage_get('list_fonts'))=='') {
			$list = array();
			$list = soapery_array_merge($list, soapery_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>soapery_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = soapery_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('soapery_filter_list_fonts', $list);
			if (soapery_get_theme_setting('use_list_cache')) soapery_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? soapery_array_merge(array('inherit' => esc_html__("Inherit", 'soapery')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'soapery_get_list_font_faces' ) ) {
	function soapery_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = soapery_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? soapery_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? soapery_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'soapery').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>
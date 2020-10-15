<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('soapery_woocommerce_theme_setup')) {
	add_action( 'soapery_action_before_init_theme', 'soapery_woocommerce_theme_setup', 1 );
	function soapery_woocommerce_theme_setup() {

		if (soapery_exists_woocommerce()) {
			
			add_theme_support( 'woocommerce' );
			// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
			add_theme_support( 'wc-product-gallery-zoom' );
			// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
			add_theme_support( 'wc-product-gallery-slider' );
			// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
			add_theme_support( 'wc-product-gallery-lightbox' );
			
			add_action('soapery_action_add_styles', 				'soapery_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('soapery_filter_get_blog_type',				'soapery_woocommerce_get_blog_type', 9, 2);
			add_filter('soapery_filter_get_blog_title',			'soapery_woocommerce_get_blog_title', 9, 2);
			add_filter('soapery_filter_get_current_taxonomy',		'soapery_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('soapery_filter_is_taxonomy',				'soapery_woocommerce_is_taxonomy', 9, 2);
			add_filter('soapery_filter_get_stream_page_title',		'soapery_woocommerce_get_stream_page_title', 9, 2);
			add_filter('soapery_filter_get_stream_page_link',		'soapery_woocommerce_get_stream_page_link', 9, 2);
			add_filter('soapery_filter_get_stream_page_id',		'soapery_woocommerce_get_stream_page_id', 9, 2);
			add_filter('soapery_filter_detect_inheritance_key',	'soapery_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('soapery_filter_detect_template_page_id',	'soapery_woocommerce_detect_template_page_id', 9, 2);
			add_filter('soapery_filter_orderby_need',				'soapery_woocommerce_orderby_need', 9, 2);

			add_filter('soapery_filter_show_post_navi', 			'soapery_woocommerce_show_post_navi');
			add_filter('soapery_filter_list_post_types', 			'soapery_woocommerce_list_post_types');

			add_action('soapery_action_shortcodes_list', 			'soapery_woocommerce_reg_shortcodes', 20);
			if (function_exists('soapery_exists_visual_composer') && soapery_exists_visual_composer())
				add_action('soapery_action_shortcodes_list_vc',	'soapery_woocommerce_reg_shortcodes_vc', 20);

			if (is_admin()) {
				add_filter( 'soapery_filter_importer_options',				'soapery_woocommerce_importer_set_options' );
				add_action( 'soapery_action_importer_after_import_posts',	'soapery_woocommerce_importer_after_import_posts', 10, 1 );
				add_action( 'soapery_action_importer_params',				'soapery_woocommerce_importer_show_params', 10, 1 );
				add_action( 'soapery_action_importer_import',				'soapery_woocommerce_importer_import', 10, 2 );
				add_action( 'soapery_action_importer_import_fields',		'soapery_woocommerce_importer_import_fields', 10, 1 );
				add_action( 'soapery_action_importer_export',				'soapery_woocommerce_importer_export', 10, 1 );
				add_action( 'soapery_action_importer_export_fields',		'soapery_woocommerce_importer_export_fields', 10, 1 );
			}
		}

		if (is_admin()) {
			add_filter( 'soapery_filter_importer_required_plugins',		'soapery_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'soapery_filter_required_plugins',					'soapery_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'soapery_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_woocommerce_settings_theme_setup2', 3 );
	function soapery_woocommerce_settings_theme_setup2() {
		if (soapery_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			soapery_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => 'blog-woocommerce',		// This params must be empty
				'single_template' => 'single-woocommerce',		// They are specified to enable separate settings for blog and single wooc
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			soapery_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'soapery'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'soapery'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'soapery'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'soapery'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'soapery'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'soapery'),
						'list' => esc_html__('List', 'soapery')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'soapery'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'soapery'),
					"std" => "yes",
					"options" => soapery_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'soapery'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'soapery'),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'soapery'),
					"desc" => esc_html__('Show currency selector in the user menu', 'soapery'),
					"std" => "yes",
					"options" => soapery_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'soapery'),
					"desc" => esc_html__('Show cart button in the user menu', 'soapery'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'soapery'),
						'always' => esc_html__('Always', 'soapery'),
						'shop'   => esc_html__('Only on shop pages', 'soapery')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'soapery'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'soapery'),
					"std" => "no",
					"options" => soapery_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('soapery_woocommerce_theme_setup3')) {
	add_action( 'soapery_action_after_init_theme', 'soapery_woocommerce_theme_setup3' );
	function soapery_woocommerce_theme_setup3() {

		if (soapery_exists_woocommerce()) {
			add_action(    'woocommerce_before_subcategory_title',		'soapery_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'soapery_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'soapery_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'soapery_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'soapery_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'soapery_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'soapery_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'soapery_woocommerce_after_subcategory_title', 10 );

			add_action(    'the_title',									'soapery_woocommerce_the_title');

			// Wrap category title into link
			remove_action( 'woocommerce_shop_loop_subcategory_title',   'woocommerce_template_loop_category_title', 10 );
			add_action(    'woocommerce_shop_loop_subcategory_title',   'soapery_woocommerce_shop_loop_subcategory_title', 9, 1);

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
            // Replace product item title tag from h2 to h3
            remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
            add_action( 'woocommerce_shop_loop_item_title',    'soapery_woocommerce_template_loop_product_title', 10 );
		}

		if (soapery_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'soapery_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'soapery_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'soapery_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'soapery_woocommerce_show_product_title', 5 );

            remove_action(  'woocommerce_single_product_summary',       'woocommerce_template_single_excerpt', 20);
            add_action(    'woocommerce_single_product_summary',		'soapery_template_single_excerpt', 20 );

			add_action(    'woocommerce_before_shop_loop', 				'soapery_woocommerce_before_shop_loop', 10 );

			//remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			//Handler of add_action(    'woocommerce_after_shop_loop',				'soapery_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'soapery_woocommerce_show_product_id', 10);

            if (soapery_param_is_on(soapery_get_custom_option('show_post_related'))) {
                add_filter('woocommerce_output_related_products_args', 'soapery_woocommerce_output_related_products_args');
                add_filter('woocommerce_related_products_args', 'soapery_woocommerce_related_products_args');
            } else {
                remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
            }

			add_filter(    'woocommerce_product_thumbnails_columns',	'soapery_woocommerce_product_thumbnails_columns' );

			add_filter(    'get_product_search_form',					'soapery_woocommerce_get_product_search_form' );


			add_filter(    'post_class',								'soapery_woocommerce_loop_shop_columns_class' );
			add_filter(    'product_cat_class',							'soapery_woocommerce_loop_shop_columns_class', 10, 3 );
			
			soapery_enqueue_popup();
		}
	}
}

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

// Check if WooCommerce installed and activated
if ( !function_exists( 'soapery_exists_woocommerce' ) ) {
	function soapery_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'soapery_is_woocommerce_page' ) ) {
	function soapery_is_woocommerce_page() {
		$rez = false;
		if (soapery_exists_woocommerce()) {
			if (!soapery_storage_empty('pre_query')) {
				$id = soapery_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = soapery_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| soapery_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| soapery_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| soapery_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'soapery_woocommerce_detect_inheritance_key' ) ) {
	//Handler of add_filter('soapery_filter_detect_inheritance_key',	'soapery_woocommerce_detect_inheritance_key', 9, 1);
	function soapery_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return soapery_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'soapery_woocommerce_detect_template_page_id' ) ) {
	//Handler of add_filter('soapery_filter_detect_template_page_id',	'soapery_woocommerce_detect_template_page_id', 9, 2);
	function soapery_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'soapery_woocommerce_get_blog_type' ) ) {
	//Handler of add_filter('soapery_filter_get_blog_type',	'soapery_woocommerce_get_blog_type', 9, 2);
	function soapery_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'soapery_woocommerce_get_blog_title' ) ) {
	//Handler of add_filter('soapery_filter_get_blog_title',	'soapery_woocommerce_get_blog_title', 9, 2);
	function soapery_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( soapery_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'soapery') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'soapery' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'soapery' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'soapery' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = soapery_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = soapery_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'soapery' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'soapery_woocommerce_get_stream_page_title' ) ) {
	//Handler of add_filter('soapery_filter_get_stream_page_title',	'soapery_woocommerce_get_stream_page_title', 9, 2);
	function soapery_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (soapery_strpos($page, 'woocommerce')!==false) {
			if (($page_id = soapery_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = soapery_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'soapery');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'soapery_woocommerce_get_stream_page_id' ) ) {
	//Handler of add_filter('soapery_filter_get_stream_page_id',	'soapery_woocommerce_get_stream_page_id', 9, 2);
	function soapery_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (soapery_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'soapery_woocommerce_get_stream_page_link' ) ) {
	//Handler of add_filter('soapery_filter_get_stream_page_link',	'soapery_woocommerce_get_stream_page_link', 9, 2);
	function soapery_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (soapery_strpos($page, 'woocommerce')!==false) {
			$id = soapery_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'soapery_woocommerce_get_current_taxonomy' ) ) {
	//Handler of add_filter('soapery_filter_get_current_taxonomy',	'soapery_woocommerce_get_current_taxonomy', 9, 2);
	function soapery_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( soapery_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'soapery_woocommerce_is_taxonomy' ) ) {
	//Handler of add_filter('soapery_filter_is_taxonomy',	'soapery_woocommerce_is_taxonomy', 9, 2);
	function soapery_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query!==null && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'soapery_woocommerce_orderby_need' ) ) {
	//Handler of add_filter('soapery_filter_orderby_need',	'soapery_woocommerce_orderby_need', 9, 1);
	function soapery_woocommerce_orderby_need($need) {
		if ($need == false || soapery_storage_empty('pre_query'))
			return $need;
		else {
			return soapery_storage_call_obj_method('pre_query', 'get', 'post_type')!='product' 
					&& soapery_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
					&& soapery_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'soapery_woocommerce_list_post_types' ) ) {
	//Handler of add_filter('soapery_filter_list_post_types', 	'soapery_woocommerce_list_post_types', 10, 1);
	function soapery_woocommerce_list_post_types($list) {
		$list = is_array($list) ? $list : array();
		$list['product'] = esc_html__('Products', 'soapery');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'soapery_woocommerce_frontend_scripts' ) ) {
	//Handler of add_action( 'soapery_action_add_styles', 'soapery_woocommerce_frontend_scripts' );
	function soapery_woocommerce_frontend_scripts() {
		if (soapery_is_woocommerce_page() || soapery_get_custom_option('show_cart')=='always')
			if (file_exists(soapery_get_file_dir('css/plugin.woocommerce.css')))
				wp_enqueue_style( 'soapery-plugin.woocommerce-style',  soapery_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}

// Replace standard WooCommerce function
/*
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post;
		if ( has_post_thumbnail() ) {
			$s = wc_get_image_size( $size );
			return soapery_get_resized_image_tag($post->ID, $s['width'], soapery_get_theme_option('crop_product_thumb')=='no' ? null :  $s['height']);
			//return get_the_post_thumbnail( $post->ID, array($s['width'], $s['height']) );
		} else if ( wc_placeholder_img_src() )
			return wc_placeholder_img( $size );
	}
}
*/

// Before main content
if ( !function_exists( 'soapery_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//Handler of add_action('woocommerce_before_main_content', 'soapery_woocommerce_wrapper_start', 10);
	function soapery_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !soapery_storage_empty('shop_mode') ? soapery_storage_get('shop_mode') : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'soapery_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//Handler of add_action('woocommerce_after_main_content', 'soapery_woocommerce_wrapper_end', 10);
	function soapery_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'soapery_woocommerce_show_page_title' ) ) {
	//Handler of add_action('woocommerce_show_page_title', 'soapery_woocommerce_show_page_title', 10);
	function soapery_woocommerce_show_page_title($defa=true) {
		return soapery_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'soapery_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//Handler of add_action( 'woocommerce_single_product_summary', 'soapery_woocommerce_show_product_title', 5 );
	function soapery_woocommerce_show_product_title() {
		if (soapery_get_custom_option('show_post_title')=='yes' || soapery_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// New product excerpt with video shortcode
if ( !function_exists( 'soapery_template_single_excerpt' ) ) {
    //remove_action(  'woocommerce_single_product_summary',       'woocommerce_template_single_excerpt', 20);
    //Handler of add_action(    'woocommerce_single_product_summary',		'soapery_template_single_excerpt', 20 );
    function soapery_template_single_excerpt() {
        if ( ! defined( 'ABSPATH' ) ) {
            exit; // Exit if accessed directly
        }
        global $post;
        if ( ! $post->post_excerpt ) {
            return;
        }
        ?>
        <div itemprop="description">
            <?php echo soapery_substitute_all(apply_filters( 'woocommerce_short_description', $post->post_excerpt )); ?>
        </div>
    <?php
    }
}

// Add list mode buttons
if ( !function_exists( 'soapery_woocommerce_before_shop_loop' ) ) {
	//Handler of add_action( 'woocommerce_before_shop_loop', 'soapery_woocommerce_before_shop_loop', 10 );
	function soapery_woocommerce_before_shop_loop() {
		if (soapery_get_custom_option('show_mode_buttons')=='yes') {
            echo '<div class="mode_buttons"><form action="' . esc_url(soapery_get_current_url()) . '" method="post">'
				. '<input type="hidden" name="soapery_shop_mode" value="'.esc_attr(soapery_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'soapery').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'soapery').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'soapery_woocommerce_open_thumb_wrapper' ) ) {
	function soapery_woocommerce_open_thumb_wrapper($cat='') {
		soapery_storage_set('in_product_item', true);
		?>

		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">

		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'soapery_woocommerce_open_item_wrapper' ) ) {
	function soapery_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'soapery_woocommerce_close_item_wrapper' ) ) {
	function soapery_woocommerce_close_item_wrapper($cat='') {
					?>
			</div>
		</div>
		<?php
		soapery_storage_set('in_product_item', false);

	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'soapery_woocommerce_after_shop_loop_item_title' ) ) {
	//Handler of add_action( 'woocommerce_after_shop_loop_item_title', 'soapery_woocommerce_after_shop_loop_item_title', 7);
	function soapery_woocommerce_after_shop_loop_item_title() {
		if (soapery_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'soapery_woocommerce_after_subcategory_title' ) ) {
	//Handler of add_action( 'woocommerce_after_subcategory_title', 'soapery_woocommerce_after_subcategory_title', 10 );
	function soapery_woocommerce_after_subcategory_title($category) {
		if (soapery_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'soapery_woocommerce_show_product_id' ) ) {
	//Handler of add_action( 'woocommerce_product_meta_end', 'soapery_woocommerce_show_product_id', 10);
	function soapery_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'soapery') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'soapery_woocommerce_output_related_products_args' ) ) {
	//Handler of add_filter( 'woocommerce_output_related_products_args', 'soapery_woocommerce_output_related_products_args' );
	function soapery_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (soapery_param_is_on(soapery_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(soapery_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  soapery_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (soapery_param_is_off(soapery_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = soapery_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Redefine post_type if number of related products == 0
if ( !function_exists( 'soapery_woocommerce_related_products_args' ) ) {
	//Handler of add_filter( 'woocommerce_related_products_args', 'soapery_woocommerce_related_products_args' );
	function soapery_woocommerce_related_products_args($args) {
		if ($args['posts_per_page'] == 0)
			$args['post_type'] .= '_';
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'soapery_woocommerce_product_thumbnails_columns' ) ) {
	//Handler of add_filter( 'woocommerce_product_thumbnails_columns', 'soapery_woocommerce_product_thumbnails_columns' );
	function soapery_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'soapery_woocommerce_loop_shop_columns_class' ) ) {
	//Handler of add_filter( 'post_class', 'soapery_woocommerce_loop_shop_columns_class' );
	//Handler of add_filter( 'product_cat_class', 'soapery_woocommerce_loop_shop_columns_class', 10, 3 );
    function soapery_woocommerce_loop_shop_columns_class($class, $class2='', $cat='') {
        if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
            $cols = function_exists('wc_get_default_products_per_row') ? wc_get_default_products_per_row() : 2;
            $class[] = ' column-1_' . $cols;
        }
        return $class;
    }
}


// Search form
if ( !function_exists( 'soapery_woocommerce_get_product_search_form' ) ) {
	//Handler of add_filter( 'get_product_search_form', 'soapery_woocommerce_get_product_search_form' );
	function soapery_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'soapery') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'soapery') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'soapery_woocommerce_the_title' ) ) {
	function soapery_woocommerce_the_title($title) {
		if (soapery_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}
// Wrap category title into link
if ( !function_exists( 'soapery_woocommerce_shop_loop_subcategory_title' ) ) {
	//Handler of the add_filter( 'woocommerce_shop_loop_subcategory_title', 'soapery_woocommerce_shop_loop_subcategory_title' );
	function soapery_woocommerce_shop_loop_subcategory_title($cat) {

		$cat->name = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($cat->slug, 'product_cat')), $cat->name);
		?>
        <h2 class="woocommerce-loop-category__title">
		<?php
		echo trim($cat->name);

		if ( $cat->count > 0 ) {
			echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $cat->count ) . ')</mark>', $cat ); // WPCS: XSS ok.
		}
		?>
        </h2><?php
	}
}

// Replace H2 tag to H3 tag in product headings
if ( !function_exists( 'soapery_woocommerce_template_loop_product_title' ) ) {
    //Handler of add_action( 'woocommerce_shop_loop_item_title',    'soapery_woocommerce_template_loop_product_title', 10 );
    function soapery_woocommerce_template_loop_product_title() {
        echo '<h3>' . get_the_title() . '</h3>';
    }
}

// Show pagination links
if ( !function_exists( 'soapery_woocommerce_pagination' ) ) {
	//Handler of add_filter( 'woocommerce_after_shop_loop', 'soapery_woocommerce_pagination', 10 );
	function soapery_woocommerce_pagination() {
		$style = soapery_get_custom_option('blog_pagination');
		soapery_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'soapery_woocommerce_required_plugins' ) ) {
	//Handler of add_filter('soapery_filter_required_plugins',	'soapery_woocommerce_required_plugins');
	function soapery_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', (array)soapery_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}

// Show products navigation
if ( !function_exists( 'soapery_woocommerce_show_post_navi' ) ) {
	//Handler of add_filter('soapery_filter_show_post_navi', 'soapery_woocommerce_show_post_navi');
	function soapery_woocommerce_show_post_navi($show=false) {
		return $show || (soapery_get_custom_option('show_page_title')=='yes' && is_single() && soapery_is_woocommerce_page());
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'soapery_woocommerce_importer_required_plugins' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_required_plugins',	'soapery_woocommerce_importer_required_plugins', 10, 2 );
	function soapery_woocommerce_importer_required_plugins($not_installed='', $list='') {
		if (soapery_strpos($list, 'woocommerce')!==false && !soapery_exists_woocommerce() )
			$not_installed .= '<br>' . esc_html__('WooCommerce', 'soapery');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'soapery_woocommerce_importer_set_options' ) ) {
	//Handler of add_filter( 'soapery_filter_importer_options',	'soapery_woocommerce_importer_set_options' );
	function soapery_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', (array)soapery_storage_get('required_plugins')) && soapery_exists_woocommerce() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_woocommerce'] = str_replace('name.ext', 'woocommerce.txt', $v['file_with_']);
				}
			}
			// Add slugs to export options for this plugin
			$options['additional_options'][]	= 'shop_%';
			$options['additional_options'][]	= 'woocommerce_%';
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'soapery_woocommerce_importer_after_import_posts' ) ) {
	//Handler of add_action( 'soapery_action_importer_after_import_posts',	'soapery_woocommerce_importer_after_import_posts', 10, 1 );
	function soapery_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'soapery_woocommerce_importer_show_params' ) ) {
	//Handler of add_action( 'soapery_action_importer_params',	'soapery_woocommerce_importer_show_params', 10, 1 );
	function soapery_woocommerce_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('Import WooCommerce', 'soapery'),
			'part' => 0
			));
	}
}

// Import posts
if ( !function_exists( 'soapery_woocommerce_importer_import' ) ) {
	//Handler of add_action( 'soapery_action_importer_import',	'soapery_woocommerce_importer_import', 10, 2 );
	function soapery_woocommerce_importer_import($importer, $action) {
		if ( $action == 'import_woocommerce' ) {
			$importer->response['start_from_id'] = 0;
			$importer->import_dump('woocommerce', esc_html__('WooCommerce meta', 'soapery'));
			delete_transient( 'wc_attribute_taxonomies' );
		}
	}
}

// Display import progress
if ( !function_exists( 'soapery_woocommerce_importer_import_fields' ) ) {
	//Handler of add_action( 'soapery_action_importer_import_fields',	'soapery_woocommerce_importer_import_fields', 10, 1 );
	function soapery_woocommerce_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('WooCommerce meta', 'soapery')
			));
	}
}

// Export posts
if ( !function_exists( 'soapery_woocommerce_importer_export' ) ) {
	//Handler of add_action( 'soapery_action_importer_export',	'soapery_woocommerce_importer_export', 10, 1 );
	function soapery_woocommerce_importer_export($importer) {
		soapery_fpc(soapery_get_file_dir('core/core.importer/export/woocommerce.txt'), serialize( array(
			"woocommerce_attribute_taxonomies"				=> $importer->export_dump("woocommerce_attribute_taxonomies"),
			"woocommerce_downloadable_product_permissions"	=> $importer->export_dump("woocommerce_downloadable_product_permissions"),
            "woocommerce_order_itemmeta"					=> $importer->export_dump("woocommerce_order_itemmeta"),
            "woocommerce_order_items"						=> $importer->export_dump("woocommerce_order_items"),
            "woocommerce_termmeta"							=> $importer->export_dump("woocommerce_termmeta")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'soapery_woocommerce_importer_export_fields' ) ) {
	//Handler of add_action( 'soapery_action_importer_export_fields',	'soapery_woocommerce_importer_export_fields', 10, 1 );
	function soapery_woocommerce_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('WooCommerce', 'soapery')
			));
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'soapery_woocommerce_reg_shortcodes' ) ) {
	//Handler of add_action('soapery_action_shortcodes_list', 'soapery_woocommerce_reg_shortcodes', 20);
	function soapery_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		soapery_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		soapery_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		soapery_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		soapery_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		soapery_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		soapery_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'soapery'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'soapery'),
					"desc" => wp_kses_data( __("ID of displayed product", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", 'soapery'),
					"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'soapery') ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", 'soapery'),
					"desc" => wp_kses_data( __("Display posts only with this status", 'soapery') ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'soapery'),
						"protected" => esc_html__('Protected', 'soapery'),
						"private" => esc_html__('Private', 'soapery'),
						"pending" => esc_html__('Pending', 'soapery'),
						"draft" => esc_html__('Draft', 'soapery')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		soapery_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'soapery'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'soapery'),
					"desc" => wp_kses_data( __("ID of displayed product", 'soapery') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		soapery_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);
		
		// WooCommerce - Recent Products
		soapery_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Related Products
		soapery_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
						)
					)
				)
			)
		);
		
		// WooCommerce - Featured Products
		soapery_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Top Rated Products
		soapery_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Sale Products
		soapery_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		soapery_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", 'soapery'),
					"desc" => wp_kses_data( __("Comma separated category slugs", 'soapery') ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", 'soapery'),
					"desc" => wp_kses_data( __("Categories operator", 'soapery') ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'soapery'),
						"NOT IN" => esc_html__('NOT IN', 'soapery'),
						"AND" => esc_html__('AND', 'soapery')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		soapery_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", 'soapery'),
					"desc" => wp_kses_data( __("Comma separated SKU codes of products", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'soapery'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		soapery_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many products showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", 'soapery'),
					"desc" => wp_kses_data( __("Attribute name", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", 'soapery'),
					"desc" => wp_kses_data( __("Attribute value", 'soapery') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		soapery_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", 'soapery'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'soapery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", 'soapery'),
					"desc" => wp_kses_data( __("How many categories showed", 'soapery') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'soapery'),
					"desc" => wp_kses_data( __("How many columns per row use for categories output", 'soapery') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'soapery'),
						"title" => esc_html__('Title', 'soapery')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'soapery'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => soapery_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", 'soapery'),
					"desc" => wp_kses_data( __("Parent category slug", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'soapery'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'soapery') ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", 'soapery'),
					"desc" => wp_kses_data( __("Hide empty categories", 'soapery') ),
					"value" => "yes",
					"type" => "switch",
					"options" => soapery_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'soapery_woocommerce_reg_shortcodes_vc' ) ) {
	//Handler of add_action('soapery_action_shortcodes_list_vc', 'soapery_woocommerce_reg_shortcodes_vc');
	function soapery_woocommerce_reg_shortcodes_vc() {
	
		if (false && function_exists('soapery_exists_woocommerce') && soapery_exists_woocommerce()) {
		
			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'soapery'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'soapery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Cart extends SOAPERY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'soapery'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'soapery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Checkout extends SOAPERY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'soapery'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'soapery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_My_Account extends SOAPERY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'soapery'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'soapery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Order_Tracking extends SOAPERY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'soapery'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'soapery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Shop_Messages extends SOAPERY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'soapery'),
						"description" => wp_kses_data( __("SKU code of displayed product", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'soapery'),
						"description" => wp_kses_data( __("ID of displayed product", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'soapery'),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'soapery') ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", 'soapery'),
						"description" => wp_kses_data( __("Display posts only with this status", 'soapery') ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'soapery') => 'publish',
							esc_html__('Protected', 'soapery') => 'protected',
							esc_html__('Private', 'soapery') => 'private',
							esc_html__('Pending', 'soapery') => 'pending',
							esc_html__('Draft', 'soapery') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Page extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'soapery'),
						"description" => wp_kses_data( __("Product's SKU code", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'soapery'),
						"description" => wp_kses_data( __("Product's ID", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product extends SOAPERY_VC_ShortCodeSingle {}
		
		
			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Best_Selling_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Related_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Featured_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Top_Rated_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Sale_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", 'soapery'),
						"description" => wp_kses_data( __("Comma separated category slugs", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", 'soapery'),
						"description" => wp_kses_data( __("Categories operator", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'soapery') => 'IN',
							esc_html__('NOT IN', 'soapery') => 'NOT IN',
							esc_html__('AND', 'soapery') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Category extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", 'soapery'),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'soapery'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Products extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
		
			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many products showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", 'soapery'),
						"description" => wp_kses_data( __("Attribute name", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", 'soapery'),
						"description" => wp_kses_data( __("Attribute value", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Attribute extends SOAPERY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", 'soapery'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'soapery') ),
				"category" => esc_html__('WooCommerce', 'soapery'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", 'soapery'),
						"description" => wp_kses_data( __("How many categories showed", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'soapery'),
						"description" => wp_kses_data( __("How many columns per row use for categories output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'soapery') => 'date',
							esc_html__('Title', 'soapery') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'soapery'),
						"description" => wp_kses_data( __("Sorting order for products output", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip((array)soapery_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", 'soapery'),
						"description" => wp_kses_data( __("Parent category slug", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'soapery'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'soapery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", 'soapery'),
						"description" => wp_kses_data( __("Hide empty categories", 'soapery') ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );
			
			class WPBakeryShortCode_Products_Categories extends SOAPERY_VC_ShortCodeSingle {}
		
		}
	}
}
?>
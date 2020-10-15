<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_template_header_7_theme_setup' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_template_header_7_theme_setup', 1 );
	function soapery_template_header_7_theme_setup() {
		soapery_add_template(array(
			'layout' => 'header_7',
			'mode'   => 'header',
			'title'  => esc_html__('Header 7', 'soapery'),
			'icon'   => soapery_get_file_url('templates/headers/images/7.jpg'),
			'thumb_title'  => esc_html__('Original image', 'soapery'),
			'w'		 => null,
			'h_crop' => null,
			'h'      => null
			));
	}
}

// Template output
if ( !function_exists( 'soapery_template_header_7_output' ) ) {
	function soapery_template_header_7_output($post_options, $post_data) {

		// Get custom image (for blog) or featured image (for single)
		$header_css = '';
		if (is_singular()) {
			$post_id = get_the_ID();
			$post_format = get_post_format();
			$post_icon = soapery_get_custom_option('icon', soapery_get_post_format_icon($post_format));
			$header_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		}
		if (empty($header_image))
			$header_image = soapery_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			/* Uncomment next rows if you want crop image
			$thumb_sizes = soapery_get_thumb_sizes(array(
				'layout' => $post_options['layout']
			));
			$header_image = soapery_get_resized_image_url($header_image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
			*/
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(soapery_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="column-1_3 contact_logo">
						<?php soapery_show_logo(true, true); ?>
					</div>
					<div class="column-2_3 menu_main_wrap">
						<nav class="menu_main_nav_area">
							<?php
							$menu_main = soapery_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = soapery_get_nav_menu();
							soapery_show_layout($menu_main);
							?>
						</nav>
						<?php
						if (function_exists('soapery_exists_woocommerce') && soapery_exists_woocommerce() && (soapery_is_woocommerce_page() && soapery_get_custom_option('show_cart')=='shop' || soapery_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
							?>
							<div class="menu_main_cart top_panel_icon">
								<?php get_template_part(soapery_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
							</div>
							<?php
						}
						if (soapery_get_custom_option('show_search')=='yes') 
							soapery_show_layout(soapery_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
						?>
					</div>
				</div>
			</div>
			

			</div>
		</header>

		<section class="top_panel_image" <?php soapery_show_layout($header_css); ?>>
			<div class="top_panel_image_hover"></div>
			<div class="top_panel_image_header">
				<?php if (!empty($post_icon)) { ?>
				<div class="top_panel_image_icon <?php echo esc_attr($post_icon); ?>"></div>
				<?php } ?>
				<h1 itemprop="headline" class="top_panel_image_title entry-title"><?php echo strip_tags(soapery_get_blog_title()); ?></h1>
				<div class="breadcrumbs">
					<?php if (!is_404()) soapery_show_breadcrumbs(); ?>
				</div>
			</div>
		</section>
		<?php
		soapery_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => true,
				 'search' => true
			)
		);
	}
}
?>
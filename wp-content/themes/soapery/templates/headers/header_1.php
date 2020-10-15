<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_template_header_1_theme_setup' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_template_header_1_theme_setup', 1 );
	function soapery_template_header_1_theme_setup() {
		soapery_add_template(array(
			'layout' => 'header_1',
			'mode'   => 'header',
			'title'  => esc_html__('Header 1', 'soapery'),
			'icon'   => soapery_get_file_url('templates/headers/images/1.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'soapery_template_header_1_output' ) ) {
	function soapery_template_header_1_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
			? ' style="background-image: url('.esc_url($header_image).')"' 
			: '';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_1 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_1 top_panel_position_<?php echo esc_attr(soapery_get_custom_option('top_panel_position')); ?>">
				<div class="top_panel_middle" <?php soapery_show_layout($header_css); ?>>
					<div class="content_wrap">
						<div class="columns_wrap columns_fluid">
							<div class="column-1_3 contact_label">
								<?php $contact_phone=trim(soapery_get_custom_option('contact_phone')); ?>
									<?php echo (!empty($contact_phone) ? '<span class="contact_phone icon-phone"><span class="callus">'. esc_html__('Call us ','soapery').'</span>' : '') .'<a href="tel:'.trim($contact_phone).'">'.trim($contact_phone).'</a> '.'</span>'; ?>
							</div><div class="column-1_3 contact_logo">
							<?php soapery_show_logo(true, true, false, false, false, false); ?>
							</div><div class="column-1_3 right_side_info">
						<?php if (soapery_get_custom_option('show_top_panel_top')=='yes') { ?>
						<?php
						soapery_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('login')
							));
						get_template_part(soapery_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
						<?php } 

						if (function_exists('soapery_exists_woocommerce') && soapery_exists_woocommerce() && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
						?>
						<div class="menu_main_cart top_panel_icon">
							<?php get_template_part(soapery_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
						</div>

						<?php  }?>
					</div>
				</div>
			</div>
		</div>

		<div class="top_panel_bottom">
			<div class="content_wrap clearfix">
				<nav class="menu_main_nav_area">
					<?php
					$menu_main = soapery_get_nav_menu('menu_main');
					if (empty($menu_main)) $menu_main = soapery_get_nav_menu();
					soapery_show_layout($menu_main);
					?>
				</nav>
			</div>
		</div>

	</div>
</header>

<?php
soapery_storage_set('header_mobile', array(
	'open_hours' => true,
	'login' => true,
	'socials' => true,
	'bookmarks' => true,
	'contact_address' => true,
	'contact_phone_email' => true,
	'woo_cart' => true,
	'search' => true
	)
);
}
}
?>
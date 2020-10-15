<?php
/**
 * The Header for our theme.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php
		// Add class 'scheme_xxx' into <html> because it used as context for the body classes!
$body_scheme = soapery_get_custom_option('body_scheme');
if (empty($body_scheme) || soapery_is_inherit_option($body_scheme)) $body_scheme = 'original';
echo 'scheme_' . esc_attr($body_scheme); 
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class();?>>

	<?php 	do_action( 'before' );	?>

	<?php if ( !soapery_param_is_off(soapery_get_custom_option('show_sidebar_outer')) ) { ?>
		<div class="outer_wrap">
			<?php } ?>

			<?php get_template_part(soapery_get_file_slug('sidebar_outer.php')); ?>

			<?php
			$class = $style = '';
			$body_style  = soapery_get_custom_option('body_style');
			if (soapery_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || soapery_get_custom_option('bg_image_load')=='always')) {
				if (($img = soapery_get_custom_option('bg_image_custom')) != '')
					$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', soapery_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
				else if (($img = soapery_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = soapery_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = soapery_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = soapery_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
			?>

			<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>

				<?php
				$video_bg_show  = soapery_get_custom_option('show_video_bg')=='yes';
				$youtube = soapery_get_custom_option('video_bg_youtube_code');
				$video   = soapery_get_custom_option('video_bg_url');
				$overlay = soapery_get_custom_option('video_bg_overlay')=='yes';
				if ($video_bg_show && (!empty($youtube) || !empty($video))) {
					if (!empty($youtube)) {
						?>
						<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
						<?php
					} else if (!empty($video)) {
						$info = pathinfo($video);
						$ext = !empty($info['extension']) ? $info['extension'] : 'src';
						?>
						<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
						<?php
					}
				}
				?>

				<div class="page_wrap">

					<?php
					$top_panel_style = soapery_get_custom_option('top_panel_style');
					$top_panel_position = soapery_get_custom_option('top_panel_position');
					$top_panel_scheme = soapery_get_custom_option('top_panel_scheme');
					if (in_array($top_panel_position, array('above', 'over'))) {
						soapery_show_post_layout(array(
							'layout' => $top_panel_style,
							'position' => $top_panel_position,
							'scheme' => $top_panel_scheme
							), false);
				// Mobile Menu
						get_template_part(soapery_get_file_slug('templates/headers/_parts/header-mobile.php'));
					}

			// Slider
					get_template_part(soapery_get_file_slug('templates/headers/_parts/slider.php'));

			// Top panel 'Below'
					if ($top_panel_position == 'below') {
						soapery_show_post_layout(array(
							'layout' => $top_panel_style,
							'position' => $top_panel_position,
							'scheme' => $top_panel_scheme
							), false);
				// Mobile Menu
						get_template_part(soapery_get_file_slug('templates/headers/_parts/header-mobile.php'));
					}

			// Top of page section: page title and breadcrumbs
					$show_title = soapery_get_custom_option('show_page_title')=='yes';
					$show_navi = $show_title && is_single() && soapery_is_woocommerce_page();
					$show_breadcrumbs = soapery_get_custom_option('show_breadcrumbs')=='yes';
					if (($show_title || $show_breadcrumbs) && !is_front_page()) {
						?>
						<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present'.  ($show_navi ? ' navi_present' : '') : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
							<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present_inner' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present_inner' : ''); ?>">
								<div class="content_wrap">
									<?php
									if ($show_title) {
										if ($show_navi) {
											?><div class="post_navi"><?php 
											previous_post_link( '<span class="post_navi_item post_navi_prev">%link</span>', '%title', true, '', 'product_cat' );
											next_post_link( '<span class="post_navi_item post_navi_next">%link</span>', '%title', true, '', 'product_cat' );
											?></div><?php
										} else {
											?><h1 class="page_title"><?php echo strip_tags(soapery_get_blog_title()); ?></h1><?php
										}
									}
									if ($show_breadcrumbs) {
										?><div class="breadcrumbs"><?php if (!is_404()) soapery_show_breadcrumbs(); ?></div><?php
									}
									?>
								</div>
							</div>
						</div>
						<?php
					}
					?>

					<div class="page_content_wrap page_paddings_<?php echo esc_attr(soapery_get_custom_option('body_paddings')); ?>">

						<?php
						
						if ($body_style!='fullscreen') soapery_open_wrapper('<div class="content_wrap">');


					//Header for woocommerce custom heading
						if (soapery_get_custom_option('show_custom_heading') == 'yes') { 
							$woo_shorcode = soapery_get_custom_option('show_custom_shortcode');
							$woo_image = soapery_get_custom_option('custom_header_bg');
							?>
							<div class="content_wrap single_page_heading">
								<div class="custom_header_wrap">	
									<div class="single_custom_header" <?php echo (!empty($woo_image) ? 'style="background-image: url('.esc_url($woo_image).');"' : ''); ?> >
										<div class="shorcodes_in_header"><?php echo (!empty($woo_shorcode) ? do_shortcode($woo_shorcode) : 'error');?></div>
									</div>
								</div>	<!-- /.content_wrap -->
							</div>	<!-- /.contacts_wrap_inner -->
							<?php
						}


				// Main content wrapper
						soapery_open_wrapper('<div class="content">');

						?>
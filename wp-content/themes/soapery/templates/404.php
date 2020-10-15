<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'soapery_template_404_theme_setup' ) ) {
	add_action( 'soapery_action_before_init_theme', 'soapery_template_404_theme_setup', 1 );
	function soapery_template_404_theme_setup() {
		soapery_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'soapery_template_404_output' ) ) {
	function soapery_template_404_output() {
		?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<span class="icon_404 icon-icon3"></span>
				<h3 class="page_subtitle"><?php esc_html_e('Error 404! can not find that page!', 'soapery'); ?></h3>
				<p class="page_description"><?php echo wp_kses_data('Can\'t find what you need? Take a moment and do a search below or start from <a href="'.esc_url('/').'">our homepage</a>.', 'soapery'); ?></p>
				<div class="page_search"><?php if (function_exists('soapery_sc_search')) soapery_show_layout(soapery_sc_search(array('state'=>'fixed', 'title'=>__('Enter keyword', 'soapery')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>
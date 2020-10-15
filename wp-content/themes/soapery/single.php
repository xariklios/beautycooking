<?php
/**
 * Single post
 */
get_header(); 

$single_style = soapery_storage_get('single_style');
if (empty($single_style)) $single_style = soapery_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	soapery_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !soapery_param_is_off(soapery_get_custom_option('show_sidebar_main')),
			'content' => soapery_get_template_property($single_style, 'need_content'),
			'terms_list' => soapery_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>
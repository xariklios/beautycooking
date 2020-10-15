<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move soapery_set_post_views to the javascript - counter will work under cache system
	if (soapery_get_custom_option('use_ajax_views_counter')=='no') {
		soapery_set_post_views(get_the_ID());
	}

	soapery_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !soapery_param_is_off(soapery_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>
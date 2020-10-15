<?php
$cart_items = WC()->cart->get_cart_contents_count();
$cart_summa = strip_tags(WC()->cart->get_cart_subtotal());
?>
<a href="#" class="top_panel_cart_button" data-items="<?php echo esc_attr($cart_items); ?>" data-summa="<?php echo esc_attr($cart_summa); ?>">
	<span class="contact_icon icon-briefcase"></span>
	<span class="contact_label contact_cart_label"><?php esc_html_e('My bag', 'soapery'); ?></span>
	<span class="contact_cart_totals">
		<span class="cart_items"><?php echo esc_html($cart_items);?></span>
	</span>
</a>
<ul class="widget_area sidebar_cart sidebar"><li>
	<?php
	do_action( 'before_sidebar' );
	soapery_storage_set('current_sidebar', 'cart');

		the_widget( 'WC_Widget_Cart', 'title=&hide_if_empty=1' );

	?>
</li></ul>
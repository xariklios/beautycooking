<?php
if (is_singular() && soapery_get_theme_option('use_ajax_views_counter')=='no') {
    soapery_set_post_views(get_the_ID());
}
?>
<?php
/**
 * Child-Theme functions and definitions
 */

add_action('wp_enqueue_scripts', 'beautycooking_child_css', 1000);


//load JS


// Load CSS
function beautycooking_child_css()
{

    // oildrop child theme styles

    wp_enqueue_style('beautycooking-style',
        get_stylesheet_directory_uri() . '/assets/css/style.css',
        wp_get_theme()->get('Version')
    );

}

?>
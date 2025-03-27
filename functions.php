<?php

function mytheme_enqueue_styles() {
    wp_enqueue_style('mytheme-style', get_stylesheet_directory_uri() . '/style.css', array(), filemtime(get_stylesheet_directory() . '/style.css'));
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_styles');


// Include the cache-setting.php and cache-toggle.php files
require_once get_template_directory() . '/cache-control/cache-settings.php';  // Handles settings page
require_once get_template_directory() . '/cache-control/cache-toggle.php';  // Handles the toggle functionality

// Enqueue styles for the Cache Control toggle
function enqueue_cache_control_styles() {
    wp_enqueue_style('cache-control-styles', get_template_directory_uri() . '/cache-control/cache-control.css');
}
add_action('admin_enqueue_scripts', 'enqueue_cache_control_styles');

// Purge the cache based on the toggle option
add_action('admin_init', function() {
    // Check if the purge toggle is enabled
    if (get_option('purge_pattern_cache') == 1) {
        // Only clear cache if toggle is on
        if (is_admin()) {
            $theme = wp_get_theme();
            if (method_exists($theme, 'delete_pattern_cache')) {
                $theme->delete_pattern_cache();
            }
        }
    }
});


// Include Admin Settings
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/ct-form-handle.php';
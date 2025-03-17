<?php

// Function to register patterns from the /patterns directory
function register_larris_theme_patterns() {
    $patterns_dir = get_template_directory() . '/patterns';
    
    // Check if the directory exists
    if ( is_dir( $patterns_dir ) ) {
        // Get all PHP files in the patterns directory
        $pattern_files = glob( $patterns_dir . '/*.php' );

        // Loop through each file and include it
        foreach ( $pattern_files as $file ) {
            include_once $file;
        }
    }
}

// Register patterns every time the theme is activated or switched
function larris_theme_register_patterns_on_activation() {
    register_larris_theme_patterns();
}
add_action( 'after_switch_theme', 'larris_theme_register_patterns_on_activation' );

// Force patterns to be registered on every page load (development only)
function larris_theme_rescan_patterns_on_load() {
    // Ensure patterns are registered each time the page loads
    register_larris_theme_patterns();
}
add_action( 'init', 'larris_theme_rescan_patterns_on_load' );


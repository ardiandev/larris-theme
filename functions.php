<?php
// Function to register your theme patterns
function register_larris_theme_patterns() {
    // Ensure this is the correct path to your patterns directory.
    $patterns_dir = get_template_directory() . '/patterns';

    // Check if the patterns directory exists
    if ( is_dir( $patterns_dir ) ) {
        $pattern_files = glob( $patterns_dir . '/*.php' );
        
        foreach ( $pattern_files as $file ) {
            // Include the pattern file so it's recognized
            include_once $file;
        }
    }
}

// Register patterns on theme activation or switch
function larris_theme_register_patterns_on_activation() {
    // Register the patterns when the theme is activated
    register_larris_theme_patterns();
}
add_action( 'after_switch_theme', 'larris_theme_register_patterns_on_activation' );

// Ensure patterns are registered on every page load (useful for development)
function larris_theme_rescan_patterns_on_load() {
    // Check if patterns are already registered to avoid multiple registrations
    if ( ! has_action( 'block_patterns' ) ) {
        register_larris_theme_patterns();
    }
}
add_action( 'init', 'larris_theme_rescan_patterns_on_load' );

// Optional: If you need to force WordPress to reload the theme on each page load
function larris_theme_force_rescan() {
    // Force WordPress to reload the theme
    wp_set_theme( get_template() );
}
add_action( 'wp_loaded', 'larris_theme_force_rescan' );

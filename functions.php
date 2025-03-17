<?php

add_action('admin_init', function() {
    // Clear the cached patterns
    delete_transient('wp_block_patterns');

    // Path to the patterns directory
    $patterns_dir = get_template_directory() . '/patterns/';

    // Check if the directory exists
    if (is_dir($patterns_dir)) {
        foreach (glob($patterns_dir . '*.php') as $file) {
            register_block_pattern(
                'larris-theme/' . basename($file, '.php'),
                require $file
            );
        }
    }
});

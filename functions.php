<?php
// Automatically scan and register block patterns in the 'patterns' directory when in admin panel
add_action('admin_init', function() {
    // Clear cached patterns
    delete_transient('wp_block_patterns');

    $patterns_dir = get_template_directory() . '/patterns/';

    if (is_dir($patterns_dir)) {
        foreach (glob($patterns_dir . '*.php') as $file) {
            $pattern_data = require $file; // Load pattern array from file

            if (is_array($pattern_data) && isset($pattern_data['title'], $pattern_data['content'])) {
                register_block_pattern('larris-theme/' . basename($file, '.php'), $pattern_data);
            }
        }
    }
});

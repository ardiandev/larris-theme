<?php
// Register block patterns only in the admin panel
add_action('init', function() {
    if (!is_admin()) {
        return; // Prevent execution on the front-end
    }

    // Clear cached patterns
    delete_transient('wp_block_patterns');

    $patterns_dir = get_template_directory() . '/patterns/';

    if (is_dir($patterns_dir)) {
        foreach (glob($patterns_dir . '*.php') as $file) {
            $pattern_data = include $file;

            if (is_array($pattern_data) && isset($pattern_data['title'], $pattern_data['content'])) {
                register_block_pattern('larris-theme/' . basename($file, '.php'), $pattern_data);
            }
        }
    }
});

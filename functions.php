<?php
add_action('admin_init', function() {
    // Get all registered block patterns
    $registered_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

    // Unregister all existing patterns
    foreach ($registered_patterns as $pattern_name => $pattern_data) {
        unregister_block_pattern($pattern_name);
    }

    // Clear cached patterns
    delete_transient('wp_block_patterns');

    // Re-register all patterns from the "patterns" directory
    $patterns_dir = get_template_directory() . '/patterns/';
    if (is_dir($patterns_dir)) {
        foreach (glob($patterns_dir . '*.php') as $file) {
            require $file;
        }
    }
});

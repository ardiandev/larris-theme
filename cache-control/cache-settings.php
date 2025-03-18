<?php 
// Register the setting for the purge toggle
function register_cache_settings() {
    register_setting('cache-settings-group', 'purge_pattern_cache');
}

add_action('admin_init', 'register_cache_settings');

<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function dev_mode_add_admin_page() {
    add_menu_page(
        'Development Mode',
        'Dev Mode',
        'manage_options',
        'dev-mode-settings',
        'dev_mode_settings_page',
        'dashicons-admin-generic',
        99
    );
}
add_action('admin_menu', 'dev_mode_add_admin_page');

// Settings page content
function dev_mode_settings_page() {
    $dev_mode = get_option('dev_mode_enabled', 'off');
    ?>
    <div class="wrap">
        <h1>Development Mode Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('dev_mode_settings_group'); ?>
            <?php do_settings_sections('dev-mode-settings'); ?>
            <label for="dev_mode_enabled">
                <input type="checkbox" name="dev_mode_enabled" id="dev_mode_enabled" value="on" <?php checked('on', $dev_mode); ?> />
                Enable Development Mode
            </label>
            <br><br>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register setting
function dev_mode_register_settings() {
    register_setting('dev_mode_settings_group', 'dev_mode_enabled');
}
add_action('admin_init', 'dev_mode_register_settings');

// Check if Development Mode is enabled
function is_dev_mode_enabled() {
    return get_option('dev_mode_enabled', 'off') === 'on';
}

// Define WP_DEVELOPMENT_MODE for WordPress (Themes, Plugins, and Core)
function dev_mode_define_constant() {
    if (is_dev_mode_enabled()) {
        define('WP_DEVELOPMENT_MODE', 'all');
    }
}
add_action('plugins_loaded', 'dev_mode_define_constant');

// Apply Dev Mode Effects
function dev_mode_apply_effects() {
    if (is_dev_mode_enabled()) {
        // Enable Debugging
        if (!defined('WP_DEBUG')) {
            define('WP_DEBUG', true);
        }
        if (!defined('WP_DEBUG_LOG')) {
            define('WP_DEBUG_LOG', true);
        }
        if (!defined('WP_DEBUG_DISPLAY')) {
            define('WP_DEBUG_DISPLAY', false);
            @ini_set('display_errors', 0);
        }
        
        // Disable caching
        add_filter('wp_cache', '__return_false');

        // Show query details
        if (!defined('SAVEQUERIES')) {
            define('SAVEQUERIES', true);
        }
    }
}
add_action('init', 'dev_mode_apply_effects');
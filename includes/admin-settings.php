<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add a menu page for SMTP settings
function ct_form_add_smtp_settings_page() {
    add_menu_page(
        'SMTP Settings',
        'SMTP Settings',
        'manage_options',
        'ct-form-smtp-settings',
        'ct_form_render_smtp_settings_page',
        'dashicons-email-alt',
        25
    );
}
add_action('admin_menu', 'ct_form_add_smtp_settings_page');

// Render the SMTP settings page
function ct_form_render_smtp_settings_page() {
    // Check if the user has submitted the form
    if (isset($_POST['ct_form_smtp_settings_submit'])) {
        // Verify nonce
        if (!isset($_POST['ct_form_smtp_settings_nonce']) || !wp_verify_nonce($_POST['ct_form_smtp_settings_nonce'], 'ct_form_smtp_settings_action')) {
            echo '<div class="error"><p>Nonce verification failed.</p></div>';
            return;
        }

        // Save the settings
        update_option('ct_form_smtp_host', sanitize_text_field($_POST['ct_form_smtp_host']));
        update_option('ct_form_smtp_port', intval($_POST['ct_form_smtp_port']));
        update_option('ct_form_smtp_username', sanitize_text_field($_POST['ct_form_smtp_username']));
        update_option('ct_form_smtp_password', sanitize_text_field($_POST['ct_form_smtp_password']));
        update_option('ct_form_smtp_encryption', sanitize_text_field($_POST['ct_form_smtp_encryption']));

        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    }

    // Get the current settings
    $smtp_host = get_option('ct_form_smtp_host', '');
    $smtp_port = get_option('ct_form_smtp_port', '');
    $smtp_username = get_option('ct_form_smtp_username', '');
    $smtp_password = get_option('ct_form_smtp_password', '');
    $smtp_encryption = get_option('ct_form_smtp_encryption', '');
    ?>
    <div class="wrap">
        <h1>SMTP Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('ct_form_smtp_settings_action', 'ct_form_smtp_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="ct_form_smtp_host">SMTP Host</label></th>
                    <td><input type="text" name="ct_form_smtp_host" id="ct_form_smtp_host" value="<?php echo esc_attr($smtp_host); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ct_form_smtp_port">SMTP Port</label></th>
                    <td><input type="number" name="ct_form_smtp_port" id="ct_form_smtp_port" value="<?php echo esc_attr($smtp_port); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ct_form_smtp_username">SMTP Username</label></th>
                    <td><input type="text" name="ct_form_smtp_username" id="ct_form_smtp_username" value="<?php echo esc_attr($smtp_username); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ct_form_smtp_password">SMTP Password</label></th>
                    <td><input type="password" name="ct_form_smtp_password" id="ct_form_smtp_password" value="<?php echo esc_attr($smtp_password); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ct_form_smtp_encryption">SMTP Encryption</label></th>
                    <td>
                        <select name="ct_form_smtp_encryption" id="ct_form_smtp_encryption">
                            <option value="" <?php selected($smtp_encryption, ''); ?>>None</option>
                            <option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>>SSL</option>
                            <option value="tls" <?php selected($smtp_encryption, 'tls'); ?>>TLS</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings', 'primary', 'ct_form_smtp_settings_submit'); ?>
        </form>
    </div>
    <?php
}
?>
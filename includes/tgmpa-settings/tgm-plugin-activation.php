<?php

function register_required_plugins() {
    $plugins = array(
        array(
            'name'      => 'Larris Contact Form', // Plugin name
            'slug'      => 'larris-contact-form', // The folder name of your plugin
            'source'    => 'https://github.com/ardiandev/larris-contact-form/archive/refs/heads/main.zip', // GitHub repo zip URL
            'required'  => true, // Make this plugin required for installation
            'version'   => '', // Optional: specify a version number
            'force_activation' => false, // Optional: force activation after install
            'force_deactivation' => false, // Optional: force deactivation when the plugin is uninstalled
        ),
    );

    tgmpa( $plugins );
}
add_action( 'tgmpa_register', 'register_required_plugins' );


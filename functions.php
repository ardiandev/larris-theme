<?php

function my_theme_refresh_patterns_on_change() {
    // Only run in admin area
    if ( ! is_admin() ) {
        return; // Exit early if not in admin
    }

    // Define a transient to store the last known state of the patterns directory
    $transient_key = 'larris_theme_patterns_hash';
    $patterns_dir = get_template_directory() . '/patterns/';
    
    // Get current state of patterns directory
    $pattern_files = glob( $patterns_dir . '*.php' );
    $current_hash = md5( serialize( array_map( 'filemtime', $pattern_files ) ) ); // Hash based on file modification times
    
    // Get the last known state from transient
    $last_hash = get_transient( $transient_key );
    
    // If no hash exists or it differs, refresh patterns
    if ( false === $last_hash || $current_hash !== $last_hash ) {
        // Unregister only theme-specific patterns
        $registered_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
        foreach ( $registered_patterns as $pattern ) {
            if ( strpos( $pattern['name'], 'larris-theme/' ) === 0 ) {
                unregister_block_pattern( $pattern['name'] );
            }
        }
        
        // Re-scan the patterns directory
        if ( function_exists( 'wp_get_theme' ) && wp_get_theme()->is_block_theme() ) {
            foreach ( $pattern_files as $file ) {
                $slug = 'larris-theme/' . basename( $file, '.php' );
                $content = file_get_contents( $file );
                $title = 'Pattern ' . basename( $file, '.php' ); // Fallback
                if ( preg_match( '/\* Title: (.+)$/m', $content, $match ) ) {
                    $title = trim( $match[1] );
                }
                register_block_pattern( $slug, [
                    'title'   => $title,
                    'content' => $content,
                ] );
            }
        }
        
        // Flush cache
        wp_cache_flush();
        
        // Update the transient with the new hash
        set_transient( $transient_key, $current_hash, WEEK_IN_SECONDS ); // Store for a week
    }
}
add_action( 'init', 'my_theme_refresh_patterns_on_change' );
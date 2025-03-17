<?php

function my_theme_refresh_patterns_on_change() {
    if ( ! is_admin() ) {
        return;
    }
    $transient_key = 'larris_theme_patterns_hash';
    $patterns_dir = get_template_directory() . '/patterns/';
    $pattern_files = glob( $patterns_dir . '*.php' );
    echo '<!-- Files found: ' . implode( ', ', $pattern_files ) . ' -->'; // Debug
    $current_hash = md5( serialize( array_map( 'filemtime', $pattern_files ) ) );
    $last_hash = get_transient( $transient_key );
    if ( false === $last_hash || $current_hash !== $last_hash ) {
        echo '<!-- Hash changed, refreshing patterns -->'; // Debug
        $registered_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
        foreach ( $registered_patterns as $pattern ) {
            if ( strpos( $pattern['name'], 'larris-theme/' ) === 0 ) {
                unregister_block_pattern( $pattern['name'] );
            }
        }
        if ( function_exists( 'wp_get_theme' ) && wp_get_theme()->is_block_theme() ) {
            foreach ( $pattern_files as $file ) {
                $slug = 'larris-theme/' . basename( $file, '.php' );
                $content = file_get_contents( $file );
                $title = 'Pattern ' . basename( $file, '.php' );
                if ( preg_match( '/\* Title: (.+)$/m', $content, $match ) ) {
                    $title = trim( $match[1] );
                }
                register_block_pattern( $slug, [
                    'title'   => $title,
                    'content' => $content,
                ] );
                echo '<!-- Registered: ' . $slug . ' -->'; // Debug
            }
        }
        wp_cache_flush();
        set_transient( $transient_key, $current_hash, WEEK_IN_SECONDS );
    } else {
        echo '<!-- No change detected -->'; // Debug
    }
}
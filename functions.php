<?php
function larris_theme_rescan() {
    // Register patterns or any theme-related code you want to re-scan.
    register_larris_theme_patterns();
}
add_action( 'init', 'larris_theme_rescan' );

<?php
if ( ! function_exists( 'veeraj_localize_script' ) ) {
    function veeraj_localize_script( $handle ) {
        wp_localize_script(
            $handle,
            'veerajPluginData',
            [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'veeraj_nonce' ), // Optional security nonce.
            ]
        );
    }
}

<?php
if ( ! function_exists( 'veeraj_localize_script' ) ) {
    function veeraj_localize_script( $handle ) {
        wp_localize_script(
            $handle,
            'veerajPluginData',
            [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'veeraj_nonce' ),
                'translations' => [
                    'tableBlockTitle' => __( 'Veeraj Table Block', 'veeraj-plugin' ),
                    'loadingMessage'  => __( 'Loading...', 'veeraj-plugin' ),
                    'errorMessage'    => __( 'Error fetching data', 'veeraj-plugin' ),
                    'noDataMessage'   => __( 'No data available', 'veeraj-plugin' ),
                    'id'   => __( 'ID', 'veeraj-plugin' ),
                    'firstName'   => __( 'First Name', 'veeraj-plugin' ),
                    'lastName'   => __( 'Last Name', 'veeraj-plugin' ),
                    'email'   => __( 'Email', 'veeraj-plugin' ),
                    'date'   => __( 'Date', 'veeraj-plugin' ),
                    
                ],
            ]
        );
    }
}

<?php
/**
 * Admin Page Functionality
 *
 * @package Veeraj\VeerajPlugin
 */

namespace Veeraj\VeerajPlugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AdminPage {

    /**
     * Constructor.
     */
    public function initialize() {
        // Add hooks specific to the admin page.
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Add admin menu page for the plugin.
     */
    public function add_admin_page() {
        add_menu_page(
            __( 'Veeraj Plugin', 'veeraj-plugin' ),
            __( 'Veeraj Plugin', 'veeraj-plugin' ),
            'manage_options',
            'veeraj-plugin',
            [ $this, 'render_admin_page' ],
            'dashicons-clipboard',
            100
        );
    }

    /**
     * Render the admin page content.
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Veeraj Plugin - Fetch Data', 'veeraj-plugin' ); ?></h1>

            <div class="veeraj-card">
                <p><?php esc_html_e( 'Below is the latest data fetched from the API. Click the refresh button to update the data.', 'veeraj-plugin' ); ?></p>

                <!-- Button to refresh data -->
                <button id="refresh-data-button" class="button-primary">
                    <?php esc_html_e( 'Refresh Data', 'veeraj-plugin' ); ?>
                </button>

                <!-- Container to display the fetched data -->
                <div id="data-container" class="veeraj-data-container">
                    <p><?php esc_html_e( 'Data will appear here after refreshing.', 'veeraj-plugin' ); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_veeraj-plugin' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'veeraj-plugin-admin-style',
            VEERAJ_PLUGIN_URL . 'assets/build/css/admin.bundle.css',
            [],
            VEERAJ_PLUGIN_VERSION
        );

        wp_enqueue_script(
            'veeraj-plugin-admin-script',
            VEERAJ_PLUGIN_URL . 'assets/build/js/admin.bundle.js',
            [ 'jquery' ],
            VEERAJ_PLUGIN_VERSION,
            true
        );

        wp_localize_script(
            'veeraj-plugin-admin-script',
            'veerajPluginData',
            [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            ]
        );
    }
}

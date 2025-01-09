<?php
/**
 * WP-CLI Commands for Veeraj Plugin.
 *
 * @package Veeraj\VeerajPlugin
 */

namespace Veeraj\VeerajPlugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    /**
     * Register WP-CLI commands.
     */
    class WP_CLI {

        /**
         * Register WP-CLI commands for the plugin.
         */
        public static function register_commands() {
            // Register the refresh-data command.
            \WP_CLI::add_command( 'veeraj refresh-data', [ __CLASS__, 'refresh_data' ] );
        }

        /**
         * Refresh the data stored in transient.
         */
        public static function refresh_data() {
            // Clear the cache and force a refresh of the data.
            delete_transient( 'veeraj_api_data' );
            update_option( 'veeraj_next_refresh_time', '' ); // Optionally reset the next refresh time.

            // Log success
            veeraj_debug_log( 'Cache cleared and data will be fetched again on the next request.' );

            // Output success message to the user
            \WP_CLI::success( 'Data cache cleared successfully and will be refreshed on the next request.' );
        }
    }

    // Register the WP-CLI commands when WP_CLI is available.
    \Veeraj\VeerajPlugin\WP_CLI::register_commands();
}

<?php
/**
 * Main Plugin Class
 *
 * @package Veeraj\VeerajPlugin
 */

namespace Veeraj\VeerajPlugin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include the AdminPage class file.
require_once VEERAJ_PLUGIN_DIR . 'includes/admin-page.php';

// Include the TableBlock class file.
require_once VEERAJ_PLUGIN_DIR . 'includes/table-block.php';

class Main {

    /**
     * Constructor.
     */
    public function __construct() {
        // Plugin initialization code can go here if needed.
    }

    /**
     * Run the plugin.
     *
     * This function initializes hooks and actions.
     */
    public function run() {
        $this->define_hooks();
    }

    /**
     * Define the hooks and actions.
     */
    private function define_hooks() {

        // Initialize the Admin Page functionality.
        $admin_page = new AdminPage();
        $admin_page->initialize();

        // Register the Gutenberg Block functionality.
        $table_block = new TableBlock();
        $table_block->register();

        // Load plugin textdomain.
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

        // AJAX endpoints.
        add_action( 'wp_ajax_get_veeraj_data', [ $this, 'fetch_veeraj_data' ] ); // For logged-in users
        add_action( 'wp_ajax_nopriv_get_veeraj_data', [ $this, 'fetch_veeraj_data' ] ); // For non-logged-in users

    }

    /**
     * Load plugin textdomain for translations.
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'veeraj-plugin',
            false,
            basename( VEERAJ_PLUGIN_DIR ) . '/languages'
        );
    }   

   /**
     * Fetch data from the remote API and store it in a transient.
     */
    public function fetch_veeraj_data() {
        $force_refresh = isset( $_POST['force_refresh'] ) && $_POST['force_refresh'] === 'true'; // Check if force refresh is requested.
        veeraj_debug_log( 'Force refresh: ' . ( $force_refresh ? 'true' : 'false' ) );
    
        $current_time = current_time( 'mysql' );
    
        $next_refresh_time = get_option( 'veeraj_next_refresh_time' );
    
        // If force refresh or next refresh time has passed, fetch fresh data.
        if ( $force_refresh || empty( $next_refresh_time ) || strtotime( $current_time ) > strtotime( $next_refresh_time ) ) {
            veeraj_debug_log( 'Fetching fresh data from API.' );
    
            $next_refresh_time = date( 'Y-m-d H:i:s', strtotime( '+5 minutes', strtotime( $current_time ) ) );
            update_option( 'veeraj_next_refresh_time', $next_refresh_time );
            veeraj_debug_log( 'Updated next refresh time: ' . $next_refresh_time );
    
            $response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
    
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                veeraj_debug_log( 'API request error: ' . $error_message );
                wp_send_json_error( [ 'error' => $error_message ], 500 );
            }
    
            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );
    
            if ( 200 === $response_code && ! empty( $response_body ) ) {
                $data = json_decode( $response_body, true );
    
                if ( json_last_error() === JSON_ERROR_NONE ) {
                    set_transient( 'veeraj_api_data', $data, 5 * MINUTE_IN_SECONDS );
                    veeraj_debug_log( 'Data cached successfully.' );
                    wp_send_json_success( $data );
                } else {
                    $json_error = json_last_error_msg();
                    veeraj_debug_log( 'JSON decoding error: ' . $json_error );
                    wp_send_json_error( [ 'error' => 'JSON decoding error.' ], 500 );
                }
            } else {
                veeraj_debug_log( 'API response error or empty body.' );
                wp_send_json_error( [ 'error' => 'API response error.' ], 500 );
            }
        }
    
        // If not a force refresh and cache exists, return cached data.
        $cached_data = get_transient( 'veeraj_api_data' );
        if ( $cached_data ) {
            veeraj_debug_log( 'Returning cached data. next refresh time: ' . $next_refresh_time );
            wp_send_json_success( $cached_data );
        } else {
            veeraj_debug_log( 'No cached data available.' );
            wp_send_json_error( [ 'error' => 'No cached data available.' ], 500 );
        }
    }

}

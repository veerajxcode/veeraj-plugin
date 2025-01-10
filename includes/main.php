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

        // Load plugin textdomain.
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

        // AJAX endpoints.
        add_action( 'wp_ajax_get_veeraj_data', [ $this, 'fetch_veeraj_data' ] ); // For logged-in users
        add_action( 'wp_ajax_nopriv_get_veeraj_data', [ $this, 'fetch_veeraj_data' ] ); // For non-logged-in users

        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_assets' ] );
        //add_action('rest_api_init', [$this, 'register_routes']);
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

    public function enqueue_block_assets() {
        wp_enqueue_script(
            'veeraj-block-script',
            VEERAJ_PLUGIN_URL .'assets/js/dist/block.bundle.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ), // Dependencies
            VEERAJ_PLUGIN_VERSION,
            true
        );

        wp_localize_script(
            'veeraj-block-script',
            'veeraj_ajax',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            ]
        );

    }

    public function register_routes()
    {
        register_rest_route('veeraj/v1', '/data', [
            'methods' => 'GET',
            'callback' => [$this, 'fetch_veeraj_data'],
        ]);
    }
    

   /**
     * Fetch data from the remote API and store it in a transient.
     */
    public function fetch_veeraj_data() {
        // Get the current timestamp
        $current_time = current_time( 'mysql' );

        // Check if the next refresh time is already set
        $next_refresh_time = get_option( 'veeraj_next_refresh_time' );

        // If the next refresh time isn't set or has passed, calculate and set it
        if ( empty( $next_refresh_time ) || strtotime( $current_time ) > strtotime( $next_refresh_time ) ) {
            // Calculate the next refresh time (5 minutes from now)
            $next_refresh_time = date( 'Y-m-d H:i:s', strtotime( '+5 minutes', strtotime( $current_time ) ) );

            // Store the next refresh time in an option
            update_option( 'veeraj_next_refresh_time', $next_refresh_time );

            // Log the next refresh time
            veeraj_debug_log( "Next refresh time is set to: $next_refresh_time" );
        }

        // Check if data is already cached
        $cached_data = get_transient( 'veeraj_api_data' );

        if ( false === $cached_data ) {
            // Fetch fresh data from the API
            $response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );

            if ( is_wp_error( $response ) ) {
                // Log and return an error if the API request fails
                $error_message = $response->get_error_message();
                veeraj_debug_log( "API fetch failed: $error_message" );
                wp_send_json_error( [ 'error' => $error_message ], 500 );
            }

            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );

            if ( 200 === $response_code && ! empty( $response_body ) ) {
                $data = json_decode( $response_body, true );

                if ( json_last_error() === JSON_ERROR_NONE ) {
                    // Cache the data for 5 minutes
                    set_transient( 'veeraj_api_data', $data, 5 * MINUTE_IN_SECONDS );

                    // Log the data fetch and next refresh time
                    veeraj_debug_log( "Data fetched at $current_time. Next data fetch will occur at $next_refresh_time." );

                    // Return the fetched data
                    wp_send_json_success( $data );
                } else {
                    // Handle JSON decoding errors
                    veeraj_debug_log( "JSON decoding error: " . json_last_error_msg() );
                    wp_send_json_error( [ 'error' => 'Failed to decode API response.' ], 500 );
                }
            } else {
                // Handle non-200 responses or empty response bodies
                veeraj_debug_log( "API responded with code $response_code or empty body." );
                wp_send_json_error( [ 'error' => 'API fetch failed.' ], 500 );
            }
        } else {
            // Log that data is fetched from cache
            veeraj_debug_log( "Data fetched from cache at $current_time. Fresh data will fetch after $next_refresh_time." );

            // Return cached data
            wp_send_json_success( $cached_data );
        }
    }

}

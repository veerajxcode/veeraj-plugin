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

        // Register block with dynamic rendering.
        add_action( 'init', function() {
            register_block_type( 'veeraj/table-block', [
                'render_callback' => [ $this, 'render_table_block' ],
            ] );
        } );
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

        if ( ! is_admin() ) {
            wp_enqueue_script(
                'veeraj-frontend-script',
                VEERAJ_PLUGIN_URL . 'assets/js/dist/table-frontend.bundle.js',
                [],
                VEERAJ_PLUGIN_VERSION,
                true
            );
    
            wp_enqueue_style(
                'veeraj-frontend-style',
                VEERAJ_PLUGIN_URL . 'assets/css/gutenberg-block/frontend-style.css',
                [],
                VEERAJ_PLUGIN_VERSION
            );
        }

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
    

   /**
     * Fetch data from the remote API and store it in a transient.
     */
    public function fetch_veeraj_data() {
        $force_refresh = isset( $_POST['force_refresh'] ) && $_POST['force_refresh'] === 'true'; // Check if force refresh is requested.
        $current_time = current_time( 'mysql' );
        $next_refresh_time = get_option( 'veeraj_next_refresh_time' );
    
        // If force refresh or next refresh time has passed, fetch fresh data.
        if ( $force_refresh || empty( $next_refresh_time ) || strtotime( $current_time ) > strtotime( $next_refresh_time ) ) {
            $next_refresh_time = date( 'Y-m-d H:i:s', strtotime( '+5 minutes', strtotime( $current_time ) ) );
            update_option( 'veeraj_next_refresh_time', $next_refresh_time );
    
            $response = wp_remote_get( 'https://miusage.com/v1/challenge/1/' );
    
            if ( is_wp_error( $response ) ) {
                wp_send_json_error( [ 'error' => $response->get_error_message() ], 500 );
            }
    
            $response_code = wp_remote_retrieve_response_code( $response );
            $response_body = wp_remote_retrieve_body( $response );
    
            if ( 200 === $response_code && ! empty( $response_body ) ) {
                $data = json_decode( $response_body, true );
    
                if ( json_last_error() === JSON_ERROR_NONE ) {
                    set_transient( 'veeraj_api_data', $data, 5 * MINUTE_IN_SECONDS );
                    wp_send_json_success( $data );
                } else {
                    wp_send_json_error( [ 'error' => 'JSON decoding error.' ], 500 );
                }
            } else {
                wp_send_json_error( [ 'error' => 'API response error.' ], 500 );
            }
        }
    
        // If not a force refresh and cache exists, return cached data.
        $cached_data = get_transient( 'veeraj_api_data' );
        if ( $cached_data ) {
            wp_send_json_success( $cached_data );
        } else {
            wp_send_json_error( [ 'error' => 'No cached data available.' ], 500 );
        }
    }

    /**
     * Render callback for the Veeraj Table Block.
     *
     * @param array $attributes Block attributes.
     * @return string HTML content of the table block.
     */
    public function render_table_block( $attributes ) {
        // Get the visibility settings for columns.
        $visible_columns = $attributes['visibleColumns'] ?? [
            'id'    => true,
            'fname' => true,
            'lname' => true,
            'email' => true,
            'date'  => true,
        ];

        // Fetch cached data.
        $cached_data = get_transient( 'veeraj_api_data' );
        if ( false === $cached_data ) {
            return '<p>' . esc_html__( 'Data is currently unavailable.', 'veeraj-plugin' ) . '</p>';
        }

        // Start building the table HTML.
        ob_start();
        ?>
        <div class="veeraj-table-wrapper">
            <table class="veeraj-table">
                <thead>
                    <tr>
                        <?php if ( $visible_columns['id'] ) : ?><th><?php esc_html_e( 'ID', 'veeraj-plugin' ); ?></th><?php endif; ?>
                        <?php if ( $visible_columns['fname'] ) : ?><th><?php esc_html_e( 'First Name', 'veeraj-plugin' ); ?></th><?php endif; ?>
                        <?php if ( $visible_columns['lname'] ) : ?><th><?php esc_html_e( 'Last Name', 'veeraj-plugin' ); ?></th><?php endif; ?>
                        <?php if ( $visible_columns['email'] ) : ?><th><?php esc_html_e( 'Email', 'veeraj-plugin' ); ?></th><?php endif; ?>
                        <?php if ( $visible_columns['date'] ) : ?><th><?php esc_html_e( 'Date', 'veeraj-plugin' ); ?></th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $cached_data['data']['rows'] as $row ) : ?>
                        <tr>
                            <?php if ( $visible_columns['id'] ) : ?><td><?php echo esc_html( $row['id'] ); ?></td><?php endif; ?>
                            <?php if ( $visible_columns['fname'] ) : ?><td><?php echo esc_html( $row['fname'] ); ?></td><?php endif; ?>
                            <?php if ( $visible_columns['lname'] ) : ?><td><?php echo esc_html( $row['lname'] ); ?></td><?php endif; ?>
                            <?php if ( $visible_columns['email'] ) : ?><td><?php echo esc_html( $row['email'] ); ?></td><?php endif; ?>
                            <?php if ( $visible_columns['date'] ) : ?><td><?php echo esc_html( date( 'Y-m-d H:i:s', $row['date'] ) ); ?></td><?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

}

<?php
/**
 * Plugin Name: Veeraj Plugin
 * Plugin URI: #
 * Description: A custom WordPress plugin for retrieving and displaying data from a remote API.
 * Version: 1.0.0
 * Author: Veeraj Yadav
 * Author URI: #
 * Text Domain: veeraj-plugin
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants.
define( 'VEERAJ_PLUGIN_VERSION', '1.0.0' );
define( 'VEERAJ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VEERAJ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Debugging helper function.
if ( ! function_exists( 'veeraj_debug_log' ) ) {
    function veeraj_debug_log( $message ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            if ( is_array( $message ) || is_object( $message ) ) {
                $message = print_r( $message, true );
            }
            error_log( '[Veeraj Plugin]: ' . $message );
        }
    }
}

// Load Composer autoload if available.
if ( file_exists( VEERAJ_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require_once VEERAJ_PLUGIN_DIR . 'vendor/autoload.php';
    veeraj_debug_log( 'Composer autoload file loaded successfully.' );
} else {
    veeraj_debug_log( 'Composer autoload file not found at ' . VEERAJ_PLUGIN_DIR . 'vendor/autoload.php' );
}

// Load common scripts.
require_once VEERAJ_PLUGIN_DIR . 'includes/common-scripts.php';

// Check if WP-CLI is available.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once VEERAJ_PLUGIN_DIR . 'includes/wp-cli.php'; // Include WP-CLI commands file.
}

// Initialize the plugin.
function veeraj_plugin_init() {
    if ( ! class_exists( '\Veeraj\VeerajPlugin\Main' ) ) {
        veeraj_debug_log( 'Class \Veeraj\VeerajPlugin\Main not found. Autoload may have failed.' );
        wp_die( __( 'Plugin initialization failed. Check the debug log for details.', 'veeraj-plugin' ) );
    }

    $plugin = new \Veeraj\VeerajPlugin\Main();
    //veeraj_debug_log( 'Plugin instance created successfully.' );

    $plugin->run();
    //veeraj_debug_log( 'Plugin run() method executed.' );
}
add_action( 'plugins_loaded', 'veeraj_plugin_init' );

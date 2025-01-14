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
     * Initialize the admin page functionality.
     */
    public function initialize() {
        // Add hooks specific to the admin page.
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );

        // Admin footer text.
		add_filter( 'admin_footer_text', [ $this, 'veeraj_get_admin_footer' ], 1, 2 );

        // Outputs the plugin version in the admin footer.
		add_filter( 'update_footer', [ $this, 'veeraj_display_update_footer' ], PHP_INT_MAX );

        // Outputs the plugin admin header.
        add_action( 'in_admin_header', [ $this, 'veeraj_display_admin_header' ], 100 );

        // Outputs the plugin admin footer.
        add_action( 'in_admin_footer', [ $this, 'veeraj_display_admin_footer' ] );

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Add admin menu page for the plugin.
     */
    public function add_admin_page() {
        add_menu_page(
            esc_html__( 'Veeraj Plugin', 'veeraj-plugin' ),
            esc_html__( 'Veeraj Plugin', 'veeraj-plugin' ),
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

        <div class="wrap" id="veeraj-plugin">
            <div class="veeraj-plugin-page veeraj-plugin-page-general veeraj-plugin-page-nw-product-edu veeraj-plugin-tab-settings">
                <div class="veeraj-plugin-page-title">
                    <a href="#" class="tab active">
                        <?php esc_html_e( 'Table', 'veeraj-plugin' ); ?>
                    </a>
                </div>

                <div class="veeraj-plugin-page-content">
                    <h1 class="screen-reader-text">
                        <?php esc_html_e( 'General', 'veeraj-plugin' ); ?>
                    </h1>
                </div>
            </div>
            <div class="veeraj-card">
                <p class="desc veeraj-plugin-text"><?php esc_html_e( 'Below is the latest data fetched from the API. Click the refresh button to update the data.', 'veeraj-plugin' ); ?></p>

                <!-- Button to refresh data -->
                <button id="refresh-data-button" class="veeraj-plugin-btn veeraj-plugin-btn-md veeraj-plugin-btn-orange">
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

    public function veeraj_get_admin_footer( $text ) {

		if ( $this->is_admin_page() ) {
			$url = '#';

			$text = sprintf(
				wp_kses(
					/* translators: %1$s - WP.org link; %2$s - same WP.org link. */
					__( 'Please rate <strong>WP Mail SMTP</strong> <a href="%1$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you from the WP Mail SMTP team!', 'veeraj-plugin' ),
					array(
						'strong' => array(),
						'a'      => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				$url,
				$url
			);
		}

		return $text;
	}

    /**
     * Display plugin version in footer
     */
    public function veeraj_display_update_footer( $text ) {

		if ( $this->is_admin_page() ) {
			return 'WP Mail SMTP 4.3.0';
		}

		return $text;
	}

    /**
     * Display a custom header for the admin page.
     */
    public function veeraj_display_admin_header() {
        // Bail if we're not on a plugin page.
        if ( ! $this->is_admin_page() ) {
            return;
        }
        ?>

        <div id="veeraj-header-temp"></div>
        <div id="veeraj-header">
            <img class="veeraj-header-logo" src="<?php echo esc_url( VEERAJ_PLUGIN_URL ); ?>assets/images/logo.svg" alt="Veeraj Plugin"/>
        </div>

        <?php
    }

    /**
     * Display a custom footer for the admin page.
     */
    public function veeraj_display_admin_footer() {
        if ( ! $this->is_admin_page() ) {
            return;
        }
        ?>
        <div class="veeraj-footer-promotion">
            <p><?php esc_html_e( 'Made with ♥ by the WP Mail SMTP team', 'veeraj-plugin' ); ?></p>
            <ul class="veeraj-footer-promotion-links">
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Support', 'veeraj-plugin' ); ?>
                    </a>
                    <span>/</span>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Docs', 'veeraj-plugin' ); ?>
                    </a>
                    <span>/</span>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Free Plugin', 'veeraj-plugin' ); ?>
                    </a>
                </li>
            </ul>
            <ul class="veeraj-footer-promotion-social">
				<li>
					<a href="#" target="_blank" rel="noopener noreferrer">
						<svg width="16" height="16" aria-hidden="true">
							<path fill="#A7AAAD" d="M16 8.05A8.02 8.02 0 0 0 8 0C3.58 0 0 3.6 0 8.05A8 8 0 0 0 6.74 16v-5.61H4.71V8.05h2.03V6.3c0-2.02 1.2-3.15 3-3.15.9 0 1.8.16 1.8.16v1.98h-1c-1 0-1.31.62-1.31 1.27v1.49h2.22l-.35 2.34H9.23V16A8.02 8.02 0 0 0 16 8.05Z"/>
						</svg>
						<span class="screen-reader-text"><?php echo esc_html( 'Facebook' ); ?></span>
					</a>
				</li>
				<li>
					<a href="#" target="_blank" rel="noopener noreferrer">
						<svg width="17" height="16" aria-hidden="true">
							<path fill="#A7AAAD" d="M15.27 4.43A7.4 7.4 0 0 0 17 2.63c-.6.27-1.3.47-2 .53a3.41 3.41 0 0 0 1.53-1.93c-.66.4-1.43.7-2.2.87a3.5 3.5 0 0 0-5.96 3.2 10.14 10.14 0 0 1-7.2-3.67C.86 2.13.7 2.73.7 3.4c0 1.2.6 2.26 1.56 2.89a3.68 3.68 0 0 1-1.6-.43v.03c0 1.7 1.2 3.1 2.8 3.43-.27.06-.6.13-.9.13a3.7 3.7 0 0 1-.66-.07 3.48 3.48 0 0 0 3.26 2.43A7.05 7.05 0 0 1 0 13.24a9.73 9.73 0 0 0 5.36 1.57c6.42 0 9.91-5.3 9.91-9.92v-.46Z"/>
						</svg>
						<span class="screen-reader-text"><?php echo esc_html( 'Twitter' ); ?></span>
					</a>
				</li>
				<li>
					<a href="#" target="_blank" rel="noopener noreferrer">
						<svg width="17" height="16" aria-hidden="true">
							<path fill="#A7AAAD" d="M16.63 3.9a2.12 2.12 0 0 0-1.5-1.52C13.8 2 8.53 2 8.53 2s-5.32 0-6.66.38c-.71.18-1.3.78-1.49 1.53C0 5.2 0 8.03 0 8.03s0 2.78.37 4.13c.19.75.78 1.3 1.5 1.5C3.2 14 8.51 14 8.51 14s5.28 0 6.62-.34c.71-.2 1.3-.75 1.49-1.5.37-1.35.37-4.13.37-4.13s0-2.81-.37-4.12Zm-9.85 6.66V5.5l4.4 2.53-4.4 2.53Z"/>
						</svg>
						<span class="screen-reader-text"><?php echo esc_html( 'YouTube' ); ?></span>
					</a>
				</li>
			</ul>
        </div>
        <?php
    }

    /**
     * Check if the current page is the plugin's admin page.
     *
     * @return bool
     */
    private function is_admin_page() {
        $current_screen = get_current_screen();

        // Ensure $current_screen is valid before checking.
        if ( ! $current_screen ) {
            return false;
        }

        return isset( $current_screen->id ) && 'toplevel_page_veeraj-plugin' === $current_screen->id;
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_veeraj-plugin' !== $hook ) {
            return;
        }

        // Set general body class.
		add_filter(
			'admin_body_class',
			function ( $classes ) {
				$classes = ' veeraj-plugin-admin-page-body';

				return $classes;
			}
		);

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

        veeraj_localize_script( 'veeraj-plugin-admin-script' );
    }
}

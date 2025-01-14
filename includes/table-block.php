<?php
/**
 * Table Block Functionality
 *
 * @package Veeraj\VeerajPlugin
 */

namespace Veeraj\VeerajPlugin;

class TableBlock {

    /**
     * Register hooks and actions.
     */
    public function register() {
        // Enqueue block editor assets.
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_assets' ] );

    }

    /**
     * Enqueue assets for the block editor.
     */
    public function enqueue_block_assets() {
        wp_enqueue_script(
            'veeraj-block-script',
            VEERAJ_PLUGIN_URL . 'assets/build/js/table.bundle.js',
            [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ],
            VEERAJ_PLUGIN_VERSION,
            true
        );

        veeraj_localize_script('veeraj-block-script');
    }


    /**
     * Render callback for the Veeraj Table Block.
     *
     * @param array $attributes Block attributes.
     * @return string HTML content of the table block.
     */
    public function render_table_block( $attributes ) {
        $visible_columns = $attributes['visibleColumns'] ?? [
            'id'    => true,
            'fname' => true,
            'lname' => true,
            'email' => true,
            'date'  => true,
        ];

        $cached_data = get_transient( 'veeraj_api_data' );
        if ( false === $cached_data ) {
            return '<p>' . esc_html__( 'Data is currently unavailable.', 'veeraj-plugin' ) . '</p>';
        }

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

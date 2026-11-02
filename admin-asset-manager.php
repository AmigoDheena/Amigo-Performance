<?php
/**
 * @package amigo-performance
 * Asset Manager Admin Page
 * Version: 3.3
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get Asset Manager stats and data
$amigoperf_asset_stats   = $plugin_instance->get_asset_manager_stats();
$amigoperf_all_assets    = $plugin_instance->get_all_managed_assets();
$amigoperf_total_assets  = max( 1, (int) $amigoperf_asset_stats['total_assets'] );
$amigoperf_disabled_num  = (int) $amigoperf_asset_stats['dequeued_assets'];
$amigoperf_disabled_pct  = round( ( $amigoperf_disabled_num / $amigoperf_total_assets ) * 100 );

// Helper function to truncate URLs
if ( ! function_exists( 'amigoperf_truncate_url' ) ) {
    function amigoperf_truncate_url( $url, $length = 50 ) {
        if ( strlen( $url ) <= $length ) {
            return $url;
        }
        return substr( $url, 0, $length ) . '...';
    }
}
?>

<div class="amigo-wrapper amigo-am">

    <!-- Header Section -->
    <div class="amigo-header">
        <div class="amigo-header-content">
            <div class="amigo-logo">
                <div class="amigo-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 10V3.5L18 6V10C18 11.66 16.66 13 15 13C13.34 13 12 11.66 13 10Z" fill="rgba(255,255,255,0.9)"/>
                        <path d="M6 15V8.5L11 11V15C11 16.66 9.66 18 8 18C6.34 18 5 16.66 6 15Z" fill="rgba(255,255,255,0.6)"/>
                        <path d="M17 20V13.5L22 16V20C22 21.66 20.66 23 19 23C17.34 23 16 21.66 17 20Z" fill="rgba(255,255,255,0.4)"/>
                        <path d="M1 20V13.5L6 16V20C6 21.66 4.66 23 3 23C1.34 23 0 21.66 1 20Z" fill="rgba(255,255,255,0.4)"/>
                    </svg>
                </div>
                <div class="amigo-title-section">
                    <h1 class="amigo-title"><?php echo esc_html( $plugin_instance->amigoPerf_PluginName ); ?></h1>
                    <p class="amigo-subtitle"><?php esc_html_e( 'Asset Manager — Control CSS & JS per page', 'amigo-performance' ); ?></p>
                </div>
            </div>
            <div class="amigo-header-meta">
                <span class="amigo-version-badge">v<?php echo esc_html( AMIGOPERF_PLUGIN_VERSION ); ?></span>
                <span class="amigo-header-status">
                    <?php
                    /* translators: 1: disabled asset count, 2: total asset count */
                    printf( esc_html__( '%1$d / %2$d disabled', 'amigo-performance' ), intval( $amigoperf_disabled_num ), intval( $amigoperf_asset_stats['total_assets'] ) );
                    ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="amigo-main">
        <?php
        if ( isset( $_GET['updated'] ) && $_GET['updated'] == '1' && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'amigoperf_asset_updated' ) ) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Asset status updated successfully.', 'amigo-performance' ) . '</p></div>';
        }
        if ( isset( $_GET['deleted'] ) && $_GET['deleted'] == '1' && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'amigoperf_asset_deleted' ) ) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Asset deleted successfully.', 'amigo-performance' ) . '</p></div>';
        }
        ?>

        <div class="amigo-content">

            <!-- Status Overview Bar -->
            <div class="amigo-am-overview">
                <div class="amigo-am-overview-left">
                    <div class="amigo-am-overview-ring">
                        <svg viewBox="0 0 36 36" width="56" height="56">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="#e2e8f0" stroke-width="2.5" />
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="url(#amigo-am-gradient)" stroke-width="2.5"
                                  stroke-dasharray="<?php echo esc_attr( $amigoperf_disabled_pct ); ?>, 100"
                                  stroke-linecap="round" />
                            <defs>
                                <linearGradient id="amigo-am-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#667eea" />
                                    <stop offset="100%" stop-color="#764ba2" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="amigo-am-overview-number"><?php echo esc_html( $amigoperf_disabled_num ); ?></span>
                    </div>
                    <div class="amigo-am-overview-text">
                        <strong><?php esc_html_e( 'Assets Disabled', 'amigo-performance' ); ?></strong>
                        <span>
                            <?php
                            /* translators: %d: total tracked assets */
                            printf( esc_html__( 'out of %d tracked assets', 'amigo-performance' ), intval( $amigoperf_asset_stats['total_assets'] ) );
                            ?>
                        </span>
                    </div>
                </div>
                <div class="amigo-am-overview-actions">
                    <a href="<?php echo esc_url( home_url() ); ?>" target="_blank" rel="noopener noreferrer" class="amigo-btn amigo-btn-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M18 13V19C18 20.1 17.1 21 16 21H5C3.9 21 3 20.1 3 19V8C3 6.9 3.9 6 5 6H11" stroke="currentColor" stroke-width="2"/><path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/><path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/></svg>
                        <?php esc_html_e( 'Visit Site', 'amigo-performance' ); ?>
                    </a>
                </div>
            </div>

            <!-- Compact Stats -->
            <div class="amigo-am-stats-bar">
                <div class="amigo-stat-card amigo-am-stat-chip">
                    <span class="amigo-stat-number"><?php echo esc_html( $amigoperf_asset_stats['total_assets'] ); ?></span>
                    <span class="amigo-am-stat-label"><?php esc_html_e( 'Total', 'amigo-performance' ); ?></span>
                </div>
                <div class="amigo-stat-card amigo-am-stat-chip">
                    <span class="amigo-stat-number"><?php echo esc_html( $amigoperf_asset_stats['dequeued_assets'] ); ?></span>
                    <span class="amigo-am-stat-label"><?php esc_html_e( 'Disabled', 'amigo-performance' ); ?></span>
                </div>
                <div class="amigo-stat-card amigo-am-stat-chip">
                    <span class="amigo-stat-number"><?php echo esc_html( $amigoperf_asset_stats['unique_pages'] ); ?></span>
                    <span class="amigo-am-stat-label"><?php esc_html_e( 'Pages', 'amigo-performance' ); ?></span>
                </div>
                <div class="amigo-stat-card amigo-am-stat-chip">
                    <span class="amigo-stat-number"><?php echo esc_html( $amigoperf_asset_stats['css_assets'] ); ?></span>
                    <span class="amigo-am-stat-label"><?php esc_html_e( 'CSS', 'amigo-performance' ); ?></span>
                </div>
                <div class="amigo-stat-card amigo-am-stat-chip">
                    <span class="amigo-stat-number"><?php echo esc_html( $amigoperf_asset_stats['js_assets'] ?? 0 ); ?></span>
                    <span class="amigo-am-stat-label"><?php esc_html_e( 'JS', 'amigo-performance' ); ?></span>
                </div>
            </div>

            <?php if ( empty( $amigoperf_all_assets ) ) : ?>

                <!-- Empty State -->
                <div class="amigo-empty-state">
                    <div class="amigo-empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2L2 7V10C2 16 6 20.5 12 22C18 20.5 22 16 22 10V7L12 2Z" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M8 12L11 14L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3><?php esc_html_e( 'No Assets Managed Yet', 'amigo-performance' ); ?></h3>
                    <p><?php esc_html_e( 'Visit pages on your website and use the admin bar Asset Manager to start managing assets per page.', 'amigo-performance' ); ?></p>
                    <a href="<?php echo esc_url( home_url() ); ?>" class="amigo-btn amigo-btn-primary" target="_blank">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M18 13V19C18 19.5523 17.5523 20 17 20H5C4.44772 20 4 19.5523 4 19V7C4 6.44772 4.44772 6 5 6H11" stroke="currentColor" stroke-width="2"/>
                            <path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/>
                            <path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <?php esc_html_e( 'Visit Homepage', 'amigo-performance' ); ?>
                    </a>
                </div>

                <!-- How to Use Section -->
                <div class="amigo-core-section">
                    <div class="amigo-core-section-label">
                        <span class="amigo-core-section-dot" style="background: linear-gradient(135deg, #43e97b, #38f9d7);"></span>
                        <h2><?php esc_html_e( 'How to Use Asset Manager', 'amigo-performance' ); ?></h2>
                        <p><?php esc_html_e( 'Step-by-step guide to manage assets effectively.', 'amigo-performance' ); ?></p>
                    </div>
                    <div class="amigo-card">
                        <div class="amigo-steps">
                            <div class="amigo-step">
                                <div class="amigo-step-number">1</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e( 'Visit Any Page', 'amigo-performance' ); ?></h4>
                                    <p><?php esc_html_e( 'Navigate to any page on your website while logged in as an administrator.', 'amigo-performance' ); ?></p>
                                </div>
                            </div>
                            <div class="amigo-step">
                                <div class="amigo-step-number">2</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e( 'Open Admin Bar Menu', 'amigo-performance' ); ?></h4>
                                    <p><?php esc_html_e( 'Look for the Performance menu in the admin bar, then click on Asset Manager.', 'amigo-performance' ); ?></p>
                                </div>
                            </div>
                            <div class="amigo-step">
                                <div class="amigo-step-number">3</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e( 'Toggle Assets', 'amigo-performance' ); ?></h4>
                                    <p><?php esc_html_e( 'Check/uncheck CSS and JS files to enable or disable them on that specific page.', 'amigo-performance' ); ?></p>
                                </div>
                            </div>
                            <div class="amigo-step">
                                <div class="amigo-step-number">4</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e( 'Test Performance', 'amigo-performance' ); ?></h4>
                                    <p><?php esc_html_e( 'Refresh the page to see changes. Use tools like GTmetrix or PageSpeed Insights to measure improvement.', 'amigo-performance' ); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else : ?>

                <!-- Asset Management by Page -->
                <div class="amigo-core-section">
                    <div class="amigo-core-section-label">
                        <span class="amigo-core-section-dot" style="background: linear-gradient(135deg, #667eea, #764ba2);"></span>
                        <h2><?php esc_html_e( 'Managed Assets by Page', 'amigo-performance' ); ?></h2>
                        <p><?php esc_html_e( 'Click on a page to expand and manage its CSS & JS assets.', 'amigo-performance' ); ?></p>
                    </div>

                    <div class="amigo-card amigo-am-accordion-card">
                        <div class="amigo-accordion-container">
                            <?php
                            // Group assets by page URL
                            $amigoperf_assets_by_page = array();
                            foreach ( $amigoperf_all_assets as $amigoperf_asset ) {
                                $amigoperf_assets_by_page[ $amigoperf_asset->page_url ][] = $amigoperf_asset;
                            }

                            $amigoperf_page_index = 0;
                            foreach ( $amigoperf_assets_by_page as $amigoperf_page_url => $amigoperf_page_assets ) :
                                $amigoperf_page_index++;
                                $amigoperf_disabled_count = count( array_filter( $amigoperf_page_assets, function( $asset ) { return $asset->is_dequeued; } ) );
                                $amigoperf_enabled_count  = count( $amigoperf_page_assets ) - $amigoperf_disabled_count;
                                $amigoperf_css_count      = count( array_filter( $amigoperf_page_assets, function( $asset ) { return $asset->asset_type === 'css'; } ) );
                                $amigoperf_js_count       = count( array_filter( $amigoperf_page_assets, function( $asset ) { return $asset->asset_type === 'js'; } ) );
                            ?>

                            <div class="amigo-accordion-item">
                                <div class="amigo-accordion-header" onclick="toggleAccordion(<?php echo esc_attr( $amigoperf_page_index ); ?>)">
                                    <div class="amigo-page-info">
                                        <div class="amigo-page-url">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="amigo-am-page-icon">
                                                <path d="M3 7V17C3 18.1046 3.89543 19 5 19H19C20.1046 19 21 18.1046 21 17V9C21 7.89543 20.1046 7 19 7H13L11 5H5C3.89543 5 3 5.89543 3 7Z" stroke="currentColor" stroke-width="2"/>
                                            </svg>
                                            <strong><?php echo esc_html( amigoperf_truncate_url( $amigoperf_page_url, 60 ) ); ?></strong>
                                            <a href="<?php echo esc_url( $amigoperf_page_url ); ?>" target="_blank" class="amigo-external-link" title="<?php esc_attr_e( 'Open page', 'amigo-performance' ); ?>" onclick="event.stopPropagation();">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                                    <path d="M18 13V19C18 19.5523 17.5523 20 17 20H5C4.44772 20 4 19.5523 4 19V7C4 6.44772 4.44772 6 5 6H11" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="amigo-page-summary">
                                            <span class="amigo-asset-count"><?php echo esc_html( $amigoperf_css_count ); ?> CSS</span>
                                            <span class="amigo-asset-count"><?php echo esc_html( $amigoperf_js_count ); ?> JS</span>
                                            <span class="amigo-disabled-count"><?php echo esc_html( $amigoperf_disabled_count ); ?> disabled</span>

                                            <div class="amigo-bulk-actions" onclick="event.stopPropagation();">
                                                <button class="amigo-btn amigo-btn-tiny amigo-btn-success" onclick="bulkToggleAssets(<?php echo esc_attr( $amigoperf_page_index ); ?>, false)" title="<?php esc_attr_e( 'Enable all assets on this page', 'amigo-performance' ); ?>">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2"/>
                                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                    </svg>
                                                    <?php esc_html_e( 'Enable All', 'amigo-performance' ); ?>
                                                </button>
                                                <button class="amigo-btn amigo-btn-tiny amigo-btn-warning" onclick="bulkToggleAssets(<?php echo esc_attr( $amigoperf_page_index ); ?>, true)" title="<?php esc_attr_e( 'Disable all assets on this page', 'amigo-performance' ); ?>">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M15 9L9 15" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M9 9L15 15" stroke="currentColor" stroke-width="2"/>
                                                    </svg>
                                                    <?php esc_html_e( 'Disable All', 'amigo-performance' ); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="amigo-accordion-arrow">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="amigo-accordion-content" id="accordion-<?php echo esc_attr( $amigoperf_page_index ); ?>">
                                    <div class="amigo-am-assets-list">
                                        <?php foreach ( $amigoperf_page_assets as $amigoperf_asset ) : ?>
                                        <div class="amigo-asset-card <?php echo $amigoperf_asset->is_dequeued ? 'disabled' : 'enabled'; ?>">
                                            <div class="amigo-am-asset-left">
                                                <span class="amigo-asset-type-badge amigo-asset-type-<?php echo esc_attr( $amigoperf_asset->asset_type ); ?>">
                                                    <?php echo esc_html( strtoupper( $amigoperf_asset->asset_type ) ); ?>
                                                </span>
                                                <span class="amigo-am-asset-handle"><?php echo esc_html( $amigoperf_asset->asset_handle ); ?></span>
                                            </div>
                                            <div class="amigo-am-asset-center">
                                                <span class="amigo-status-indicator <?php echo $amigoperf_asset->is_dequeued ? 'disabled' : 'enabled'; ?>">
                                                    <?php if ( $amigoperf_asset->is_dequeued ) : ?>
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                            <path d="M15 9L9 15" stroke="currentColor" stroke-width="2"/>
                                                            <path d="M9 9L15 15" stroke="currentColor" stroke-width="2"/>
                                                        </svg>
                                                    <?php else : ?>
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                            <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2"/>
                                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                        </svg>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="amigo-asset-status"><?php echo $amigoperf_asset->is_dequeued ? esc_html__( 'Disabled', 'amigo-performance' ) : esc_html__( 'Enabled', 'amigo-performance' ); ?></span>
                                            </div>
                                            <div class="amigo-asset-actions">
                                                <button class="amigo-btn amigo-btn-small amigo-btn-secondary" onclick="toggleAssetStatus(<?php echo esc_attr( $amigoperf_asset->id ); ?>, <?php echo $amigoperf_asset->is_dequeued ? 'false' : 'true'; ?>)">
                                                    <?php echo $amigoperf_asset->is_dequeued ? esc_html__( 'Enable', 'amigo-performance' ) : esc_html__( 'Disable', 'amigo-performance' ); ?>
                                                </button>
                                                <button class="amigo-btn amigo-btn-small amigo-btn-danger amigo-am-btn-icon" onclick="deleteAsset(<?php echo esc_attr( $amigoperf_asset->id ); ?>)">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php
// Enqueue the Asset Manager script
wp_enqueue_script( 'amigo-asset-manager', plugins_url( '/assets/js/asset-manager.js', __FILE__ ), array(), AMIGOPERF_PLUGIN_VERSION, true );

// Localize the script with data
wp_localize_script( 'amigo-asset-manager', 'amigoAssetManager', array(
    'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
    'nonce'         => wp_create_nonce( 'amigoperf_asset_admin_action' ),
    'confirmDelete' => __( 'Are you sure you want to delete this asset entry?', 'amigo-performance' ),
    'homeUrl'       => esc_url( home_url() ),
    'i18n'          => array(
        'noAssetsYet'      => __( 'No Assets Managed Yet', 'amigo-performance' ),
        'visitPages'       => __( 'Visit pages on your website and use the admin bar Asset Manager to start managing assets per page.', 'amigo-performance' ),
        'visitHomepage'    => __( 'Visit Homepage', 'amigo-performance' ),
        'howToUse'         => __( 'How to Use Asset Manager', 'amigo-performance' ),
        'stepByStep'       => __( 'Step-by-step guide to manage assets effectively.', 'amigo-performance' ),
        'visitAnyPage'     => __( 'Visit Any Page', 'amigo-performance' ),
        'visitAnyPageDesc' => __( 'Navigate to any page on your website while logged in as an administrator.', 'amigo-performance' ),
        'openAdminBar'     => __( 'Open Admin Bar Menu', 'amigo-performance' ),
        'openAdminBarDesc' => __( 'Look for the Performance menu in the admin bar, then click on Asset Manager.', 'amigo-performance' ),
        'toggleAssets'     => __( 'Toggle Assets', 'amigo-performance' ),
        'toggleAssetsDesc' => __( 'Check/uncheck CSS and JS files to enable or disable them on that specific page.', 'amigo-performance' ),
        'testPerformance'     => __( 'Test Performance', 'amigo-performance' ),
        'testPerformanceDesc' => __( 'Refresh the page to see changes. Use tools like GTmetrix or PageSpeed Insights to measure improvement.', 'amigo-performance' ),
    ),
) );
?>

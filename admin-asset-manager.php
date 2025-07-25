<?php
/**
 * @package amigo-performance
 * Asset Manager Admin Page
 * Version: 3.2
 */
?>

<div class="amigo-wrapper">
    
    <!-- Header Section -->
    <div class="amigo-header">
        <div class="amigo-header-content">
            <div class="amigo-logo">
                <div class="amigo-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 9V3.5L15 2L17 3.5V9C17 10.11 16.11 11 15 11S13 10.11 13 9Z" stroke="#667eea" stroke-width="2" fill="#667eea"/>
                        <path d="M7 14V8.5L9 7L11 8.5V14C11 15.11 10.11 16 9 16S7 15.11 7 14Z" stroke="#667eea" stroke-width="2" fill="#764ba2"/>
                        <path d="M19 19V13.5L21 12L23 13.5V19C23 20.11 22.11 21 21 21S19 20.11 19 19Z" stroke="#667eea" stroke-width="2" fill="#f093fb"/>
                        <path d="M1 19V13.5L3 12L5 13.5V19C5 20.11 4.11 21 3 21S1 20.11 1 19Z" stroke="#667eea" stroke-width="2" fill="#f093fb"/>
                    </svg>
                </div>
                <div class="amigo-title-section">
                    <h1 class="amigo-title"><?php echo esc_html($plugin_instance->amigoPerf_PluginName); ?> - Asset Manager</h1>
                    <p class="amigo-subtitle"><?php esc_html_e('Optimize CSS and JS assets across your website', 'amigo-performance'); ?></p>
                </div>
            </div>
            <div class="amigo-version">
                <span class="amigo-version-badge">
                    v<?php echo esc_html(AMIGOPERF_PLUGIN_VERSION); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="amigo-main">
        <?php
        // Display admin notices for asset manager actions
        if (isset($_GET['updated']) && $_GET['updated'] == '1' && isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'amigoperf_asset_updated')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Asset status updated successfully.', 'amigo-performance') . '</p></div>';
        }
        
        if (isset($_GET['deleted']) && $_GET['deleted'] == '1' && isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'amigoperf_asset_deleted')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Asset deleted successfully.', 'amigo-performance') . '</p></div>';
        }
        ?>
        
        <div class="amigo-content">
            <div class="amigo-section">
                <div class="amigo-section-header">
                    <h2 class="amigo-section-title"><?php esc_html_e('Asset Manager', 'amigo-performance'); ?></h2>
                    <p class="amigo-section-desc"><?php esc_html_e('Easily control which CSS/JS files load on specific pages without any coding knowledge. Remove unnecessary assets to boost your site speed, and if anything breaks, you can quickly restore it here. This tool gives you the power to optimize your site like a developer would, but with a simple interface.', 'amigo-performance'); ?></p>
                </div>
                
                <?php
                // Get Asset Manager stats and data
                $asset_stats = $plugin_instance->get_asset_manager_stats();
                $all_assets = $plugin_instance->get_all_managed_assets();
                
                // Helper function to truncate URLs
                function amigoperf_truncate_url($url, $length = 50) {
                    if (strlen($url) <= $length) {
                        return $url;
                    }
                    return substr($url, 0, $length) . '...';
                }
                ?>
                
                <!-- Stats Cards -->
                <div class="amigo-stats-grid">
                    <div class="amigo-stat-card">
                        <div class="amigo-stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="amigo-stat-content">
                            <div class="amigo-stat-number"><?php echo esc_html($asset_stats['total_assets']); ?></div>
                            <div class="amigo-stat-label"><?php esc_html_e('Total Assets', 'amigo-performance'); ?></div>
                        </div>
                    </div>
                    
                    <div class="amigo-stat-card">
                        <div class="amigo-stat-icon disabled">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <path d="M15 9L9 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M9 9L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="amigo-stat-content">
                            <div class="amigo-stat-number"><?php echo esc_html($asset_stats['dequeued_assets']); ?></div>
                            <div class="amigo-stat-label"><?php esc_html_e('Disabled Assets', 'amigo-performance'); ?></div>
                        </div>
                    </div>
                    
                    <div class="amigo-stat-card">
                        <div class="amigo-stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M3 7V17C3 18.1046 3.89543 19 5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 21L16 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M12 17L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="amigo-stat-content">
                            <div class="amigo-stat-number"><?php echo esc_html($asset_stats['unique_pages']); ?></div>
                            <div class="amigo-stat-label"><?php esc_html_e('Managed Pages', 'amigo-performance'); ?></div>
                        </div>
                    </div>
                    
                    <div class="amigo-stat-card">
                        <div class="amigo-stat-icon css">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4V20C4 20.5523 4.44772 21 5 21H19C19.5523 21 20 20.5523 20 20V8L16 4H5C4.44772 4 4 4.44772 4 4Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M14 4V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="amigo-stat-content">
                            <div class="amigo-stat-number"><?php echo esc_html($asset_stats['css_assets']); ?></div>
                            <div class="amigo-stat-label"><?php esc_html_e('CSS Files', 'amigo-performance'); ?></div>
                        </div>
                    </div>
                    
                    <div class="amigo-stat-card">
                        <div class="amigo-stat-icon js">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4V20C4 20.5523 4.44772 21 5 21H19C19.5523 21 20 20.5523 20 20V8L16 4H5C4.44772 4 4 4.44772 4 4Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M14 4V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 15C12 14.2 11.5 13.5 10 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M10 17V13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M14 13.5V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M14 15.5C14 16.5 15 17 16 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="amigo-stat-content">
                            <div class="amigo-stat-number"><?php echo esc_html($asset_stats['js_assets'] ?? 0); ?></div>
                            <div class="amigo-stat-label"><?php esc_html_e('JS Files', 'amigo-performance'); ?></div>
                        </div>
                    </div>
                </div>
                
                <?php if (empty($all_assets)): ?>
                    <div class="amigo-empty-state">
                        <div class="amigo-empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L2 7V10C2 16 6 20.5 12 22C18 20.5 22 16 22 10V7L12 2Z" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M8 12L11 14L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('No Assets Managed Yet', 'amigo-performance'); ?></h3>
                        <p><?php esc_html_e('Visit pages on your website and use the admin bar Asset Manager to start managing assets per page.', 'amigo-performance'); ?></p>
                        <a href="<?php echo esc_url(home_url()); ?>" class="amigo-btn amigo-btn-primary" target="_blank">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M18 13V19C18 19.5523 17.5523 20 17 20H5C4.44772 20 4 19.5523 4 19V7C4 6.44772 4.44772 6 5 6H11" stroke="currentColor" stroke-width="2"/>
                                <path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/>
                                <path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <?php esc_html_e('Visit Homepage', 'amigo-performance'); ?>
                        </a>
                    </div>

                    <!-- How to Use Section -->
                    <div class="amigo-card">
                        <div class="amigo-card-header">
                            <h3 class="amigo-card-title"><?php esc_html_e('How to Use Asset Manager', 'amigo-performance'); ?></h3>
                            <p class="amigo-card-desc"><?php esc_html_e('Step-by-step guide to manage assets effectively.', 'amigo-performance'); ?></p>
                        </div>
                        
                        <div class="amigo-steps">
                            <div class="amigo-step">
                                <div class="amigo-step-number">1</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e('Visit Any Page', 'amigo-performance'); ?></h4>
                                    <p><?php esc_html_e('Navigate to any page on your website while logged in as an administrator.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            
                            <div class="amigo-step">
                                <div class="amigo-step-number">2</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e('Open Admin Bar Menu', 'amigo-performance'); ?></h4>
                                    <p><?php esc_html_e('Look for the Performance menu in the admin bar, then click on Asset Manager.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            
                            <div class="amigo-step">
                                <div class="amigo-step-number">3</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e('Toggle Assets', 'amigo-performance'); ?></h4>
                                    <p><?php esc_html_e('Check/uncheck CSS and JS files to enable or disable them on that specific page.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            
                            <div class="amigo-step">
                                <div class="amigo-step-number">4</div>
                                <div class="amigo-step-content">
                                    <h4><?php esc_html_e('Test Performance', 'amigo-performance'); ?></h4>
                                    <p><?php esc_html_e('Refresh the page to see changes. Use tools like GTmetrix or PageSpeed Insights to measure improvement.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>

                    <!-- Asset Management by Page -->
                    <div class="amigo-card">
                        <div class="amigo-card-header">
                            <h3 class="amigo-card-title"><?php esc_html_e('Managed Assets by Page', 'amigo-performance'); ?></h3>
                            <p class="amigo-card-desc"><?php esc_html_e('Assets are organized by page. Click on any page to view and manage its assets.', 'amigo-performance'); ?></p>
                        </div>
                        
                        <div class="amigo-accordion-container">
                            <?php 
                            // Group assets by page URL
                            $assets_by_page = array();
                            foreach ($all_assets as $asset) {
                                $assets_by_page[$asset->page_url][] = $asset;
                            }
                            
                            $page_index = 0;
                            foreach ($assets_by_page as $page_url => $page_assets):
                                $page_index++;
                                $disabled_count = count(array_filter($page_assets, function($asset) { return $asset->is_dequeued; }));
                                $enabled_count = count($page_assets) - $disabled_count;
                                $css_count = count(array_filter($page_assets, function($asset) { return $asset->asset_type === 'css'; }));
                                $js_count = count(array_filter($page_assets, function($asset) { return $asset->asset_type === 'js'; }));
                            ?>
                            
                            <div class="amigo-accordion-item">
                                <div class="amigo-accordion-header" onclick="toggleAccordion(<?php echo esc_attr($page_index); ?>)">
                                    <div class="amigo-page-info">
                                        <div class="amigo-page-url">
                                            <span class="amigo-folder-icon">📁</span>
                                            <strong><?php echo esc_html(amigoperf_truncate_url($page_url, 60)); ?></strong>
                                            <a href="<?php echo esc_url($page_url); ?>" target="_blank" class="amigo-external-link" title="<?php esc_attr_e('Open page in new tab', 'amigo-performance'); ?>" onclick="event.stopPropagation();">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                    <path d="M18 13V19C18 19.5523 17.5523 20 17 20H5C4.44772 20 4 19.5523 4 19V7C4 6.44772 4.44772 6 5 6H11" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/>
                                                    <path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="amigo-page-summary">
                                            <span class="amigo-asset-count"><?php echo esc_html($css_count); ?> CSS</span>
                                            <span class="amigo-asset-count"><?php echo esc_html($js_count); ?> JS</span>
                                            <span class="amigo-disabled-count"><?php echo esc_html($disabled_count); ?> disabled</span>
                                            
                                            <div class="amigo-bulk-actions" onclick="event.stopPropagation();">
                                                <button class="amigo-btn amigo-btn-tiny amigo-btn-success" onclick="bulkToggleAssets(<?php echo esc_attr($page_index); ?>, false)" title="<?php esc_attr_e('Enable all assets on this page', 'amigo-performance'); ?>">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2"/>
                                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                    </svg>
                                                    <?php esc_html_e('Enable All', 'amigo-performance'); ?>
                                                </button>
                                                <button class="amigo-btn amigo-btn-tiny amigo-btn-warning" onclick="bulkToggleAssets(<?php echo esc_attr($page_index); ?>, true)" title="<?php esc_attr_e('Disable all assets on this page', 'amigo-performance'); ?>">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M15 9L9 15" stroke="currentColor" stroke-width="2"/>
                                                        <path d="M9 9L15 15" stroke="currentColor" stroke-width="2"/>
                                                    </svg>
                                                    <?php esc_html_e('Disable All', 'amigo-performance'); ?>
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
                                
                                <div class="amigo-accordion-content" id="accordion-<?php echo esc_attr($page_index); ?>">
                                    <div class="amigo-assets-grid">
                                        <?php foreach ($page_assets as $asset): ?>
                                        <div class="amigo-asset-card <?php echo $asset->is_dequeued ? 'disabled' : 'enabled'; ?>">
                                            <div class="amigo-asset-info">
                                                <div class="amigo-asset-header">
                                                    <span class="amigo-asset-type-badge amigo-asset-type-<?php echo esc_attr($asset->asset_type); ?>">
                                                        <?php echo esc_html(strtoupper($asset->asset_type)); ?>
                                                    </span>
                                                    <span class="amigo-status-indicator <?php echo $asset->is_dequeued ? 'disabled' : 'enabled'; ?>">
                                                        <?php if ($asset->is_dequeued): ?>
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                                <path d="M15 9L9 15" stroke="currentColor" stroke-width="2"/>
                                                                <path d="M9 9L15 15" stroke="currentColor" stroke-width="2"/>
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2"/>
                                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                            </svg>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="amigo-asset-name">
                                                    <strong><?php echo esc_html($asset->asset_handle); ?></strong>
                                                </div>
                                                <div class="amigo-asset-status">
                                                    <?php echo $asset->is_dequeued ? esc_html__('Disabled', 'amigo-performance') : esc_html__('Enabled', 'amigo-performance'); ?>
                                                </div>
                                            </div>
                                            <div class="amigo-asset-actions">
                                                <button class="amigo-btn amigo-btn-small amigo-btn-secondary" onclick="toggleAssetStatus(<?php echo esc_attr($asset->id); ?>, <?php echo $asset->is_dequeued ? 'false' : 'true'; ?>)">
                                                    <?php echo $asset->is_dequeued ? esc_html__('Enable', 'amigo-performance') : esc_html__('Disable', 'amigo-performance'); ?>
                                                </button>
                                                <button class="amigo-btn amigo-btn-small amigo-btn-danger" onclick="deleteAsset(<?php echo esc_attr($asset->id); ?>)">
                                                    <?php esc_html_e('Delete', 'amigo-performance'); ?>
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

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Enqueue the Asset Manager script
wp_enqueue_script('amigo-asset-manager', plugins_url('/assets/js/asset-manager.js', __FILE__), array(), AMIGOPERF_PLUGIN_VERSION, true);

// Localize the script with data
wp_localize_script('amigo-asset-manager', 'amigoAssetManager', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('amigoperf_asset_admin_action'),
    'confirmDelete' => __('Are you sure you want to delete this asset entry?', 'amigo-performance'),
    'homeUrl' => esc_url(home_url()),
    'i18n' => array(
        'noAssetsYet' => __('No Assets Managed Yet', 'amigo-performance'),
        'visitPages' => __('Visit pages on your website and use the admin bar Asset Manager to start managing assets per page.', 'amigo-performance'),
        'visitHomepage' => __('Visit Homepage', 'amigo-performance'),
        'howToUse' => __('How to Use Asset Manager', 'amigo-performance'),
        'stepByStep' => __('Step-by-step guide to manage assets effectively.', 'amigo-performance'),
        'visitAnyPage' => __('Visit Any Page', 'amigo-performance'),
        'visitAnyPageDesc' => __('Navigate to any page on your website while logged in as an administrator.', 'amigo-performance'),
        'openAdminBar' => __('Open Admin Bar Menu', 'amigo-performance'),
        'openAdminBarDesc' => __('Look for the Performance menu in the admin bar, then click on Asset Manager.', 'amigo-performance'),
        'toggleAssets' => __('Toggle Assets', 'amigo-performance'),
        'toggleAssetsDesc' => __('Check/uncheck CSS and JS files to enable or disable them on that specific page.', 'amigo-performance'),
        'testPerformance' => __('Test Performance', 'amigo-performance'),
        'testPerformanceDesc' => __('Refresh the page to see changes. Use tools like GTmetrix or PageSpeed Insights to measure improvement.', 'amigo-performance')
    )
));
?>
<?php
/**
 * @package amigo-performance
 * Core Settings Admin Page
 * Version: 2.7
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
                    <h1 class="amigo-title"><?php echo esc_html($plugin_instance->amigoPerf_PluginName); ?> - Core Settings</h1>
                    <p class="amigo-subtitle"><?php esc_html_e('Optimize your website with core performance settings', 'amigo-performance'); ?></p>
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
        <div class="amigo-content">
            <div class="amigo-section">
                <div class="amigo-section-header">
                    <h2 class="amigo-section-title"><?php esc_html_e('Performance Optimizations', 'amigo-performance'); ?></h2>
                    <p class="amigo-section-desc"><?php esc_html_e('Enable these core optimizations to boost your website speed and performance scores.', 'amigo-performance'); ?></p>
                </div>
                
                <form method="post" class="amigo-form">
                    <input type="hidden" name="<?php echo esc_attr($plugin_instance->amigoPerf_hfn); ?>" value="Y">
                    
                    <div class="amigo-cards-grid">
                        
                        <!-- Remove Query Strings Card -->
                        <div class="amigo-card">
                            <div class="amigo-card-header">
                                <div class="amigo-card-icon query-strings">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M10 6H6C4.89543 6 4 6.89543 4 8V16C4 17.1046 4.89543 18 6 18H14C15.1046 18 16 17.1046 16 16V12" stroke="currentColor" stroke-width="2"/>
                                        <path d="M14 4L20 10L16 14L10 8L14 4Z" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="amigo-card-title-area">
                                    <h3 class="amigo-card-title"><?php esc_html_e('Remove Query Strings', 'amigo-performance'); ?></h3>
                                    <p class="amigo-card-desc"><?php esc_html_e('Improve caching by removing version parameters from CSS/JS files', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <div class="amigo-card-control">
                                <label class="amigo-toggle">
                                    <input type="checkbox" 
                                           name="<?php echo esc_attr($plugin_instance->amigoPerf_rqs); ?>"
                                           value="1"
                                           <?php checked($plugin_instance->amigoPerf_rqs_opt, true); ?>>
                                    <span class="amigo-toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Remove Emoji Card -->
                        <div class="amigo-card">
                            <div class="amigo-card-header">
                                <div class="amigo-card-icon emoji">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        <path d="M8 14S9.5 16 12 16S16 14 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="9" y1="9" x2="9.01" y2="9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="15" y1="9" x2="15.01" y2="9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-card-title-area">
                                    <h3 class="amigo-card-title"><?php esc_html_e('Remove Emoji Scripts', 'amigo-performance'); ?></h3>
                                    <p class="amigo-card-desc"><?php esc_html_e('Save 13.5KB by removing WordPress emoji assets', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <div class="amigo-card-control">
                                <label class="amigo-toggle">
                                    <input type="checkbox" 
                                           name="<?php echo esc_attr($plugin_instance->amigoPerf_remoji); ?>"
                                           value="1"
                                           <?php checked($plugin_instance->amigoPerf_remoji_opt, true); ?>>
                                    <span class="amigo-toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Defer JavaScript Card -->
                        <div class="amigo-card">
                            <div class="amigo-card-header">
                                <div class="amigo-card-icon javascript">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 2L2 7V10C2 16 6 20.5 12 22C18 20.5 22 16 22 10V7L12 2Z" stroke="currentColor" stroke-width="2"/>
                                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-card-title-area">
                                    <h3 class="amigo-card-title"><?php esc_html_e('Defer JavaScript', 'amigo-performance'); ?></h3>
                                    <p class="amigo-card-desc"><?php esc_html_e('Improve page load by deferring non-critical JavaScript', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <div class="amigo-card-control">
                                <label class="amigo-toggle">
                                    <input type="checkbox" 
                                           name="<?php echo esc_attr($plugin_instance->amigoPerf_defer); ?>"
                                           value="1"
                                           <?php checked($plugin_instance->amigoPerf_defer_opt, true); ?>>
                                    <span class="amigo-toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Iframe Lazy Loading Card -->
                        <div class="amigo-card">
                            <div class="amigo-card-header">
                                <div class="amigo-card-icon iframe">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="9" cy="9" r="2" stroke="currentColor" stroke-width="2"/>
                                        <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="amigo-card-title-area">
                                    <h3 class="amigo-card-title"><?php esc_html_e('Iframe Lazy Loading', 'amigo-performance'); ?></h3>
                                    <p class="amigo-card-desc"><?php esc_html_e('Load iframes only when they come into viewport', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <div class="amigo-card-control">
                                <label class="amigo-toggle">
                                    <input type="checkbox" 
                                           name="<?php echo esc_attr($plugin_instance->amigoPerf_iframelazy); ?>"
                                           value="1"
                                           <?php checked($plugin_instance->amigoPerf_iframelazy_opt, true); ?>>
                                    <span class="amigo-toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Image Lazy Loading Card -->
                        <div class="amigo-card">
                            <div class="amigo-card-header">
                                <div class="amigo-card-icon images">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="2"/>
                                        <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="amigo-card-title-area">
                                    <h3 class="amigo-card-title"><?php esc_html_e('Image Lazy Loading', 'amigo-performance'); ?></h3>
                                    <p class="amigo-card-desc"><?php esc_html_e('Load images only when they appear in the viewport', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <div class="amigo-card-control">
                                <label class="amigo-toggle">
                                    <input type="checkbox" 
                                           name="<?php echo esc_attr($plugin_instance->amigoPerf_lazyload); ?>"
                                           value="1"
                                           <?php checked($plugin_instance->amigoPerf_lazyload_opt, true); ?>>
                                    <span class="amigo-toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="amigo-form-footer">
                        <button type="submit" class="amigo-btn amigo-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php esc_html_e('Save Changes', 'amigo-performance'); ?>
                        </button>
                    </div>
                    
                    <?php wp_nonce_field('amigo_basic_settings_action', 'amigo_basic_nonce'); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * @package amigo-performance
 * Core Settings Admin Page
 * Version: 3.4
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Count enabled features for the summary
$amigoperf_enabled_count = 0;
$amigoperf_total_features = 7;
if ($plugin_instance->amigoPerf_rqs_opt) $amigoperf_enabled_count++;
if ($plugin_instance->amigoPerf_remoji_opt) $amigoperf_enabled_count++;
if ($plugin_instance->amigoPerf_defer_opt) $amigoperf_enabled_count++;
if ($plugin_instance->amigoPerf_iframelazy_opt) $amigoperf_enabled_count++;
if ($plugin_instance->amigoPerf_lazyload_opt) $amigoperf_enabled_count++;
if ($plugin_instance->amigoPerf_minify_css_opt) $amigoperf_enabled_count++;
if ($plugin_instance->amigoPerf_minify_js_opt) $amigoperf_enabled_count++;
?>

<div class="amigo-wrapper">

    <!-- Header Section -->
    <div class="amigo-header">
        <div class="amigo-header-content">
            <div class="amigo-logo">
                <div class="amigo-icon">
                    <span class="dashicons dashicons-buddicons-activity"></span>
                </div>
                <div class="amigo-title-section">
                    <h1 class="amigo-title"><?php echo esc_html($plugin_instance->amigoPerf_PluginName); ?></h1>
                    <p class="amigo-subtitle"><?php esc_html_e('Performance optimization made simple', 'amigo-performance'); ?></p>
                </div>
            </div>
            <div class="amigo-header-meta">
                <span class="amigo-version-badge">v<?php echo esc_html(AMIGOPERF_PLUGIN_VERSION); ?></span>
                <span class="amigo-header-status">
                    <?php
                    /* translators: 1: number of enabled features, 2: total number of features */
                    printf( esc_html__( '%1$d / %2$d active', 'amigo-performance' ), intval( $amigoperf_enabled_count ), intval( $amigoperf_total_features ) );
                    ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="amigo-main">
        <div class="amigo-content">

            <!-- Status Overview Bar -->
            <div class="amigo-core-status-bar">
                <div class="amigo-core-status-indicator">
                    <div class="amigo-core-status-ring">
                        <svg viewBox="0 0 36 36" width="56" height="56">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="#e2e8f0" stroke-width="2.5" />
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="url(#amigo-gradient)" stroke-width="2.5"
                                  stroke-dasharray="<?php echo esc_attr(round(($amigoperf_enabled_count / $amigoperf_total_features) * 100)); ?>, 100"
                                  stroke-linecap="round" />
                            <defs>
                                <linearGradient id="amigo-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#667eea" />
                                    <stop offset="100%" stop-color="#764ba2" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="amigo-core-status-number"><?php echo esc_html($amigoperf_enabled_count); ?></span>
                    </div>
                    <div class="amigo-core-status-text">
                        <strong><?php esc_html_e('Optimizations Active', 'amigo-performance'); ?></strong>
                        <span>
                            <?php
                            /* translators: %d: total number of features available */
                            printf( esc_html__( 'out of %d available features', 'amigo-performance' ), intval( $amigoperf_total_features ) );
                            ?>
                        </span>
                    </div>
                </div>
                <div class="amigo-core-status-actions">
                    <a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank" rel="noopener noreferrer" class="amigo-btn amigo-btn-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M18 13V19C18 20.1 17.1 21 16 21H5C3.9 21 3 20.1 3 19V8C3 6.9 3.9 6 5 6H11" stroke="currentColor" stroke-width="2"/><path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/><path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/></svg>
                        <?php esc_html_e('PageSpeed Insights', 'amigo-performance'); ?>
                    </a>
                </div>
            </div>

            <form method="post" class="amigo-form">
                <input type="hidden" name="<?php echo esc_attr($plugin_instance->amigoPerf_hfn); ?>" value="Y">

                <!-- Section: Page Load -->
                <div class="amigo-core-section">
                    <div class="amigo-core-section-label">
                        <span class="amigo-core-section-dot" style="background: linear-gradient(135deg, #667eea, #764ba2);"></span>
                        <h2><?php esc_html_e('Page Load Optimization', 'amigo-performance'); ?></h2>
                        <p><?php esc_html_e('Speed up initial page rendering and improve caching behavior.', 'amigo-performance'); ?></p>
                    </div>

                    <div class="amigo-core-cards">

                        <!-- Remove Query Strings -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_rqs_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #667eea, #764ba2);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Remove Query Strings', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Strips ?ver= parameters from static resources so CDNs and browsers cache them more efficiently.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_rqs); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_rqs_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Defer JavaScript -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_defer_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #4facfe, #00f2fe);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Defer JavaScript', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Adds defer attribute to scripts so they execute after HTML parsing, eliminating render-blocking.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_defer); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_defer_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Remove Emoji Scripts -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_remoji_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #f093fb, #f5576c);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="9" y1="9" x2="9.01" y2="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                        <line x1="15" y1="9" x2="15.01" y2="9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Remove Emoji Scripts', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Removes the wp-emoji-release.min.js script and related inline CSS, saving ~13.5 KB per page load.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_remoji); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_remoji_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <!-- Section: Lazy Loading -->
                <div class="amigo-core-section">
                    <div class="amigo-core-section-label">
                        <span class="amigo-core-section-dot" style="background: linear-gradient(135deg, #43e97b, #38f9d7);"></span>
                        <h2><?php esc_html_e('Lazy Loading', 'amigo-performance'); ?></h2>
                        <p><?php esc_html_e('Defer off-screen media to reduce initial payload and improve Largest Contentful Paint.', 'amigo-performance'); ?></p>
                    </div>

                    <div class="amigo-core-cards">

                        <!-- Image Lazy Loading -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_lazyload_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #43e97b, #38f9d7);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="2"/>
                                        <path d="M21 15l-5-5L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Image Lazy Loading', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Defers loading of below-the-fold images until the user scrolls near them.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_lazyload); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_lazyload_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Iframe Lazy Loading -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_iframelazy_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #fa709a, #fee140);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <path d="M8 21h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M12 17v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M7 8l3 2-3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Iframe Lazy Loading', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Loads embedded videos, maps, and other iframes only when they enter the viewport.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_iframelazy); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_iframelazy_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <!-- Section: Asset Minification -->
                <div class="amigo-core-section">
                    <div class="amigo-core-section-label">
                        <span class="amigo-core-section-dot" style="background: linear-gradient(135deg, #16a085, #3498db);"></span>
                        <h2><?php esc_html_e('Asset Minification', 'amigo-performance'); ?></h2>
                        <p><?php esc_html_e('Reduce file sizes by stripping whitespace and comments from static assets.', 'amigo-performance'); ?></p>
                    </div>

                    <div class="amigo-core-cards">

                        <!-- Minify CSS -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_minify_css_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #16a085, #3498db);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 4v16a1 1 0 001 1h14a1 1 0 001-1V8l-4-4H5a1 1 0 00-1 1z" stroke="currentColor" stroke-width="2"/>
                                        <path d="M14 4v4h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 13h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M9 17h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Minify CSS', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Compresses stylesheets by removing whitespace, comments, and redundant code.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_minify_css); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_minify_css_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Minify JavaScript -->
                        <div class="amigo-core-card <?php echo $plugin_instance->amigoPerf_minify_js_opt ? 'is-active' : ''; ?>">
                            <div class="amigo-core-card-left">
                                <div class="amigo-core-card-icon" style="--icon-bg: linear-gradient(135deg, #f7971e, #ffd200);">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 4v16a1 1 0 001 1h14a1 1 0 001-1V8l-4-4H5a1 1 0 00-1 1z" stroke="currentColor" stroke-width="2"/>
                                        <path d="M14 4v4h4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10 13.5v3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M10 15.5c0 1 1 1.5 2 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        <path d="M14 13.5c-1.5 0-2 .8-2 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="amigo-core-card-info">
                                    <h3><?php esc_html_e('Minify JavaScript', 'amigo-performance'); ?></h3>
                                    <p><?php esc_html_e('Compresses scripts by removing whitespace, comments, and unnecessary characters.', 'amigo-performance'); ?></p>
                                </div>
                            </div>
                            <label class="amigo-toggle">
                                <input type="checkbox"
                                       name="<?php echo esc_attr($plugin_instance->amigoPerf_minify_js); ?>"
                                       value="1"
                                       <?php checked($plugin_instance->amigoPerf_minify_js_opt, true); ?>>
                                <span class="amigo-toggle-slider"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <!-- Save Button -->
                <div class="amigo-core-form-footer">
                    <button type="submit" class="amigo-btn amigo-btn-primary amigo-btn-save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php esc_html_e('Save Changes', 'amigo-performance'); ?>
                    </button>
                </div>

                <?php wp_nonce_field('amigo_basic_settings_action', 'amigo_basic_nonce'); ?>
            </form>

        </div>
    </div>
</div>

<?php
/**
 * Admin Menu and Page Management
 * 
 * @package amigo-performance
 * @version 3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class AmigoPerformance_Admin {
    
    private $plugin_instance;
    
    public function __construct($plugin_instance) {
        $this->plugin_instance = $plugin_instance;
    }
    
    /**
     * Initialize admin functionality
     */
    public function init() {
        add_action('admin_menu', array($this, 'add_admin_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('wp_head', array($this, 'admin_bar_styles'));
        add_action('admin_head', array($this, 'admin_bar_styles'));
        
        // Add admin bar menu after everything is loaded
        add_action('admin_bar_menu', array($this, 'add_admin_bar_menu'), 999);
    }
    
    /**
     * Add admin menu pages
     */
    public function add_admin_pages() {
        // Main menu item
        add_menu_page(
            'Amigo Performance Plugin',
            'Performance',
            'manage_options',
            'amigo_performance',
            array($this, 'core_settings_page'),
            'dashicons-buddicons-activity'
        );
        
        // Core Settings submenu
        add_submenu_page(
            'amigo_performance',
            'Core Settings',
            'Core Settings',
            'manage_options',
            'amigo_performance',
            array($this, 'core_settings_page')
        );
        
        // Asset Manager submenu
        add_submenu_page(
            'amigo_performance',
            'Asset Manager',
            'Asset Manager',
            'manage_options',
            'amigo_asset_manager',
            array($this, 'asset_manager_page')
        );
    }
    
    /**
     * Core Settings page callback
     */
    public function core_settings_page() {
        // Make the plugin instance available to the included file
        $plugin_instance = $this->plugin_instance;
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin-core-settings.php';
    }
    
    /**
     * Asset Manager page callback
     */
    public function asset_manager_page() {
        // Make the plugin instance available to the included file
        $plugin_instance = $this->plugin_instance;
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin-asset-manager.php';
    }
    
    /**
     * Add admin bar menu
     */
    public function add_admin_bar_menu($admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Main menu item with background color
        $admin_bar->add_menu(array(
            'id' => 'amigo-performance-main',
            'parent' => null,
            'group' => null,
            'title' => '<span class="ab-icon dashicons dashicons-performance"></span>Asset Manager',
            'href' => admin_url('admin.php?page=amigo_asset_manager'),
            'meta' => [
                'title' => 'Amigo Performance - Asset Manager',
                'class' => 'amigoperf-main-menu'
            ]
        ));

        // Add Asset Manager submenu if enabled
        if (property_exists($this->plugin_instance, 'asset_manager_enabled') && $this->plugin_instance->asset_manager_enabled) {
            $this->add_asset_manager_admin_bar_items($admin_bar);
        }
    }
    
    /**
     * Add asset manager items to admin bar
     */
    private function add_asset_manager_admin_bar_items($admin_bar) {
        $current_url = $this->get_current_url();
        $assets = $this->get_enqueued_assets();

        // Add CSS submenu if we have styles
        if (!empty($assets['styles'])) {
            // Filter out dequeued assets
            $active_styles = array();
            foreach ($assets['styles'] as $handle => $src) {
                $is_dequeued = $this->is_asset_dequeued($current_url, $handle, 'css');
                if (!$is_dequeued) {
                    $active_styles[$handle] = $src;
                }
            }
            
            if (!empty($active_styles)) {
                $admin_bar->add_node(array(
                    'parent' => 'amigo-performance-main',
                    'id' => 'amigoperf-asset-css',
                    // translators: %d is the number of CSS files
                    'title' => sprintf('🎨 %s (%d)', __('CSS Files', 'amigo-performance'), count($active_styles)),
                    'meta' => array('class' => 'amigoperf-asset-submenu amigoperf-scrollable')
                ));
                
                foreach ($active_styles as $handle => $src) {
                    $admin_bar->add_node(array(
                        'parent' => 'amigoperf-asset-css',
                        'id' => 'amigoperf-asset-css-' . $handle,
                        'title' => $this->get_asset_checkbox_html($handle, 'css', false, $src),
                        'meta' => array('class' => 'amigoperf-asset-item asset-enabled')
                    ));
                }
            }
        }
        
        // Add JS submenu if we have scripts
        if (!empty($assets['scripts'])) {
            // Filter out dequeued assets
            $active_scripts = array();
            foreach ($assets['scripts'] as $handle => $src) {
                $is_dequeued = $this->is_asset_dequeued($current_url, $handle, 'js');
                if (!$is_dequeued) {
                    $active_scripts[$handle] = $src;
                }
            }
            
            if (!empty($active_scripts)) {
                $admin_bar->add_node(array(
                    'parent' => 'amigo-performance-main',
                    'id' => 'amigoperf-asset-js',
                    // translators: %d is the number of JS files
                    'title' => sprintf('🔧 %s (%d)', __('JS Files', 'amigo-performance'), count($active_scripts)),
                    'meta' => array('class' => 'amigoperf-asset-submenu amigoperf-scrollable')
                ));
                
                foreach ($active_scripts as $handle => $src) {
                    $admin_bar->add_node(array(
                        'parent' => 'amigoperf-asset-js',
                        'id' => 'amigoperf-asset-js-' . $handle,
                        'title' => $this->get_asset_checkbox_html($handle, 'js', false, $src),
                        'meta' => array('class' => 'amigoperf-asset-item asset-enabled')
                    ));
                }
            }
        }

        // Add dashboard link
        $admin_bar->add_node(array(
            'parent' => 'amigo-performance-main',
            'id' => 'amigoperf-asset-dashboard',
            'title' => '📊 ' . __('Asset Dashboard', 'amigo-performance'),
            'href' => admin_url('admin.php?page=amigo_asset_manager'),
            'meta' => array('title' => __('View comprehensive asset statistics and management', 'amigo-performance'))
        ));
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style(
            'amigoperf_style',
            plugins_url('assets/css/style.css', dirname(__FILE__)),
            array(),
            '3.0'
        );
        
        wp_enqueue_script(
            'amigoperf_script',
            plugins_url('assets/js/script.js', dirname(__FILE__)),
            array(),
            '3.0',
            true
        );
        
        // Enqueue admin bar script for asset management
        if (is_admin_bar_showing() && current_user_can('manage_options')) {
            wp_enqueue_script('jquery');
            
            wp_enqueue_script(
                'amigoperf-admin-bar',
                plugins_url('assets/js/admin-bar.js', dirname(__FILE__)),
                array('jquery'),
                '3.0',
                true
            );
            
            // Localize script for AJAX
            wp_localize_script('amigoperf-admin-bar', 'amigoPerf', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('amigoperf_asset_toggle'),
                'debug' => defined('AMIGOPERF_DEBUG') && AMIGOPERF_DEBUG
            ));
        }
    }
    
    /**
     * Enqueue frontend assets for admin bar
     */
    public function enqueue_frontend_assets() {
        // Only enqueue if admin bar is showing and user can manage options
        if (is_admin_bar_showing() && current_user_can('manage_options')) {
            wp_enqueue_script('jquery');
            
            wp_enqueue_script(
                'amigoperf-admin-bar',
                plugins_url('assets/js/admin-bar.js', dirname(__FILE__)),
                array('jquery'),
                '3.0',
                true
            );
            
            // Localize script for AJAX
            wp_localize_script('amigoperf-admin-bar', 'amigoPerf', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('amigoperf_asset_toggle'),
                'debug' => defined('AMIGOPERF_DEBUG') && AMIGOPERF_DEBUG
            ));
        }
    }
    
    /**
     * Get current URL
     */
    private function get_current_url() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
        $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        
        // Get the full URL
        $url = $protocol . $host . $request_uri;
        
        // Remove query string for more reliable matching
        $url = strtok($url, '?');
        
        // Remove trailing slash for consistency
        $url = untrailingslashit($url);
        
        // Debug log
        AmigoPerformance_Logger::info("Admin Bar current URL - " . $url);
        
        return $url;
    }
    
    /**
     * Get enqueued assets
     */
    private function get_enqueued_assets() {
        global $wp_scripts, $wp_styles;
        
        $scripts = array();
        $styles = array();
        
        if (!empty($wp_scripts->queue)) {
            foreach ($wp_scripts->queue as $handle) {
                if (isset($wp_scripts->registered[$handle]) && !empty($wp_scripts->registered[$handle]->src)) {
                    $scripts[$handle] = $wp_scripts->registered[$handle]->src;
                }
            }
        }
        
        if (!empty($wp_styles->queue)) {
            foreach ($wp_styles->queue as $handle) {
                if (isset($wp_styles->registered[$handle]) && !empty($wp_styles->registered[$handle]->src)) {
                    $styles[$handle] = $wp_styles->registered[$handle]->src;
                }
            }
        }
        
        return array(
            'scripts' => $scripts,
            'styles' => $styles
        );
    }
    
    /**
     * Check if asset is dequeued - with improved URL matching
     */
    private function is_asset_dequeued($page_url, $handle, $type) {
        global $wpdb;
        
        // Force table creation if it doesn't exist
        $table_name = $wpdb->prefix . 'amigoperf_asset_manager';
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- No WordPress API available for table existence check
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) === $table_name;
        
        if (!$table_exists) {
            // Force asset manager to create table
            if ($this->plugin_instance->asset_manager) {
                $this->plugin_instance->asset_manager->create_table();
            }
            return false;
        }
        
        // Normalize the URL for consistent matching
        // Note: page_url should already be normalized, but just to be safe
        $page_url = strtok($page_url, '?'); // Remove query string
        $page_url = untrailingslashit($page_url); // Remove trailing slash
        
        // Create a cache key for this specific asset check
        $cache_key = 'amigoperf_asset_dequeued_' . md5($page_url . $handle . $type);
        $is_dequeued = wp_cache_get($cache_key);
        
        if ($is_dequeued === false) {
            // Try exact match first
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
            $result = $wpdb->get_var($wpdb->prepare(
                "SELECT is_dequeued FROM `{$wpdb->prefix}amigoperf_asset_manager` WHERE page_url = %s AND asset_handle = %s AND asset_type = %s",
                $page_url, $handle, $type
            ));
            
            // If no match, try with trailing slash
            if ($result === null) {
                $alt_url = trailingslashit($page_url);
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
                $result = $wpdb->get_var($wpdb->prepare(
                    "SELECT is_dequeued FROM `{$wpdb->prefix}amigoperf_asset_manager` WHERE page_url = %s AND asset_handle = %s AND asset_type = %s",
                    $alt_url, $handle, $type
                ));
            }
            
            $is_dequeued = ($result == 1);
            
            // Debug log
            AmigoPerformance_Logger::info("Checked if dequeued - URL: " . $page_url . ", Handle: " . $handle . ", Type: " . $type . ", Result: " . ($is_dequeued ? 'true' : 'false'));
            
            // Cache for 5 minutes
            wp_cache_set($cache_key, $is_dequeued, '', 300);
        }
        
        return $is_dequeued;
    }
    
    /**
     * Get asset checkbox HTML
     */
    private function get_asset_checkbox_html($handle, $type, $is_dequeued, $src) {
        $checked = $is_dequeued ? 'checked' : '';
        $filename = basename($src);
        $title = !empty($filename) ? $filename : $handle;
        
        if (strpos($title, '?') !== false) {
            $title = explode('?', $title)[0];
        }
        
        $nonce = wp_create_nonce('amigoperf_asset_toggle');
        $admin_ajax_url = admin_url('admin-ajax.php');
        
        return sprintf(
            '<label class="asset-label">
                <input type="checkbox" %s onchange="amigoPerfToggleAsset(\'%s\', \'%s\', this.checked, \'%s\', \'%s\')" class="asset-checkbox">
                <span class="asset-name" title="%s - %s">%s</span>
            </label>',
            $checked,
            esc_attr($handle),
            esc_attr($type),
            esc_url($admin_ajax_url),
            esc_attr($nonce),
            esc_attr($handle),
            esc_attr($src),
            esc_html($title)
        );
    }
    
    /**
     * Add admin bar styles
     */
    public function admin_bar_styles() {
        echo '<style>
        #wp-admin-bar-amigo-performance-main > .ab-item {
            background-color: #0073aa !important;
            color: white !important;
            font-weight: bold;
        }
        
        #wp-admin-bar-amigo-performance-main:hover > .ab-item {
            background-color: #005a87 !important;
        }
        
        .amigoperf-scrollable .ab-submenu {
            max-height: 300px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }
        
        .amigoperf-scrollable .ab-submenu::-webkit-scrollbar {
            width: 8px;
        }
        
        .amigoperf-scrollable .ab-submenu::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .amigoperf-scrollable .ab-submenu::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        .amigoperf-scrollable .ab-submenu::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .amigoperf-asset-item .ab-item {
            font-size: 12px !important;
            line-height: 1.3 !important;
            padding: 3px 12px !important;
            white-space: nowrap !important;
        }
        
        .amigoperf-asset-item input[type="checkbox"] {
            margin-right: 8px;
            vertical-align: middle;
        }
        
        .asset-disabled .ab-item {
            opacity: 0.6;
            text-decoration: line-through;
        }
        
        .asset-enabled .ab-item {
            color: #0f834d;
        }
        
        .amigoperf-asset-submenu > .ab-item {
            font-weight: bold !important;
            background-color: #23282d !important;
            color: #eee !important;
        }
        </style>';
    }
}
<?php
/**
 * Asset Manager Functionality
 * 
 * @package amigo-performance
 * @version 3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class AmigoPerformance_AssetManager {
    
    private $plugin_instance;
    private $table_name;
    
    public function __construct($plugin_instance) {
        $this->plugin_instance = $plugin_instance;
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'amigoperf_asset_manager';
    }
    
    /**
     * Initialize Asset Manager
     */
    public function init() {
        $this->maybe_create_table();
        $this->setup_hooks();
    }
    
    /**
     * Setup WordPress hooks
     */
    private function setup_hooks() {
        add_action('wp_ajax_amigoperf_toggle_asset', array($this, 'ajax_toggle_asset'));
        add_action('wp_ajax_amigoperf_asset_admin_toggle', array($this, 'ajax_asset_admin_toggle'));
        add_action('wp_ajax_amigoperf_asset_admin_delete', array($this, 'ajax_asset_admin_delete'));
        
        // Dequeue assets right before they're printed
        add_action('wp_print_styles', array($this, 'dequeue_managed_assets'), 1);
        add_action('wp_print_scripts', array($this, 'dequeue_managed_assets'), 1);
        add_action('admin_print_styles', array($this, 'dequeue_managed_assets'), 1);
        add_action('admin_print_scripts', array($this, 'dequeue_managed_assets'), 1);
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_asset_manager_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_asset_manager_scripts'));
    }
    
    /**
     * Create asset manager table
     */
    public function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            page_url varchar(500) NOT NULL,
            asset_handle varchar(100) NOT NULL,
            asset_type varchar(10) NOT NULL,
            is_dequeued tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_asset (page_url, asset_handle, asset_type),
            KEY page_url (page_url),
            KEY asset_type (asset_type),
            KEY is_dequeued (is_dequeued)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Check if table exists and create if needed
     */
    public function maybe_create_table() {
        global $wpdb;
        
        // Cache key for table existence check
        $cache_key = 'amigoperf_table_exists_' . $this->table_name;
        $table_exists = wp_cache_get($cache_key);
        
        if ($table_exists === false) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table existence check, no WP API equivalent
            $table_exists = $wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $this->table_name
            )) === $this->table_name;
            
            // Cache for 1 hour
            wp_cache_set($cache_key, $table_exists, '', 3600);
        }
        
        if (!$table_exists) {
            $this->create_table();
            // Update cache after creating table
            wp_cache_set($cache_key, true, '', 3600);
        }
    }
    
    /**
     * Dequeue managed assets - Improved with better URL matching
     */
    public function dequeue_managed_assets() {
        global $wpdb;
        $current_url = $this->get_current_url();
        
        // Debug information
        AmigoPerformance_Logger::info("Attempting to dequeue assets for URL - " . $current_url);
        
        // Check if table exists first
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- No WordPress API available for table existence check
        $table_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->table_name)) === $this->table_name;
        
        if (!$table_exists) {
            AmigoPerformance_Logger::warning("Table {$this->table_name} does not exist");
            return;
        }
        
        // Cache key for dequeued assets per page
        $cache_key = 'amigoperf_dequeued_assets_' . md5($current_url);
        $dequeued_assets = wp_cache_get($cache_key);
        
        if ($dequeued_assets === false) {
            // Exact URL match first (normalized URL)
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
            $results = $wpdb->get_results($wpdb->prepare(
                "SELECT asset_handle, asset_type FROM `{$wpdb->prefix}amigoperf_asset_manager` 
                 WHERE page_url = %s AND is_dequeued = 1",
                $current_url
            ), ARRAY_A);
            
            // Initialize the array
            $dequeued_assets = array();
            if ($results && is_array($results)) {
                $dequeued_assets = $results;
                AmigoPerformance_Logger::info("Found " . count($results) . " assets to dequeue with exact URL match");
            } else {
                // If no results with exact match, try with trailing slash variations
                $alt_url = trailingslashit($current_url);
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
                $results = $wpdb->get_results($wpdb->prepare(
                    "SELECT asset_handle, asset_type FROM `{$wpdb->prefix}amigoperf_asset_manager` 
                     WHERE page_url = %s AND is_dequeued = 1",
                    $alt_url
                ), ARRAY_A);
                
                if ($results && is_array($results)) {
                    $dequeued_assets = $results;
                    AmigoPerformance_Logger::info("Found " . count($results) . " assets to dequeue with trailing slash URL match");
                }
            }
            
            // Cache for 5 minutes
            wp_cache_set($cache_key, $dequeued_assets, '', 300);
        }
        
        if (!empty($dequeued_assets)) {
            AmigoPerformance_Logger::info("Dequeuing " . count($dequeued_assets) . " assets for URL: " . $current_url);
            
            foreach ($dequeued_assets as $asset) {
                if (isset($asset['asset_type']) && isset($asset['asset_handle'])) {
                    if ($asset['asset_type'] === 'css') {
                        wp_dequeue_style($asset['asset_handle']);
                        wp_deregister_style($asset['asset_handle']);
                        AmigoPerformance_Logger::info("Dequeued CSS - " . $asset['asset_handle']);
                    } elseif ($asset['asset_type'] === 'js') {
                        wp_dequeue_script($asset['asset_handle']);
                        wp_deregister_script($asset['asset_handle']);
                        AmigoPerformance_Logger::info("Dequeued JS - " . $asset['asset_handle']);
                    }
                }
            }
        } else {
            AmigoPerformance_Logger::info("No assets to dequeue for URL: " . $current_url);
        }
    }
    
    /**
     * Enqueue asset manager scripts
     */
    public function enqueue_asset_manager_scripts() {
        if (!is_admin_bar_showing() || !current_user_can('manage_options')) {
            return;
        }
        
        wp_enqueue_script('jquery');
        
        wp_enqueue_style(
            'amigoperf-admin-bar',
            AMIGOPERF_PLUGIN_URL . 'assets/css/admin-bar.css',
            [],
            AMIGOPERF_PLUGIN_VERSION
        );
        
        wp_enqueue_script(
            'amigoperf-admin-bar',
            AMIGOPERF_PLUGIN_URL . 'assets/js/admin-bar.js',
            ['jquery'],
            AMIGOPERF_PLUGIN_VERSION,
            true
        );
        
        wp_localize_script('amigoperf-admin-bar', 'amigoPerf', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('amigoperf_asset_toggle'),
            'debug'   => defined('WP_DEBUG') && WP_DEBUG
        ]);
    }
    
    /**
     * Get current URL - Improved version with normalization
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
        AmigoPerformance_Logger::info("Current normalized URL - " . $url);
        
        return $url;
    }
    
    /**
     * Get asset manager statistics
     */
    public function get_stats() {
        global $wpdb;
        
        // Cache key for statistics
        $cache_key = 'amigoperf_stats';
        $stats = wp_cache_get($cache_key);
        
        if ($stats !== false) {
            return $stats;
        }
        
        // Check if table exists (with caching)
        $table_cache_key = 'amigoperf_table_exists_' . $this->table_name;
        $table_exists = wp_cache_get($table_cache_key);
        
        if ($table_exists === false) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table existence check, no WP API equivalent
            $table_exists = $wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $this->table_name
            )) === $this->table_name;
            wp_cache_set($table_cache_key, $table_exists, '', 3600);
        }
        
        if (!$table_exists) {
            $stats = array(
                'total_assets' => 0,
                'dequeued_assets' => 0,
                'unique_pages' => 0,
                'css_assets' => 0,
                'js_assets' => 0
            );
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table statistics, no WP API equivalent
            $total = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}amigoperf_asset_manager`") ?: 0;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table statistics, no WP API equivalent
            $dequeued = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}amigoperf_asset_manager` WHERE is_dequeued = 1") ?: 0;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table statistics, no WP API equivalent
            $pages = $wpdb->get_var("SELECT COUNT(DISTINCT page_url) FROM `{$wpdb->prefix}amigoperf_asset_manager`") ?: 0;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table statistics, no WP API equivalent
            $css = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}amigoperf_asset_manager` WHERE asset_type = 'css'") ?: 0;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table statistics, no WP API equivalent
            $js = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}amigoperf_asset_manager` WHERE asset_type = 'js'") ?: 0;
            
            $stats = array(
                'total_assets' => $total,
                'dequeued_assets' => $dequeued,
                'unique_pages' => $pages,
                'css_assets' => $css,
                'js_assets' => $js
            );
        }
        
        // Cache for 5 minutes
        wp_cache_set($cache_key, $stats, '', 300);
        return $stats;
    }
    
    /**
     * Get all managed assets
     */
    public function get_all_assets() {
        global $wpdb;
        
        // Cache key for all assets
        $cache_key = 'amigoperf_all_assets';
        $assets = wp_cache_get($cache_key);
        
        if ($assets !== false) {
            return $assets;
        }
        
        // Check if table exists (with caching)
        $table_cache_key = 'amigoperf_table_exists_' . $this->table_name;
        $table_exists = wp_cache_get($table_cache_key);
        
        if ($table_exists === false) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table existence check, no WP API equivalent
            $table_exists = $wpdb->get_var($wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $this->table_name
            )) === $this->table_name;
            wp_cache_set($table_cache_key, $table_exists, '', 3600);
        }
        
        if (!$table_exists) {
            $this->maybe_create_table();
            $assets = array();
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
            $assets = $wpdb->get_results(
                "SELECT * FROM `{$wpdb->prefix}amigoperf_asset_manager` ORDER BY page_url, asset_type, asset_handle"
            );
        }
        
        // Cache for 2 minutes
        wp_cache_set($cache_key, $assets, '', 120);
        return $assets;
    }
    
    /**
     * AJAX handler for toggling assets
     */
    public function ajax_toggle_asset() {
        // Security checks
        if (!wp_doing_ajax() || !current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'amigo-performance')));
        }
        
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? $_POST['_wpnonce'] ?? $_POST['_ajax_nonce'] ?? ''));
        if (!wp_verify_nonce($nonce, 'amigoperf_asset_toggle')) {
            wp_send_json_error(array('message' => __('Security verification failed', 'amigo-performance')));
        }
        
        $page_url = sanitize_url(wp_unslash($_POST['page_url'] ?? ''));
        $asset_handle = sanitize_text_field(wp_unslash($_POST['asset_handle'] ?? ''));
        $asset_type = sanitize_text_field(wp_unslash($_POST['asset_type'] ?? ''));
        $action = sanitize_text_field(wp_unslash($_POST['action_type'] ?? ''));
        
        // Normalize the URL for consistent matching
        $page_url = strtok($page_url, '?'); // Remove query string
        $page_url = untrailingslashit($page_url); // Remove trailing slash
        
        AmigoPerformance_Logger::info("Toggle asset - Normalized URL: " . $page_url);
        
        if (empty($page_url) || empty($asset_handle) || empty($asset_type)) {
            wp_send_json_error(array('message' => __('Missing required parameters', 'amigo-performance')));
        }
        
        // Process the toggle action
        $this->process_asset_toggle($page_url, $asset_handle, $asset_type, $action);
    }
    
    /**
     * Process asset toggle
     */
    private function process_asset_toggle($page_url, $asset_handle, $asset_type, $action) {
        global $wpdb;
        
        $this->maybe_create_table();
        
        // Cache key for specific asset lookup
        $cache_key = 'amigoperf_asset_' . md5($page_url . $asset_handle . $asset_type);
        $existing = wp_cache_get($cache_key);
        
        if ($existing === false) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
            $existing = $wpdb->get_row($wpdb->prepare(
                "SELECT id, is_dequeued FROM `{$wpdb->prefix}amigoperf_asset_manager` 
                 WHERE page_url = %s AND asset_handle = %s AND asset_type = %s",
                $page_url, $asset_handle, $asset_type
            ));
            
            // Cache for 5 minutes
            wp_cache_set($cache_key, $existing, '', 300);
        }
        
        $is_dequeued = ($action === 'disable') ? 1 : 0;
        
        if ($existing) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
            $result = $wpdb->update(
                $this->table_name,
                array('is_dequeued' => $is_dequeued, 'updated_at' => current_time('mysql')),
                array('id' => $existing->id),
                array('%d', '%s'),
                array('%d')
            );
        } else {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
            $result = $wpdb->insert(
                $this->table_name,
                array(
                    'page_url' => $page_url,
                    'asset_handle' => $asset_handle,
                    'asset_type' => $asset_type,
                    'is_dequeued' => $is_dequeued,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ),
                array('%s', '%s', '%s', '%d', '%s', '%s')
            );
        }
        
        // Clear relevant caches after data modification
        if ($result !== false) {
            wp_cache_delete('amigoperf_stats');
            wp_cache_delete('amigoperf_all_assets');
            wp_cache_delete('amigoperf_dequeued_assets_' . md5($page_url));
            // Clear the specific asset cache
            wp_cache_delete('amigoperf_asset_' . md5($page_url . $asset_handle . $asset_type));
        }
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => sprintf(
                    // translators: %1$s is the asset handle/name, %2$s is either "disabled" or "enabled"
                    __('Asset %1$s %2$s successfully', 'amigo-performance'),
                    $asset_handle,
                    $action === 'disable' ? __('disabled', 'amigo-performance') : __('enabled', 'amigo-performance')
                ),
                'handle' => $asset_handle,
                'type' => $asset_type,
                'action' => $action,
                'is_dequeued' => $is_dequeued
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to update asset status', 'amigo-performance')));
        }
    }
    
    /**
     * AJAX handler for admin toggle
     */
    public function ajax_asset_admin_toggle() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'amigo-performance')));
        }
        
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'] ?? '')), 'amigoperf_asset_admin_action')) {
            wp_send_json_error(array('message' => __('Security verification failed', 'amigo-performance')));
        }
        
        $asset_id = intval($_POST['asset_id'] ?? 0);
        $disable = (sanitize_text_field(wp_unslash($_POST['disable'] ?? '')) == '1');
        
        if (empty($asset_id)) {
            wp_send_json_error(array('message' => __('Missing asset ID', 'amigo-performance')));
        }
        
        global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
        $result = $wpdb->update(
            $this->table_name,
            array('is_dequeued' => $disable ? 1 : 0),
            array('id' => $asset_id),
            array('%d'),
            array('%d')
        );
        
        // Clear relevant caches after data modification
        if ($result !== false) {
            wp_cache_delete('amigoperf_stats');
            wp_cache_delete('amigoperf_all_assets');
            // Clear all dequeued asset caches (we don't know which page this asset belongs to)
            wp_cache_flush();
        }
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => __('Asset status updated successfully', 'amigo-performance'),
                'is_disabled' => $disable,
                'redirect_nonce' => wp_create_nonce('amigoperf_asset_updated')
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to update asset status', 'amigo-performance')));
        }
    }
    
    /**
     * AJAX handler for admin delete
     */
    public function ajax_asset_admin_delete() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'amigo-performance')));
        }
        
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'] ?? '')), 'amigoperf_asset_admin_action')) {
            wp_send_json_error(array('message' => __('Security verification failed', 'amigo-performance')));
        }
        
        $asset_id = intval($_POST['asset_id'] ?? 0);
        
        if (empty($asset_id)) {
            wp_send_json_error(array('message' => __('Missing asset ID', 'amigo-performance')));
        }
        
        global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table for asset management, no WP API equivalent
        $result = $wpdb->delete(
            $this->table_name,
            array('id' => $asset_id),
            array('%d')
        );
        
        // Clear relevant caches after data modification
        if ($result !== false) {
            wp_cache_delete('amigoperf_stats');
            wp_cache_delete('amigoperf_all_assets');
            // Clear all dequeued asset caches (we don't know which page this asset belongs to)
            wp_cache_flush();
        }
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => __('Asset deleted successfully', 'amigo-performance'),
                'asset_id' => $asset_id,
                'redirect_nonce' => wp_create_nonce('amigoperf_asset_deleted')
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete asset', 'amigo-performance')));
        }
    }
}
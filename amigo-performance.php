<?php
/**
 * Plugin Name:       Amigo Performance
 * Plugin URI:        https://github.com/AmigoDheena/Amigo-Performance
 * Description:       A lightweight performance optimization plugin that boosts website speed by removing query strings, disabling emojis, enabling script defer, and implementing lazy loading for images and iframes to improve scores in Google PageSpeed Insights and GTmetrix.
 * Version:           3.2
 * Author:            Amigo Dheena
 * Author URI:        https://github.com/AmigoDheena
 * Text Domain:       amigo-performance
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 6.0
 * Tested up to:      6.9
 * Requires PHP:      8.0
 * 
 * @package amigo-performance
 * @author Amigo Dheena
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include WordPress core files
include_once(ABSPATH . 'wp-admin/includes/file.php');
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Define plugin constants
if (!defined('AMIGOPERF_PLUGIN_VERSION')) {
    define('AMIGOPERF_PLUGIN_VERSION', '3.2');
}

if (!defined('AMIGOPERF_PLUGIN_PATH')) {
    define('AMIGOPERF_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('AMIGOPERF_PLUGIN_URL')) {
    define('AMIGOPERF_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Define debug mode constant - MUST be set to false in production
if (!defined('AMIGOPERF_DEBUG')) {
    /**
     * Controls debug logging for Amigo Performance plugin
     * 
     * When set to true AND WP_DEBUG is true, will enable debug logging
     * This should ALWAYS be set to false in production environments
     *
     * To enable debugging, add this to wp-config.php:
     * define('AMIGOPERF_DEBUG', true);
     */
    define('AMIGOPERF_DEBUG', false);
}

// Load text domain
function amigoperf_load_textdomain() {
    load_plugin_textdomain(
        'amigo-performance',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
add_action('plugins_loaded', 'amigoperf_load_textdomain', 1);

// Include class files
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-logger.php';
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-core.php';
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-lazyload.php';
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-minify.php';
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-asset-manager.php';
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-admin.php';
require_once AMIGOPERF_PLUGIN_PATH . 'includes/class-amigo-performance-settings.php';

/**
 * Main Plugin Class
 */
class AmigoPerformancePlugin {
    
    // Plugin properties
    public $amigoPerf_hfn = 'amigoPerf_hfn';
    public $amigoPerf_PluginName = 'Amigo Performance';
    public $amigoPerf_PluginVersion = 'amigoPerf_plugin_version';
    public $version = AMIGOPERF_PLUGIN_VERSION;
    
    // Settings properties
    public $amigoPerf_rqs;
    public $amigoPerf_rqs_opt;
    public $amigoPerf_rqs_val;
    public $amigoPerf_remoji;
    public $amigoPerf_remoji_opt;
    public $amigoPerf_remoji_val;
    public $amigoPerf_defer;
    public $amigoPerf_defer_opt;
    public $amigoPerf_defer_val;
    public $amigoPerf_iframelazy;
    public $amigoPerf_iframelazy_opt;
    public $amigoPerf_iframelazy_val;
    public $amigoPerf_lazyload;
    public $amigoPerf_lazyload_opt;
    public $amigoPerf_lazyload_val;
    public $amigoPerf_minify_css;
    public $amigoPerf_minify_css_opt;
    public $amigoPerf_minify_css_val;
    public $amigoPerf_minify_js;
    public $amigoPerf_minify_js_opt;
    public $amigoPerf_minify_js_val;
    
    // Component instances
    private $core;
    private $lazyload;
    private $minify;
    private $asset_manager;
    private $admin;
    private $settings;
    private $addons;
    
    // Asset Manager properties
    public $asset_manager_enabled = true;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_components();
        $this->init_hooks();
    }
    
    /**
     * Initialize component classes
     */
    private function init_components() {
        $this->core = new AmigoPerformance_Core($this);
        $this->lazyload = new AmigoPerformance_LazyLoad($this);
        $this->minify = new AmigoPerformance_Minify($this);
        $this->asset_manager = new AmigoPerformance_AssetManager($this);
        $this->admin = new AmigoPerformance_Admin($this);
        $this->settings = new AmigoPerformance_Settings($this);
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Initialize components
        $this->settings->init();
        $this->admin->init();
        
        if ($this->asset_manager_enabled) {
            add_action('init', array($this->asset_manager, 'init'));
        }
        
        // Performance optimizations
        add_action('init', array($this, 'init_performance_features'));
        
        // Version checker
        add_action('init', array($this->settings, 'check_version_and_update'));
        
        // Version tracking for WordPress.org statistics
        add_action('init', array($this, 'track_version_usage'), 20);
    }
    
    /**
     * Initialize performance features
     */
    public function init_performance_features() {
        $this->core->execute_query_strings_removal();
        $this->core->execute_emoji_removal();
        $this->core->execute_defer_javascript();
        $this->lazyload->init_image_lazy_loading();
        $this->lazyload->init_iframe_lazy_loading();
        $this->minify->init();
    }
    
    /**
     * Track version usage for WordPress.org statistics
     */
    public function track_version_usage() {
        $current_version = AMIGOPERF_PLUGIN_VERSION;
        $stored_version = get_option($this->amigoPerf_PluginVersion, '1.0');
        
        // Update version if it's different (important for statistics)
        if ($stored_version !== $current_version) {
            update_option($this->amigoPerf_PluginVersion, $current_version);
            update_option('amigoperf_version_updated', time());
            
            // Clear any version-related cache
            if (function_exists('wp_cache_delete')) {
                wp_cache_delete('amigoperf_version', 'options');
            }
        }
        
        // Ensure WordPress knows about our current version
        if (!defined('AMIGOPERF_VERSION_TRACKED')) {
            define('AMIGOPERF_VERSION_TRACKED', $current_version);
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        if ($this->asset_manager_enabled) {
            $this->asset_manager->create_table();
            delete_option('amigoperf_asset_cache');
        }
        
        // Properly store the version in database with correct option name
        update_option($this->amigoPerf_PluginVersion, AMIGOPERF_PLUGIN_VERSION);
        
        // Also update WordPress version tracking for statistics
        if (function_exists('wp_get_current_user')) {
            update_option('amigoperf_activation_version', AMIGOPERF_PLUGIN_VERSION);
            update_option('amigoperf_last_version_check', time());
        }
        
        return AMIGOPERF_PLUGIN_VERSION;
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
        delete_option('amigoPerf_nq_script');
        delete_option('amigoPerf_nq_style');
    }
    
    /**
     * Get asset manager stats (for backward compatibility)
     */
    public function get_asset_manager_stats() {
        if ($this->asset_manager_enabled && $this->asset_manager) {
            return $this->asset_manager->get_stats();
        }
        return array(
            'total_assets' => 0,
            'dequeued_assets' => 0,
            'unique_pages' => 0,
            'css_assets' => 0,
            'js_assets' => 0
        );
    }
    
    /**
     * Get all managed assets (for backward compatibility)
     */
    public function get_all_managed_assets() {
        if ($this->asset_manager_enabled && $this->asset_manager) {
            return $this->asset_manager->get_all_assets();
        }
        return array();
    }
    
    /**
     * Add asset manager admin bar items (for backward compatibility)
     */
    public function add_asset_manager_admin_bar_items($admin_bar) {
        // This method is now handled by the Admin class
        // Keeping for backward compatibility
    }
    
    /**
     * Register addon (for backward compatibility)
     */
    public function register_addon($addon_id, $addon_data) {
        return $this->addons->register_addon($addon_id, $addon_data);
    }
    
    /**
     * Check if addon is active (for backward compatibility)
     */
    public function is_addon_active($addon_id) {
        return $this->addons->is_addon_active($addon_id);
    }
    
    /**
     * Get active addons (for backward compatibility)
     */
    public function get_active_addons() {
        return $this->addons->get_active_addons();
    }
}

// Initialize the plugin
if (class_exists('AmigoPerformancePlugin')) {
    
    // Create plugin instances
    $amigoperformanceplugin = new AmigoPerformancePlugin();
    
    // Make the main plugin instance globally accessible
    global $amigo_performance_instance, $amigo_performance_addons;
    $amigo_performance_instance = $amigoperformanceplugin;
    $amigo_performance_addons = $amigoperformanceplugin->addons ?? null;
    
    // Trigger add-on system hook
    do_action('amigoperf_addons_loaded');
}

// Activation and deactivation hooks
register_activation_hook(__FILE__, array($amigoperformanceplugin, 'activate'));
register_deactivation_hook(__FILE__, array($amigoperformanceplugin, 'deactivate'));

// Legacy helper functions for add-ons (maintained for backward compatibility)
if (!function_exists('amigoperf_register_addon')) {
    function amigoperf_register_addon($addon_id, $addon_data) {
        global $amigo_performance_instance;
        if ($amigo_performance_instance && method_exists($amigo_performance_instance, 'register_addon')) {
            return $amigo_performance_instance->register_addon($addon_id, $addon_data);
        }
        return false;
    }
}

if (!function_exists('amigoperf_is_addon_active')) {
    function amigoperf_is_addon_active($addon_id) {
        global $amigo_performance_instance;
        if ($amigo_performance_instance && method_exists($amigo_performance_instance, 'is_addon_active')) {
            return $amigo_performance_instance->is_addon_active($addon_id);
        }
        return false;
    }
}

if (!function_exists('amigoperf_get_active_addons')) {
    function amigoperf_get_active_addons() {
        global $amigo_performance_instance;
        if ($amigo_performance_instance && method_exists($amigo_performance_instance, 'get_active_addons')) {
            return $amigo_performance_instance->get_active_addons();
        }
        return array();
    }
}

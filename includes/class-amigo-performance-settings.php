<?php
/**
 * Settings Management
 * 
 * @package amigo-performance
 * @version 3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class AmigoPerformance_Settings {
    
    private $plugin_instance;
    
    public function __construct($plugin_instance) {
        $this->plugin_instance = $plugin_instance;
    }
    
    /**
     * Initialize settings
     */
    public function init() {
        add_action('admin_init', array($this, 'process_form_submission'));
        $this->set_default_values();
    }
    
    /**
     * Set default option values
     */
    public function set_default_values() {
        // Store version properly - no circular reference
        $stored_version = get_option($this->plugin_instance->amigoPerf_PluginVersion, '1.0');
        
        // Update to current version if different
        if (version_compare($stored_version, AMIGOPERF_PLUGIN_VERSION, '<')) {
            update_option($this->plugin_instance->amigoPerf_PluginVersion, AMIGOPERF_PLUGIN_VERSION);
        }

        // Remove Query Strings
        $this->plugin_instance->amigoPerf_rqs = 'amigoPerf_rqs';
        $this->plugin_instance->amigoPerf_rqs_opt = get_option($this->plugin_instance->amigoPerf_rqs, true);
        $this->plugin_instance->amigoPerf_rqs_val = $this->plugin_instance->amigoPerf_rqs_opt;

        // Remove Emoji
        $this->plugin_instance->amigoPerf_remoji = 'amigoPerf_remoji';
        $this->plugin_instance->amigoPerf_remoji_opt = get_option($this->plugin_instance->amigoPerf_remoji, true);
        $this->plugin_instance->amigoPerf_remoji_val = $this->plugin_instance->amigoPerf_remoji_opt;

        // Defer JavaScript
        $this->plugin_instance->amigoPerf_defer = 'amigoPerf_defer';
        $this->plugin_instance->amigoPerf_defer_opt = get_option($this->plugin_instance->amigoPerf_defer, true);
        $this->plugin_instance->amigoPerf_defer_val = $this->plugin_instance->amigoPerf_defer_opt;

        // Iframe Lazy Loading
        $this->plugin_instance->amigoPerf_iframelazy = 'amigoPerf_iframelazy';
        $this->plugin_instance->amigoPerf_iframelazy_opt = get_option($this->plugin_instance->amigoPerf_iframelazy, true);
        $this->plugin_instance->amigoPerf_iframelazy_val = $this->plugin_instance->amigoPerf_iframelazy_opt;

        // Image Lazy Loading
        $this->plugin_instance->amigoPerf_lazyload = 'amigoPerf_lazyload';
        $this->plugin_instance->amigoPerf_lazyload_opt = get_option($this->plugin_instance->amigoPerf_lazyload, true);
        $this->plugin_instance->amigoPerf_lazyload_val = $this->plugin_instance->amigoPerf_lazyload_opt;
    }
    
    /**
     * Process form submission
     */
    public function process_form_submission() {
        if (!isset($_POST[$this->plugin_instance->amigoPerf_hfn]) || $_POST[$this->plugin_instance->amigoPerf_hfn] !== 'Y') {
            return;
        }

        // Security checks
        if (!isset($_POST['amigo_basic_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['amigo_basic_nonce'])), 'amigo_basic_settings_action') ||
            !current_user_can('manage_options')) {
            wp_die('Security verification failed.');
        }

        // Process each setting
        $this->process_setting('amigoPerf_rqs');
        $this->process_setting('amigoPerf_remoji');
        $this->process_setting('amigoPerf_defer');
        $this->process_setting('amigoPerf_iframelazy');
        $this->process_setting('amigoPerf_lazyload');

        flush_rewrite_rules();
    }
    
    /**
     * Process individual setting
     */
    private function process_setting($setting_name) {
        // For checkboxes: if present in POST, it's checked (true), if not present, it's unchecked (false)
        // Note: This method is only called after nonce verification in process_form_submission()
        $post_key = $this->plugin_instance->$setting_name;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification handled in calling method process_form_submission()
        $value = isset($_POST[$post_key]) ? true : false;
        $val_property = $setting_name . '_val';
        $opt_property = $setting_name . '_opt';
        
        // Update both the val and opt properties
        $this->plugin_instance->$val_property = $value;
        $this->plugin_instance->$opt_property = $value;
        
        // Save to database
        update_option($this->plugin_instance->$setting_name, $value);
    }
    
    /**
     * Check plugin version and handle updates
     */
    public function check_version_and_update() {
        $version = get_option($this->plugin_instance->amigoPerf_PluginVersion);
        
        if (version_compare($version, AMIGOPERF_PLUGIN_VERSION, '<')) {
            // Handle updates from previous versions
            if (version_compare($version, '2.5', '<')) {
                // Perform 2.5 update tasks if needed
            }
            
            if (version_compare($version, '2.7', '<')) {
                // Perform 2.7 update tasks if needed
            }
            
            if (version_compare($version, '3.0', '<')) {
                // Perform 3.0 update tasks - major restructure complete
                // Clear all caches due to major changes
                if (function_exists('wp_cache_flush')) {
                    wp_cache_flush();
                }
                // Update version tracking options
                update_option('amigoperf_major_update_3_0', time());
            }
            
            // Always update the stored version number
            update_option($this->plugin_instance->amigoPerf_PluginVersion, AMIGOPERF_PLUGIN_VERSION);
        }
    }
    
    /**
     * Check if current version matches
     */
    public function is_current_version() {
        return version_compare($this->plugin_instance->version ?? '', AMIGOPERF_PLUGIN_VERSION, '=');
    }
}

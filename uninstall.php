<?php
/**
 * Uninstall Amigo Performance Plugin
 * 
 * This file is executed when the plugin is uninstalled.
 * It removes all plugin data, options, database tables, and files.
 * 
 * @package amigo-performance
 * @version 3.2
 */

// Prevent direct access
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Security check: Only proceed if user has proper permissions
if (!current_user_can('activate_plugins')) {
    return;
}

global $wpdb;

/**
 * 1. DELETE ALL PLUGIN OPTIONS
 * Remove all WordPress options created by the plugin
 */

// Core feature settings
delete_option('amigoPerf_rqs');                    // Remove Query Strings
delete_option('amigoPerf_remoji');                 // Remove Emoji Scripts
delete_option('amigoPerf_defer');                  // Defer JavaScript
delete_option('amigoPerf_iframelazy');             // Iframe Lazy Loading
delete_option('amigoPerf_lazyload');               // Image Lazy Loading

// Premium feature settings
delete_option('amigoPerf_minify_css');             // Minify CSS
delete_option('amigoPerf_minify_js');              // Minify JavaScript

// Version and tracking options
delete_option('amigoPerf_plugin_version');         // Plugin version tracking
delete_option('amigoperf_version_updated');        // Version update timestamp
delete_option('amigoperf_activation_version');     // Activation version
delete_option('amigoperf_last_version_check');     // Last version check
delete_option('amigoperf_major_update_3_0');       // Major update marker

// Legacy and deprecated options (clean up from older versions)
delete_option('amigoPerf_nq_script');              // Legacy script option
delete_option('amigoPerf_nq_style');               // Legacy style option
delete_option('amigoperf_asset_cache');            // Asset cache option
delete_option('amigoperf_error_log');              // Error logs (if implemented)

/**
 * 2. DROP DATABASE TABLES
 * Remove custom database tables created by the plugin
 */

// Asset Manager table
$asset_manager_table = $wpdb->prefix . 'amigoperf_asset_manager';

// Clear any cached table existence checks before dropping
wp_cache_delete('amigoperf_table_exists_' . $asset_manager_table);
wp_cache_delete($asset_manager_table, 'amigoperf_tables');

// Sanitize table name and execute drop query
// Note: Table names cannot be used with $wpdb->prepare() placeholders
$table_name = esc_sql($asset_manager_table);

// Validate table name format for security
if (preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
    // For DDL operations like DROP TABLE, WordPress core itself uses this pattern
    // Table names cannot be placeholders in prepared statements
    
    // phpcs:disable WordPress.DB -- The following approach is used by WordPress core for dropping tables
    $query = "DROP TABLE IF EXISTS `$table_name`";
    $wpdb->query($query);
    // phpcs:enable WordPress.DB
}

/**
 * 3. CLEAR OBJECT CACHE
 * Remove cached data from WordPress object cache
 */

// Clear all plugin-related cache entries
$cache_keys_to_clear = array(
  'amigoperf_stats',
  'amigoperf_table_exists_' . $asset_manager_table,
  'amigoperf_dequeued_assets_*', // Pattern - actual keys vary by URL
);

foreach ($cache_keys_to_clear as $cache_key) {
  if (strpos($cache_key, '*') !== false) {
    // For pattern-based cache keys, we'll use wp_cache_flush for safety
    wp_cache_flush();
    break;
  } else {
    wp_cache_delete($cache_key);
  }
}

/**
 * 4. REMOVE MINIFIED FILES
 * Clean up minified CSS/JS files created by the minification feature
 */

function amigoperf_cleanup_minified_files() {
    $cleanup_paths = array(
      ABSPATH . 'wp-content/themes',
      ABSPATH . 'wp-content/plugins',
    );
    
    // Add uploads directory if available
    $upload_dir = wp_upload_dir();
    if (!empty($upload_dir['basedir']) && is_dir($upload_dir['basedir'])) {
        $cleanup_paths[] = $upload_dir['basedir'];
    }
    
    foreach ($cleanup_paths as $path) {
        amigoperf_recursive_cleanup_minified($path);
    }
}

function amigoperf_recursive_cleanup_minified($dir) {
    if (!is_dir($dir) || !is_readable($dir)) {
        return;
    }
    
    // Initialize WordPress filesystem
    global $wp_filesystem;
    if (empty($wp_filesystem)) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
    }
    
    // Remove .amigo-min.* files
    $files = glob($dir . '/*.amigo-min.*');
    if (is_array($files)) {
        foreach ($files as $file) {
            if ($wp_filesystem->is_file($file)) {
                wp_delete_file($file);
            }
        }
    }
    
    // Recurse into subdirectories
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
    if (is_array($subdirs)) {
        foreach ($subdirs as $subdir) {
            amigoperf_recursive_cleanup_minified($subdir);
        }
    }
}

// Execute minified files cleanup
amigoperf_cleanup_minified_files();

/**
 * 5. CLEAR TRANSIENTS
 * Remove any transient data that might have been created
 */

// Clear any plugin-specific transients
delete_transient('amigoperf_stats');
delete_transient('amigoperf_asset_data');
delete_transient('amigoperf_version_check');

// Clear site transients (multisite)
delete_site_transient('amigoperf_network_stats');
delete_site_transient('amigoperf_network_version');

/**
 * 6. FLUSH REWRITE RULES
 * Clear any cached rewrite rules that might reference plugin functionality
 */
flush_rewrite_rules();

/**
 * 7. CLEAR PLUGIN-RELATED USER META
 * Remove user-specific settings or preferences
 */

// Clear any user meta that might have been set
$users = get_users(array('fields' => 'ID'));
foreach ($users as $user_id) {
    delete_user_meta($user_id, 'amigoperf_hide_notices');
    delete_user_meta($user_id, 'amigoperf_preferences');
}

/**
 * 8. MULTISITE CLEANUP
 * If this is a multisite installation, clean up network-wide options
 */

if (is_multisite()) {
    delete_site_option('amigoperf_network_version');
    delete_site_option('amigoperf_network_activated');
    delete_site_option('amigoperf_network_settings');
}

/**
 * 9. FINAL CLEANUP VERIFICATION
 * Log the cleanup completion (only in debug mode)
 */

if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
    // Use WordPress logging instead of error_log
    if (function_exists('wp_debug_log')) {
        wp_debug_log('Amigo Performance: Plugin uninstall cleanup completed successfully');
    } else {
        // Fallback to WordPress file system for logging
        $log_message = gmdate('[Y-m-d H:i:s] ') . 'Amigo Performance: Plugin uninstall cleanup completed successfully' . PHP_EOL;
        if (defined('WP_DEBUG_LOG') && is_string(WP_DEBUG_LOG)) {
            $log_file = WP_DEBUG_LOG;
        } else {
            $log_file = WP_CONTENT_DIR . '/debug.log';
        }
        
        // Use WordPress filesystem for writing
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }
        
        if ($wp_filesystem && $wp_filesystem->is_writable(dirname($log_file))) {
            $existing_content = '';
            if ($wp_filesystem->exists($log_file)) {
                $existing_content = $wp_filesystem->get_contents($log_file);
            }
            $wp_filesystem->put_contents($log_file, $existing_content . $log_message, FS_CHMOD_FILE);
        }
    }
}

// Optional: Show admin notice for successful cleanup (commented out for production)
// add_action('admin_notices', function() {
//     echo '<div class="notice notice-success"><p>Amigo Performance: All plugin data has been successfully removed.</p></div>';
// });

/**
 * UNINSTALL COMPLETE
 * 
 * The following data has been removed:
 * - All WordPress options and settings
 * - Custom database tables (wp_amigoperf_asset_manager)
 * - Object cache entries
 * - Minified CSS/JS files (.amigo-min.*)
 * - Transient data
 * - User meta data
 * - Rewrite rules
 * - Multisite options (if applicable)
 */
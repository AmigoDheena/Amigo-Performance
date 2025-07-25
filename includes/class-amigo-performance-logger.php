<?php
/**
 * Logger Utility
 * 
 * @package amigo-performance
 * @version 3.2
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Static logger class for Amigo Performance plugin
 */
class AmigoPerformance_Logger {
    // Define log levels
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    
    /**
     * Determines if debug logging is enabled
     *
     * @return boolean True if debug logging is enabled
     */
    private static function is_debug_enabled() {
        return defined('WP_DEBUG') && WP_DEBUG && defined('AMIGOPERF_DEBUG') && AMIGOPERF_DEBUG;
    }
    
    /**
     * Log a message if debug mode is enabled
     * 
     * @param string $message The message to log
     * @param string $level The log level (info, warning, error)
     * @return void
     */
    public static function log($message, $level = self::LEVEL_INFO) {
        // Only perform debug logging when explicitly enabled
        if (self::is_debug_enabled()) {
            $formatted_message = "Amigo Performance [{$level}]: " . $message;
            
            // In debug mode, we can use WordPress logging functions
            if (function_exists('wp_debug_log')) {
                wp_debug_log($formatted_message);
            } elseif (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                // Use WordPress file system APIs for logging instead of error_log
                if (function_exists('wp_mkdir_p') && function_exists('wp_normalize_path')) {
                    $log_path = is_string(WP_DEBUG_LOG) ? WP_DEBUG_LOG : WP_CONTENT_DIR . '/debug.log';
                    $log_dir = dirname($log_path);
                    
                    // Create log directory if it doesn't exist
                    wp_mkdir_p($log_dir);
                    
                    // Append to the log file using WP file system methods
                    @file_put_contents(
                        wp_normalize_path($log_path),
                        gmdate('[Y-m-d H:i:s]') . ' ' . $formatted_message . PHP_EOL,
                        FILE_APPEND
                    );
                }
            }
        } 
        // For error level logs in production, we can use alternative logging
        elseif ($level === self::LEVEL_ERROR) {
            self::log_critical_error($message);
        }
    }
    
    /**
     * Log critical errors in production in a safe way
     * 
     * @param string $message Error message
     * @return void
     */
    private static function log_critical_error($message) {
        // This method can be expanded to log critical errors in production
        // using WordPress options or custom database tables instead of error_log
        
        // Example implementation (commented out):
        // $errors = get_option('amigoperf_error_log', array());
        // $errors[] = array(
        //     'message' => $message,
        //     'time' => current_time('mysql'),
        // );
        // $errors = array_slice($errors, -20); // Keep only last 20 errors
        // update_option('amigoperf_error_log', $errors);
    }
    
    /**
     * Info level log - for general information and successful operations
     * 
     * @param string $message The message to log
     * @return void
     */
    public static function info($message) {
        self::log($message, self::LEVEL_INFO);
    }
    
    /**
     * Warning level log - for non-critical issues that should be addressed
     * 
     * @param string $message The message to log
     * @return void
     */
    public static function warning($message) {
        self::log($message, self::LEVEL_WARNING);
    }
    
    /**
     * Error level log - for critical errors that affect functionality
     * 
     * @param string $message The message to log
     * @return void
     */
    public static function error($message) {
        self::log($message, self::LEVEL_ERROR);
    }
    
    /**
     * Gets the log level as a human-readable string
     *
     * @param string $level The log level
     * @return string Human-readable log level
     */
    private static function get_level_name($level) {
        switch ($level) {
            case self::LEVEL_INFO:
                return 'INFO';
            case self::LEVEL_WARNING:
                return 'WARNING';
            case self::LEVEL_ERROR:
                return 'ERROR';
            default:
                return 'UNKNOWN';
        }
    }
}

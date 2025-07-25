<?php
/**
 * Core Performance Optimization Methods
 * 
 * @package amigo-performance
 * @version 3.2
 */

if (!defined('ABSPATH')) {
    exit;
}

class AmigoPerformance_Core {
    
    private $plugin_instance;
    
    public function __construct($plugin_instance) {
        $this->plugin_instance = $plugin_instance;
    }
    
    /**
     * Remove query strings from CSS and JS files
     */
    public function remove_query_strings($src) {
        if(strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Execute query string removal
     */
    public function execute_query_strings_removal() {
        if(get_option($this->plugin_instance->amigoPerf_rqs, true)) {
            if(!is_admin()) {
                add_filter('style_loader_src', array($this, 'remove_query_strings'), 10, 2);
                add_filter('script_loader_src', array($this, 'remove_query_strings'), 10, 2);
            }
        }
    }
    
    /**
     * Remove emoji scripts and styles
     */
    public function execute_emoji_removal() {
        if(get_option($this->plugin_instance->amigoPerf_remoji, true)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7); 
            remove_action('admin_print_scripts', 'print_emoji_detection_script'); 
            remove_action('wp_print_styles', 'print_emoji_styles'); 
            remove_action('admin_print_styles', 'print_emoji_styles');
        }
    }
    
    /**
     * Defer JavaScript execution
     */
    public function execute_defer_javascript() {
        if(get_option($this->plugin_instance->amigoPerf_defer, true)) {
            if(!is_admin()) {
                add_filter('script_loader_tag', function($tag, $handle) {
                    if(is_front_page()) {
                        if('jquery-core' == $handle) { 
                            return $tag; 
                        } 
                    } else {
                        return $tag;
                    }
                    return str_replace(' src', ' defer="defer" src', $tag);
                }, 10, 2);
            }
        }
    }
}

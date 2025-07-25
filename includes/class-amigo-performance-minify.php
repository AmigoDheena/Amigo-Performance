<?php
/**
 * Amigo Performance Minification Class
 * 
 * Handles CSS and JavaScript minification
 * 
 * @package amigo-performance
 * @version 3.2
 */

if (!defined('ABSPATH')) {
    exit;
}

class AmigoPerformance_Minify {
    
    private $plugin;
    
    /**
     * Constructor
     */
    public function __construct($plugin) {
        $this->plugin = $plugin;
    }
    
    /**
     * Initialize minification features
     */
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'setup_minification'), 999);
        add_action('wp_print_styles', array($this, 'minify_css_output'), 999);
        add_action('wp_print_scripts', array($this, 'minify_js_output'), 999);
    }
    
    /**
     * Setup minification hooks
     */
    public function setup_minification() {
        if ($this->plugin->amigoPerf_minify_css_opt) {
            add_filter('style_loader_src', array($this, 'minify_css_file'), 10, 2);
        }
        
        if ($this->plugin->amigoPerf_minify_js_opt) {
            add_filter('script_loader_src', array($this, 'minify_js_file'), 10, 2);
        }
    }
    
    /**
     * Minify CSS file
     */
    public function minify_css_file($src, $handle) {
        // Skip external files and already minified files
        if ($this->should_skip_minification($src, 'css')) {
            return $src;
        }
        
        $minified_src = $this->get_minified_file_url($src, 'css');
        
        if ($minified_src) {
            AmigoPerformance_Logger::info("CSS Minification: Serving minified version for {$handle}");
            return $minified_src;
        }
        
        return $src;
    }
    
    /**
     * Minify JS file
     */
    public function minify_js_file($src, $handle) {
        // Skip external files and already minified files
        if ($this->should_skip_minification($src, 'js')) {
            return $src;
        }
        
        $minified_src = $this->get_minified_file_url($src, 'js');
        
        if ($minified_src) {
            AmigoPerformance_Logger::info("JS Minification: Serving minified version for {$handle}");
            return $minified_src;
        }
        
        return $src;
    }
    
    /**
     * Check if file should be skipped from minification
     */
    private function should_skip_minification($src, $type) {
        // Skip external files
        if (!$this->is_local_file($src)) {
            return true;
        }
        
        // Skip already minified files
        if (strpos($src, '.min.') !== false) {
            return true;
        }
        
        // Skip admin files
        if (strpos($src, '/wp-admin/') !== false) {
            return true;
        }
        
        // Skip certain handles that might break
        $skip_handles = array(
            'jquery',
            'jquery-core',
            'jquery-migrate',
            'wp-embed'
        );
        
        foreach ($skip_handles as $skip_handle) {
            if (strpos($src, $skip_handle) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if file is local
     */
    private function is_local_file($src) {
        $site_url = site_url();
        return strpos($src, $site_url) === 0 || strpos($src, '/') === 0;
    }
    
    /**
     * Get minified file URL
     */
    private function get_minified_file_url($src, $type) {
        // Convert URL to file path
        $file_path = $this->url_to_path($src);
        
        if (!$file_path || !file_exists($file_path)) {
            return false;
        }
        
        // Generate minified file path
        $minified_path = $this->get_minified_file_path($file_path, $type);
        
        // Check if minified file exists and is newer than original
        if (!file_exists($minified_path) || filemtime($file_path) > filemtime($minified_path)) {
            // Create minified file
            if (!$this->create_minified_file($file_path, $minified_path, $type)) {
                return false;
            }
        }
        
        // Convert back to URL
        return $this->path_to_url($minified_path);
    }
    
    /**
     * Convert URL to file path
     */
    private function url_to_path($url) {
        // Remove query parameters
        $url = strtok($url, '?');
        
        // Convert URL to file path
        $site_url = site_url();
        if (strpos($url, $site_url) === 0) {
            $relative_path = str_replace($site_url, '', $url);
            return ABSPATH . ltrim($relative_path, '/');
        } elseif (strpos($url, '/') === 0) {
            return ABSPATH . ltrim($url, '/');
        }
        
        return false;
    }
    
    /**
     * Convert file path to URL
     */
    private function path_to_url($path) {
        $relative_path = str_replace(ABSPATH, '', $path);
        return site_url('/' . $relative_path);
    }
    
    /**
     * Get minified file path
     */
    private function get_minified_file_path($original_path, $type) {
        $path_info = pathinfo($original_path);
        return $path_info['dirname'] . '/' . $path_info['filename'] . '.amigo-min.' . $path_info['extension'];
    }
    
    /**
     * Create minified file
     */
    private function create_minified_file($original_path, $minified_path, $type) {
        // Read original file
        $content = file_get_contents($original_path);
        
        if ($content === false) {
            AmigoPerformance_Logger::error("Minification: Could not read file {$original_path}");
            return false;
        }
        
        // Minify content
        if ($type === 'css') {
            $minified_content = $this->minify_css_content($content);
        } else {
            $minified_content = $this->minify_js_content($content);
        }
        
        // Ensure directory exists
        $dir = dirname($minified_path);
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
        
        // Write minified file
        $result = file_put_contents($minified_path, $minified_content);
        
        if ($result === false) {
            AmigoPerformance_Logger::error("Minification: Could not write minified file {$minified_path}");
            return false;
        }
        
        AmigoPerformance_Logger::info("Minification: Created minified file {$minified_path}");
        return true;
    }
    
    /**
     * Minify CSS content
     */
    private function minify_css_content($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        // Remove whitespace around specific characters
        $css = str_replace(array(' {', '{ ', ' }', '} ', ' :', ': ', ' ;', '; ', ' ,', ', '), array('{', '{', '}', '}', ':', ':', ';', ';', ',', ','), $css);
        
        // Remove trailing semicolon before closing brace
        $css = str_replace(';}', '}', $css);
        
        // Remove any remaining unnecessary whitespace
        $css = trim($css);
        
        return $css;
    }
    
    /**
     * Minify JavaScript content
     */
    private function minify_js_content($js) {
        // Basic JS minification - remove comments and unnecessary whitespace
        // Note: This is a simple minification. For production, consider using a proper JS minifier
        
        // Remove single-line comments (but preserve URLs and regexes)
        $js = preg_replace('~(?<!:)//.*$~m', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Remove whitespace around operators and brackets
        $js = str_replace(array(' = ', ' + ', ' - ', ' * ', ' / ', ' { ', ' } ', ' ( ', ' ) ', ' [ ', ' ] ', ' ; ', ' , '), 
                          array('=', '+', '-', '*', '/', '{', '}', '(', ')', '[', ']', ';', ','), $js);
        
        return trim($js);
    }
    
    /**
     * Minify inline CSS output
     */
    public function minify_css_output() {
        if (!$this->plugin->amigoPerf_minify_css_opt) {
            return;
        }
        
        ob_start(array($this, 'minify_inline_css'));
    }
    
    /**
     * Minify inline JS output
     */
    public function minify_js_output() {
        if (!$this->plugin->amigoPerf_minify_js_opt) {
            return;
        }
        
        ob_start(array($this, 'minify_inline_js'));
    }
    
    /**
     * Minify inline CSS callback
     */
    public function minify_inline_css($buffer) {
        // Minify inline CSS within <style> tags
        $buffer = preg_replace_callback(
            '/<style[^>]*>(.*?)<\/style>/is',
            function($matches) {
                return '<style>' . $this->minify_css_content($matches[1]) . '</style>';
            },
            $buffer
        );
        
        return $buffer;
    }
    
    /**
     * Minify inline JS callback
     */
    public function minify_inline_js($buffer) {
        // Minify inline JavaScript within <script> tags
        $buffer = preg_replace_callback(
            '/<script[^>]*>(.*?)<\/script>/is',
            function($matches) {
                // Skip scripts with src attribute (external scripts)
                if (strpos($matches[0], 'src=') !== false) {
                    return $matches[0];
                }
                return '<script>' . $this->minify_js_content($matches[1]) . '</script>';
            },
            $buffer
        );
        
        return $buffer;
    }
    
    /**
     * Clean up minified files (utility function)
     */
    public function cleanup_minified_files() {
        $upload_dir = wp_upload_dir();
        $cleanup_paths = array(
            ABSPATH . 'wp-content/themes',
            ABSPATH . 'wp-content/plugins',
            $upload_dir['basedir']
        );
        
        foreach ($cleanup_paths as $path) {
            $this->recursive_cleanup($path);
        }
    }
    
    /**
     * Recursively clean up minified files
     */
    private function recursive_cleanup($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = glob($dir . '/*.amigo-min.*');
        foreach ($files as $file) {
            if (is_file($file)) {
                wp_delete_file($file);
            }
        }
        
        $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
        foreach ($subdirs as $subdir) {
            $this->recursive_cleanup($subdir);
        }
    }
}

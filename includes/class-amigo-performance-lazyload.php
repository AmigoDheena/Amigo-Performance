<?php
/**
 * Lazy Loading Implementation
 * 
 * @package amigo-performance
 * @version 3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class AmigoPerformance_LazyLoad {
    
    private $plugin_instance;
    
    public function __construct($plugin_instance) {
        $this->plugin_instance = $plugin_instance;
    }
    
    /**
     * Initialize image lazy loading
     */
    public function init_image_lazy_loading() {
        if(get_option($this->plugin_instance->amigoPerf_lazyload, true)) {
            $this->enqueue_lazy_scripts();
            add_filter('the_content', array($this, 'process_images_for_lazy_loading'));
        }
    }
    
    /**
     * Enqueue lazy loading scripts
     */
    private function enqueue_lazy_scripts() {
        add_action('wp_enqueue_scripts', function() {
            // Enqueue vanilla-lazyload script
            wp_enqueue_script(
                'vanilla-lazyload',
                plugin_dir_url(dirname(__FILE__)) . 'assets/js/lazyload.min.js',
                array(),
                '19.1.3',
                true
            );

            // Enqueue custom lazy-function.js
            wp_enqueue_script(
                'amigo-lazy-function',
                plugin_dir_url(dirname(__FILE__)) . 'assets/js/lazy-function.js',
                array('vanilla-lazyload'),
                '2.7',
                true
            );
        });
    }
    
    /**
     * Process images for lazy loading
     */
    public function process_images_for_lazy_loading($content) {
        if (is_admin() || empty($content)) {
            return $content;
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');
        
        foreach ($images as $img) {
            if ($img->hasAttribute('data-src')) {
                continue;
            }

            $src = $img->getAttribute('src');
            if (empty($src)) {
                continue;
            }

            $img->setAttribute('data-src', $src);
            $img->removeAttribute('src');
            
            $class = $img->getAttribute('class');
            $img->setAttribute('class', trim($class . ' lazy'));
        }

        return $dom->saveHTML();
    }
    
    /**
     * Initialize iframe lazy loading
     */
    public function init_iframe_lazy_loading() {
        if(get_option($this->plugin_instance->amigoPerf_iframelazy, true)) {
            $this->enqueue_iframe_script();
            
            if (!is_admin()) {
                add_action('template_redirect', array($this, 'start_iframe_buffer'));
                add_action('get_header', array($this, 'start_iframe_buffer'));
                add_action('wp_footer', array($this, 'end_iframe_buffer'));
            }
        }
    }
    
    /**
     * Enqueue iframe lazy loading script
     */
    private function enqueue_iframe_script() {
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_script(
                'amigo-iframe-lazy',
                plugin_dir_url(dirname(__FILE__)) . 'assets/js/iframe-lazy.js',
                array(),
                '2.7',
                true
            );
        });
    }
    
    /**
     * Start output buffering for iframe processing
     */
    public function start_iframe_buffer() {
        ob_start(array($this, 'process_iframe_buffer'));
    }
    
    /**
     * End output buffering
     */
    public function end_iframe_buffer() {
        ob_flush();
    }
    
    /**
     * Process iframe buffer for lazy loading
     */
    public function process_iframe_buffer($buffer) {
        $buffer = preg_replace('/<iframe([^>]*)(\s+src\s*=\s*["\'])([^"\'>]+)(["\'])/i', 
                              '<iframe$1 class="amigolazy" lazy="1500" data-src=$4$3$4', $buffer);
        return $buffer;
    }
}

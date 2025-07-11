<?php

/**
 * @package amigo-performance
 * @author Amigo Dheena
 
 * Plugin Name:       Amigo Performance
 * Plugin URI:        https://github.com/AmigoDheena/Amigo-Performance
 * Description:       Amigo Performance is used to Optimize Website Performance and improve Site Score in services like Google Page Speed Insight, GTmetrix.
 * Version:           2.5
 * Author:            Amigo Dheena
 * Author URI:        https://github.com/AmigoDheena
 * Text Domain:       amigo-performance
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if (!defined('ABSPATH')) {
    die;
}

include_once ( ABSPATH . 'wp-admin/includes/file.php' ); // to get get_home_path() function work
include_once ( ABSPATH . 'wp-admin/includes/plugin.php' ); // to is_plugin_active()() function work

// Define plugin version for future releases
if (!defined('AMIGOPERF_PLUGIN_VERSION')) {
    define('AMIGOPERF_PLUGIN_VERSION', '2.5');
}

// Load text domain immediately when WordPress is ready
function amigoperf_load_textdomain() {
    load_plugin_textdomain(
        'amigo-performance',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
// Load text domain as early as possible
add_action('plugins_loaded', 'amigoperf_load_textdomain', 1);

class AmigoPerformancePlugin{
    protected $amigoPerf_hfn = 'amigoPerf_hfn'; //hidden field name
    protected $amigoPerf_PluginName = 'Amigo Performance';
    protected $amigoPerf_PluginVersion;
    protected $amigoPerf_rqs;
    protected $amigoPerf_rqs_opt;
    protected $amigoPerf_rqs_val;
    protected $amigoPerf_remoji;
    protected $amigoPerf_remoji_opt;
    protected $amigoPerf_remoji_val;
    protected $amigoPerf_defer;
    protected $amigoPerf_defer_opt;
    protected $amigoPerf_defer_val;
    protected $amigoPerf_iframelazy;
    protected $amigoPerf_iframelazy_opt;
    protected $amigoPerf_iframelazy_val;
    protected $amigoPerf_lazyload;
    protected $amigoPerf_lazyload_opt;
    protected $amigoPerf_lazyload_val;
    protected $amigoPerf_nq_script;
    protected $amigoPerf_nqjs_array;
    protected $amigoPerf_dq_script;
    protected $amigoPerf_nqcss_array;
    protected $amigoPerf_dq_style;
    protected $amigoPerf_get_nq_js;
    protected $amigoPerf_dq_js_str_to_arr;
    protected $js_handle;
    protected $css_handle;
    protected $amigoPerf_dq_css_str_to_arr;
    protected $amigoPerf_get_nq_css;

    function amigoperformance_activate()
    {
        update_option( $this->amigoPerf_update_checker, AMIGOPERF_PLUGIN_VERSION );
        return AMIGOPERF_PLUGIN_VERSION;
    }

    function amigoperformance_deactivate()
    {
        flush_rewrite_rules();
        delete_option('amigoPerf_nq_script');
        delete_option('amigoPerf_nq_style');
    }

    // Check plugin version
    function amigoPerf_update_checker() {        
        $version = get_option( $this->amigoPerf_PluginVersion ); 
        if( version_compare($version, AMIGOPERF_PLUGIN_VERSION , '<')) {
            // Handle updates from previous versions
            if (version_compare($version, '2.5', '<')) {
                // Perform 2.5 update tasks if needed
                update_option( $this->amigoPerf_PluginVersion, AMIGOPERF_PLUGIN_VERSION );
            }
        }
    }

    function amigoPerf_is_current_version(){
        return version_compare($this->version, AMIGOPERF_PLUGIN_VERSION, '=') ? true : false;
    }

    // Enqueue Style sheets and Scripts
    function amigoperformance_enqueue_style(){
        wp_enqueue_style(
            'amigoperf_style',
            plugins_url('assets/css/style.css', __FILE__),
            array(),
            '2.5' // Updated for version 2.5
        );
    }

    function amigoperformance_enqueue_script(){
        wp_enqueue_script('amigoperf_script', plugins_url('assets/js/script.js',__FILE__),array(), '2.5', true );
    }

    // Register Style sheets and Scripts
    function amigoperformance_register(){
        add_action('admin_enqueue_scripts', array($this , 'amigoperformance_enqueue_style'));
        add_action('admin_enqueue_scripts', array($this , 'amigoperformance_enqueue_script') );
    }

    public function amigoPerf_Default() {
        global $amigoPerf_rqs_opt, $amigoPerf_remoji_opt, $amigoPerf_defer_opt, $amigoPerf_iframelazy_opt, $amigoPerf_lazyload_opt;

        $this->amigoPerf_PluginVersion = (get_option($this->amigoPerf_PluginVersion) ? get_option($this->amigoPerf_PluginVersion) : AMIGOPERF_PLUGIN_VERSION);

        $this->amigoPerf_rqs = 'amigoPerf_rqs';
        $this->amigoPerf_rqs_opt = (FALSE !== get_option($this->amigoPerf_rqs) ? get_option($this->amigoPerf_rqs) : TRUE);
        $this->amigoPerf_rqs_val = $this->amigoPerf_rqs_opt;

        $this->amigoPerf_remoji_opt = (FALSE !== get_option($this->amigoPerf_remoji) ? get_option($this->amigoPerf_remoji) : TRUE);
        $this->amigoPerf_remoji = 'amigoPerf_remoji';
        $this->amigoPerf_remoji_val = $amigoPerf_remoji_opt;

        $this->amigoPerf_defer_opt = (FALSE !== get_option($this->amigoPerf_defer) ? get_option($this->amigoPerf_defer) : TRUE);
        $this->amigoPerf_defer = 'amigoPerf_defer';
        $this->amigoPerf_defer_val = $amigoPerf_defer_opt;

        $this->amigoPerf_iframelazy_opt = (FALSE !== get_option($this->amigoPerf_iframelazy) ? get_option($this->amigoPerf_iframelazy) : TRUE);
        $this->amigoPerf_iframelazy = 'amigoPerf_iframelazy';
        $this->amigoPerf_iframelazy_val = $amigoPerf_iframelazy_opt;

        $this->amigoPerf_lazyload_opt = (FALSE !== get_option($this->amigoPerf_lazyload) ? get_option($this->amigoPerf_lazyload) : TRUE);
        $this->amigoPerf_lazyload = 'amigoPerf_lazyload';
        $this->amigoPerf_lazyload_val = $amigoPerf_lazyload_opt;
    }

    public function amigoperf_hiddenField(){
        if (isset($_POST[$this->amigoPerf_hfn]) && $_POST[$this->amigoPerf_hfn] === 'Y') {

            // Verify nonce for security
            if (
                !isset($_POST['amigo_basic_nonce']) ||
                !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['amigo_basic_nonce'])), 'amigo_basic_settings_action') ||
                !current_user_can('manage_options')
            ) {
                wp_die('Security verification failed.');
            }

            $this->amigoPerf_rqs_val = (isset($_POST[$this->amigoPerf_rqs]) ? true : false);
            if (is_bool($this->amigoPerf_rqs_val)) {
                update_option( $this->amigoPerf_rqs, $this->amigoPerf_rqs_val );
            }

            $this->amigoPerf_remoji_val = (isset($_POST[$this->amigoPerf_remoji]) ? true : false);
            if (is_bool($this->amigoPerf_remoji_val)) {
                update_option( $this->amigoPerf_remoji, $this->amigoPerf_remoji_val );
            }
            
            $this->amigoPerf_defer_val = (isset($_POST[$this->amigoPerf_defer]) ? true : false);
            if (is_bool($this->amigoPerf_defer_val)) {
                update_option( $this->amigoPerf_defer, $this->amigoPerf_defer_val );
            }
            
            $this->amigoPerf_iframelazy_val = (isset($_POST[$this->amigoPerf_iframelazy]) ? true : false);
            if (is_bool($this->amigoPerf_iframelazy_val)) {
                update_option( $this->amigoPerf_iframelazy, $this->amigoPerf_iframelazy_val );
            }
            
            $this->amigoPerf_lazyload_val = (isset($_POST[$this->amigoPerf_lazyload]) ? true : false);
            if (is_bool($this->amigoPerf_lazyload_val)) {
                update_option( $this->amigoPerf_lazyload, $this->amigoPerf_lazyload_val );
            }

            flush_rewrite_rules();
        }
    }

    public function amigoPerf_rqs_query($src)
    {
        if(strpos( $src, '?ver=' ))
        $src = remove_query_arg( 'ver', $src );
        return $src;
    }

    public function amigoPerf_rqs_operation()
    {
        if($this->amigoPerf_rqs_opt == get_option($this->amigoPerf_rqs)) {
            if(!is_admin()) {
                add_filter( 'style_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
                add_filter( 'script_loader_src', array($this,'amigoPerf_rqs_query'), 10, 2 );
            }
        }
    }

    public function amigoPerf_remoji_operation()
    {
        if($this->amigoPerf_remoji_opt == get_option($this->amigoPerf_remoji)) {
            remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
            remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
            remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
            remove_action( 'admin_print_styles', 'print_emoji_styles' );
        }
    }

    public function amigoPerf_defer_operation(){
        if($this->amigoPerf_defer_opt == get_option($this->amigoPerf_defer)) {
            if(!is_admin()) {
                add_filter( 'script_loader_tag', function ( $tag, $handle ) {
                    if(is_front_page()) {
                        if ( 'jquery-core' == $handle){ return $tag; } 
                    } else {
                    return $tag;
                    }
                    return str_replace( ' src', ' defer="defer" src', $tag );
                }, 10, 2 );
            }
        }
    }

    public function amigoPerf_iframelazy_operation()
    {
        //Inline Footer
        function inline_footer(){ 
            // if (is_front_page()) {
                $lazyscript = '
                <script>
                    /* 
                    URL: https://github.com/AmigoDheena/amigolazy
                    Author: Amigo Dheena
                    */
                    let amframe = document.querySelectorAll(".amigolazy");
                    window.onload = function(){
                        for(let i=0; i<amframe.length;i++){
                            let amsrc = amframe[i];
                            let amdata = amsrc.getAttribute("data-src");
                            let datanew = amsrc.getAttribute("lazy");
                            if(datanew === null){
                            datanew = 1500;
                            }
                            setTimeout(function(){
                            amframe[i].setAttribute("src",amdata);
                            console.info(datanew + "ms Lazyloaded " + amframe[i].src);
                            }, datanew);
                        }
                    }
                </script>';
                echo wp_kses_post($lazyscript);
            // }
        }
        //Inline Footer
        add_action('wp_footer', 'inline_footer', 100);
    }

    public function amigoPerf_lazyload_operation() {
        function amigoPerf_lazyload_add_script() {
            // Enqueue LOCAL vanilla-lazyload script
            wp_enqueue_script(
                'vanilla-lazyload',
                plugin_dir_url(__FILE__) . 'assets/js/vanilla-lazyload.min.js',
                array(),
                '19.1.3', // Version number (optional)
                true // Load in footer
            );

            // Enqueue custom lazy-function.js (depends on vanilla-lazyload)
            wp_enqueue_script(
                'amigo-lazy-function',
                plugin_dir_url(__FILE__) . 'assets/js/lazy-function.js',
                array('vanilla-lazyload'), // Dependency
                '2.5', // Updated version for 2.5 release
                true // Load in footer
            );
        }
        add_action('wp_enqueue_scripts', 'amigoPerf_lazyload_add_script');
    }

    public function link_rel_buffer_callback($buffer) {
        $buffer = preg_replace('/iframe(.src)/m', 'iframe class="amigolazy" lazy="3000" data-src', $buffer);
        return $buffer;
    }

    public function link_rel_buffer_start() {
        ob_start(array($this,'link_rel_buffer_callback'));
    }

    public function link_rel_buffer_end() {
        ob_flush();
    }

    public function amigoPerf_iframelazy_execute(){
        if($this->amigoPerf_iframelazy_opt == get_option($this->amigoPerf_iframelazy)) {

            // Inline amigolazy script at footer
            $this->amigoPerf_iframelazy_operation();

            //str replace
            if (!is_admin()) {
                add_action('template_redirect', array($this,'link_rel_buffer_start'));
                add_action('get_header', array($this,'link_rel_buffer_start'));
                add_action('wp_footer', array($this,'link_rel_buffer_end'));
            }
        }
    }

    // New
    public function amigoPerf_lazyload_execute(){
        if($this->amigoPerf_lazyload_opt == get_option($this->amigoPerf_lazyload)) {
            // Inline amigolazy script at footer
            $this->amigoPerf_lazyload_operation();

            // Add lazy loading attribute to images if enabled
            add_filter('the_content', array($this, 'amigoPerf_lazyload_load_images')); // Add this line
        }
    }

    // Add lazy loading attribute to images if enabled
    // public function amigoPerf_lazyload_load_images($content) {
    //     // Check if Amigo Lazy is enabled
    //         // Add "lazy" class and replace "src" with "data-src"
    //         $content = preg_replace_callback(
    //             '/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i',
    //             function($matches) {
    //                 $img_tag = $matches[0];
    //                 $img_attributes = $matches[1];
    //                 $img_src = $matches[2];
    //                 $img_remaining = $matches[3];
                    
    //                 // Add "lazy" class and replace "src" with "data-src"
    //                 $img_tag = '<img' . $img_attributes . 'class="lazy" data-src="' . $img_src . '"' . $img_remaining . '>';
                    
    //                 return $img_tag;
    //             },
    //             $content
    //         );

    //     return $content;
    // }

    public function amigoPerf_lazyload_load_images($content) {
        // Skip if not in the frontend or if content is empty
        if (is_admin() || empty($content)) {
            return $content;
        }

        // Use DOMDocument to parse and modify images safely
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');
        
        foreach ($images as $img) {
            // Skip if already lazy-loaded
            if ($img->hasAttribute('data-src')) {
                continue;
            }

            // Get the original src
            $src = $img->getAttribute('src');
            if (empty($src)) {
                continue;
            }

            // Add lazy-loading attributes
            $img->setAttribute('data-src', $src);
            $img->removeAttribute('src');
            
            // Add "lazy" class
            $class = $img->getAttribute('class');
            $img->setAttribute('class', trim($class . ' lazy'));
        }

        return $dom->saveHTML();
    }

    // New
    
   // Register Menu Page
    public function amigoperformance_add_pages() {
        add_menu_page(
            'Amigo Performance Plugin', //Page title
            'Performance', //Menu title
            'manage_options', //capability
            'amigo_performance', //menu_slug
            array($this, 'amigoPerf_newpage'), //function
            'dashicons-buddicons-activity' //icon url
        );
    }

    public function amigoPerf_newpage(){
        require_once plugin_dir_path(__FILE__).'admin.php';
    }

    function amigoPerf_reg_menu(){
        add_action('admin_menu', array($this, 'amigoperformance_add_pages'));
    }

    // List of Enqueued files
    public function amigoPerf_enqueue_list_scripts() {
        if (is_front_page()) {
            global $wp_scripts;
            global $enqueued_scripts;
            $enqueued_scripts = array();
            foreach( $wp_scripts->queue as $handle ) {
                $enqueued_scripts[] = array_filter(array('handle' => $wp_scripts->registered[$handle]->handle , 'src'=> $wp_scripts->registered[$handle]->src));
            }
        }else{
            return null;
        }
    }
    
    public function amigoPerf_enqueue_list_styles() {
        if (is_front_page()) {
            global $wp_styles;
            global $enqueued_styles;
            $enqueued_styles = array();
            foreach( $wp_styles->queue as $handle ) {
                $enqueued_styles[] = array_filter(array('handle'=>$wp_styles->registered[$handle]->handle , 'src'=> $wp_styles->registered[$handle]->src));
            }
        }else{
            return null;
        }
    }

    public function amigoPerf_enqueued(){
        global $enqueued_scripts;
        global $enqueued_styles;
        update_option('amigoPerf_nq_style', $enqueued_styles);
        update_option('amigoPerf_nq_script', $enqueued_scripts);
    }
    public function amigoPerf_enqueued_list(){
        add_action( 'wp_print_scripts',  array($this, 'amigoPerf_enqueue_list_scripts') );
        add_action( 'wp_print_styles',  array($this, 'amigoPerf_enqueue_list_styles') );
        add_action( 'wp_head', array($this,'amigoPerf_enqueued') );
    }

    // public function save_enqueued_css() {
    //     // Check if form was submitted
    //     if (isset($_POST['enqueued_css_submit'])) {
    //         // Verify nonce (security check)
    //         if (!isset($_POST['amigo_css_nonce']) || !wp_verify_nonce($_POST['amigo_css_nonce'], 'amigo_save_css_action')) {
    //             wp_die('Security check failed!');
    //         }

    //         // Sanitize and save CSS handle
    //         if (isset($_POST['css_hadle'])) {
    //             $this->css_handle = sanitize_text_field($_POST['css_hadle']); // Use sanitize_text_field instead of sanitize_textarea_field for single-line input
    //             update_option('amigoPerf_save_nq_style', $this->css_handle);
    //         } else {
    //             $this->css_handle = '';
    //             error_log('The "css_hadle" index is not set in $_POST array.');
    //         }
    //     }
    // }

    // public function save_enqueued_css() {
    //     if (isset($_POST['enqueued_css_submit'])) {
    //         // Security checks
    //         if (
    //             !isset($_POST['amigo_css_nonce']) ||
    //             !wp_verify_nonce($_POST['amigo_css_nonce'], 'amigo_save_css_action') ||
    //             !current_user_can('manage_options') // Only allow admins
    //         ) {
    //             wp_die('Security check failed!');
    //         }

    //         // Process data
    //         $this->css_handle = isset($_POST['css_handle']) ? sanitize_text_field($_POST['css_handle']) : '';
    //         update_option('amigoPerf_save_nq_style', $this->css_handle);
    //     }
    // }

    public function save_enqueued_css() {
        // Only process during admin requests
        if (!is_admin() || !isset($_POST['enqueued_css_submit'])) {
            return;
        }
        
        // Verify nonce and capabilities first
        if (
            !isset($_POST['amigo_css_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['amigo_css_nonce'])), 'amigo_save_css_action') ||
            !current_user_can('manage_options')
        ) {
            wp_die('Security check failed!');
        }

        // Process and sanitize CSS handle
        $css_handle = isset($_POST['css_handle']) ? sanitize_text_field(wp_unslash($_POST['css_handle'])) : '';
        update_option('amigoPerf_save_nq_style', $css_handle);
        
        // Optional: Admin notice on success
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>CSS handles saved successfully!</p></div>';
        });
    }

    // public function save_enqueued_js(){
    //     // Check if the 'js_hadle' index exists in $_POST
    //     if (isset($_POST['js_hadle'])) {
    //         $this->js_handle = sanitize_textarea_field($_POST['js_hadle']);
    //         if (isset($_POST['enqueued_js_submit'])) {
    //             update_option('amigoPerf_save_nq_script', $this->js_handle);
    //         }
    //     } else {
    //         // Handle the case where 'js_hadle' index is not set
    //         // For example, you can set a default value or display an error message
    //         // Here, I'll set a default value to $this->js_handle
    //         $this->js_handle = '';
    //         // You can also log an error or display a warning message
    //         error_log('The "js_hadle" index is not set in $_POST array.');
    //         // Or display a warning message
    //         // echo 'Warning: The "js_hadle" index is not set in $_POST array.';
    //     }
    // }

    public function save_enqueued_js() {
        // Only process during admin requests
        if (!is_admin() || !isset($_POST['enqueued_js_submit'])) {
            return;
        }
        
        // Security verification
        if (
            !isset($_POST['amigo_js_nonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_POST['amigo_js_nonce'])), 'amigo_save_js_action') ||
            !current_user_can('manage_options')
        ) {
            wp_die('Security verification failed');
        }

        // Process and sanitize input
        $js_handle = isset($_POST['js_handle']) ? 
            sanitize_textarea_field(wp_unslash($_POST['js_handle'])) : 
            '';

        update_option('amigoPerf_save_nq_script', $js_handle);

        // Add admin success notice
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible">'
                . '<p>JavaScript handles saved successfully!</p>'
                . '</div>';
        });
    }

    public function amigoPerf_dq_js(){
        $this->amigoPerf_get_nq_js = get_option('amigoPerf_save_nq_script');
        $this->amigoPerf_dq_js_str_to_arr = explode (",", $this->amigoPerf_get_nq_js);

        for ($i=0; $i <count($this->amigoPerf_dq_js_str_to_arr) ; $i++) { 
            if (is_front_page()) {
                wp_dequeue_script($this->amigoPerf_dq_js_str_to_arr);
                wp_deregister_script($this->amigoPerf_dq_js_str_to_arr);
            }
        }      
    }

    public function amigoPerf_dq_css(){
        $this->amigoPerf_get_nq_css = get_option('amigoPerf_save_nq_style');
        $this->amigoPerf_dq_css_str_to_arr = explode (",", $this->amigoPerf_get_nq_css);

        for ($i=0; $i <count($this->amigoPerf_dq_css_str_to_arr) ; $i++) { 
            if (is_front_page()) {
                wp_deregister_style($this->amigoPerf_dq_css_str_to_arr);
            }
        }
    }

    public function amigoPerf_dequeue(){
        add_action( 'wp_print_scripts', array($this, 'amigoPerf_dq_js'), 100);
        add_action( 'wp_print_styles', array($this, 'amigoPerf_dq_css'), 100);
    }
    
    public function amigoPerf_nq_js(){        
        // Retrieve the option 'amigoPerf_nq_script'
        $this->amigoPerf_nqjs_array = get_option('amigoPerf_nq_script');
        
        // Declare and initialize the property if it's not declared
        if (!property_exists($this, 'amigoPerf_nqjs_array')) {
            $this->amigoPerf_nqjs_array = array();
        }
    
        // Update the option 'amigoPerf_dq_script'
        update_option('amigoPerf_dq_script', array($this->amigoPerf_dq_script));            
    }

    public function amigoPerf_nq_css(){
        $this->amigoPerf_nqcss_array = get_option('amigoPerf_nq_style');
        update_option('amigoPerf_dq_style',array($this->amigoPerf_dq_style));            
    }
    // List of Enqueued files

    // Working in Progress
    function amigoPerf_admin_bar ( WP_Admin_Bar $admin_bar ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $admin_bar->add_menu( array(
            'id'    => 'menu-id',
            'parent' => null,
            'group'  => null,
            'title' => '<span class="ab-icon dashicons dashicons-buddicons-activity"></span>AmigoPerf',
            'href'  => admin_url('admin.php?page=amigo_performance'),
            'meta' => [
                'title' => 'Amigo Performance', //This title will show on hover
            ]
        ) );
    }

    public function amigoPerf_adminmenu(){
        add_action( 'admin_bar_menu', array($this,'amigoPerf_admin_bar'), 500 );
    }
    // Working in Progress
    
}

if (class_exists('AmigoPerformancePlugin')) {
    
    $amigoperformanceplugin = new AmigoPerformancePlugin();
    $amigoperformanceplugin -> amigoperformance_register();
    $amigoPerfDefault = new AmigoPerformancePlugin();
    $amigoPerfDefault -> amigoPerf_Default();
    
    // Hook form processing to admin_init for proper timing
    add_action('admin_init', array($amigoPerfDefault, 'amigoperf_hiddenField'));
    add_action('admin_init', array($amigoPerfDefault, 'save_enqueued_css'));
    add_action('admin_init', array($amigoPerfDefault, 'save_enqueued_js'));
    
    $amigoPerfDefault -> amigoPerf_rqs_query('details');
    $amigoPerfDefault -> amigoPerf_rqs_operation(); //Remove Query Strings Operation
    $amigoPerfDefault -> amigoPerf_remoji_operation(); //Remove Emoji Operation
    $amigoPerfDefault -> amigoPerf_defer_operation(); //Defer parsing of JavaScript
    $amigoPerfDefault -> amigoPerf_iframelazy_execute(); //Iframe Lazyload  
    $amigoPerfDefault -> amigoPerf_lazyload_execute(); //Image Lazyload  
    $amigoPerfDefault -> amigoPerf_reg_menu(); //Register Menu
    $amigoPerfDefault -> amigoPerf_update_checker(); //Update checker
    $amigoPerfDefault -> amigoPerf_enqueued_list(); //List of Enqueued files
    
    $amigoPerfDefault -> amigoPerf_nq_js(); //Enqueue JS
    $amigoPerfDefault -> amigoPerf_nq_css(); //Enqueue CSS
    $amigoPerfDefault -> amigoPerf_dequeue(); //DQ js and CSS - in Front page
    // $amigoPerfDefault -> amigoPerf_adminmenu(); //Admin Bar menu WIP
}

// Activation
register_activation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_activate'));

// Deactivation
register_deactivation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_deactivate'));

// Uninstall
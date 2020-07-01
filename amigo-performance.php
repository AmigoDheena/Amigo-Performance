<?php
/**
 * Plugin Name:       Amigo Performance
 * Description:       This is very useful to optimize your website
 * Version:           0.1
 * Author:            Amigo Dheena
 * Author URI:        https://www.amigodheena.xyz
 */

if (!defined('ABSPATH')) {
    die;
}

class AmigoPerformancePlugin{

    function __construct()
    {
        add_action( 'admin_menu', array($this , 'amigoperformance_register_menu') );
    }

    function amigoperformance_activate()
    {
        # code...
    }

    function amigoperformance_deactivate()
    {
        # code...
    }

    function amigoperformance_uninstall()
    {
        # code...
    }
    function amigoperformance_register_menu() {
        add_menu_page(
            __( 'Amigo Performance', 'textdomain' ),
            'AmigoPerf',
            'manage_options',
            plugins_url('amigoPerformance-admin.php', __FILE__),
            '',
            'dashicons-buddicons-activity',
            // plugins_url( 'amigo-performance/assets/imag/icon3.png' ),
            6
        );
    }

    // Enqueue Style sheets
    function amigoperformance_enqueue_style(){
        wp_enqueue_style('amigoperf_style', plugins_url('assets/css/style.css',__FILE__));
    }
    
    // Enqueue Scripts
    function amigoperformance_enqueue_script(){
        wp_enqueue_script('amigoperf_script', plugins_url('assets/js/script.js',__FILE__));
    }

    // Register Style sheets and Scripts
    function amigoperformance_register(){
        add_action('admin_enqueue_scripts', array($this , 'amigoperformance_enqueue_style'));
        add_action('admin_enqueue_scripts', array($this , 'amigoperformance_enqueue_script'));
    }

}

if (class_exists('AmigoPerformancePlugin')) {
    $amigoperformanceplugin = new AmigoPerformancePlugin();
    $amigoperformanceplugin -> amigoperformance_register();
}

// Activation
register_activation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_activate'));

// Deactivation
register_deactivation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_deactivate'));

// Uninstall

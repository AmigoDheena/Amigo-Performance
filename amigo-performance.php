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

include_once 'amigo-performance-admin.php';

class AmigoPerformancePlugin{

    function __construct()
    {
        // add_action('admin_menu', array($this, 'amigoPerf_menu'));
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

    // Enqueue Style sheets and Scripts
    function amigoperformance_enqueue_style(){
        wp_enqueue_style('amigoperf_style', plugins_url('assets/css/style.css',__FILE__));
        wp_enqueue_script('amigoperf_script', plugins_url('assets/js/script.js',__FILE__));
    }

    // Register Style sheets and Scripts
    function amigoperformance_register(){
        add_action('admin_enqueue_scripts', array($this , 'amigoperformance_enqueue_style'));
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
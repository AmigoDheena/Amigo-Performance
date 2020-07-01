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
            plugins_url('amigo-performance/amigoPerformance-admin.php'),
            '',
            'dashicons-buddicons-activity',
            // plugins_url( 'amigo-performance/images/icon3.png' ),
            6
        );
    }

}

if (class_exists('AmigoPerformancePlugin')) {
    $amigoperformanceplugin = new AmigoPerformancePlugin();
}

// Activation
register_activation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_activate'));

// Deactivation
register_deactivation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_deactivate'));

// Uninstall

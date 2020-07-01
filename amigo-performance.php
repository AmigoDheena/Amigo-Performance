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
        add_action('admin_menu', array($this, 'amigoperformance_add_pages'));
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

    // Register Menu Page
    function amigoperformance_add_pages() {        
        add_menu_page(
            __('Amigo Perf Page','amigoperf-menupage'), //Page title
            __('Amigo Perf','amigoperf-menu'), //Menu title
            'manage_options', //capability
            'amigo-perf-handle', //menu_slug
            array($this, 'amigoperformance_toplevel_page'), //function
            'dashicons-buddicons-activity' //icon url
        );
    }

    // Displays the page content for the custom Toplevel menu
    function amigoperformance_toplevel_page() {
        echo "<h2>" . __( 'Amigo Performance', 'amigoperf-menu' ) . "</h2>";
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
    // $amigoperformanceplugin -> amigoperformance_toplevel_page();
}

// Activation
register_activation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_activate'));

// Deactivation
register_deactivation_hook(__FILE__,array($amigoperformanceplugin,'amigoperformance_deactivate'));

// Uninstall
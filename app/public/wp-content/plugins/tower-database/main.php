<?php
/**
 * Plugin Name: Tower Database Plugin
 * Description: A plugin to manage database information in HCACR.  
 * Version: 4.0
 * Author: Alejandra Rivas
 * Managed: Lucas Williams 
 */


 if (!defined('ABSPATH')) {
    exit;
}



require_once plugin_dir_path(__FILE__) . 'includes-admin/class-google-sheet.php';
require_once plugin_dir_path(__FILE__) . 'includes-admin/class-db-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes-admin/class-admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes-admin/rest-api.php';
require_once plugin_dir_path(__FILE__) . 'generated-list/display.php';
require_once plugin_dir_path(__FILE__) . 'tower-info/details-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'officers/officers-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';


register_activation_hook(__FILE__, 
                                 ['DB_Tower_Handler', 'create_table']);
register_activation_hook(__FILE__, ['DB_Officer_Handler', 'create_table']);
register_activation_hook(__FILE__, ['DB_District_Officers_Handler', 'create_table']);



add_action('admin_menu', ['Admin_Page', 'init_menu']);




add_action('rest_api_init', 'tower_manager_register_rest_api');






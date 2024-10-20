<?php
/**
 * Plugin Name: Tower Database Plugin
 * Description: A plugin to manage tower database with tower_id, name, and bells in the WordPress dashboard.
 * Version: 3.0
 * Author: Alejandra Rivas
 */


 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Load dependencies
require_once plugin_dir_path(__FILE__) . 'includes/class-google-sheet.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-db-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-api.php';

// Activation hook for creating tables
register_activation_hook(__FILE__, ['DB_Handler', 'create_table']);
add_action('admin_menu', ['Admin_Page', 'init_menu']);
add_action('rest_api_init', 'tower_manager_register_rest_api');
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// include_once(plugin_dir_path(__FILE__) . 'admin-tower-manager.php');


include_once(plugin_dir_path(__FILE__) . 'public-tower-display.php');

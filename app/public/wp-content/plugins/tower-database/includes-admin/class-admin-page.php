<?php
//Admin page maker to visualise sheet data 
class Admin_Page {

    public static function init_menu() {
        // Main menu page
        add_menu_page(
            'HCACR',       
            'HCACR',       
            'manage_options',      
            'tower-manager',       
            [__CLASS__, 'render_page'],  
            'dashicons-admin-site',
            20                     
        );

        add_submenu_page(
            'tower-manager',        // The slug name for the parent menu
            'Tower Database',       // Page title
            'Tower Database',            // Menu title
            'manage_options',       // Capability
            'tower-manager',        // Menu slug, same as main page slug
            [__CLASS__, 'render_page']  // Callback function
        );

        // Submenu: Second option, secondary empty page
        add_submenu_page(
            'tower-manager',        // The slug name for the parent menu
            'Officers Information',       // Page title
            'Officers Information',       // Menu title
            'manage_options',       // Capability
            'secondary-page',       // Unique slug for secondary page
            [__CLASS__, 'render_secondary_page']  // Callback function
        );
    }

    public static function render_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'towers';

        DB_Tower_Handler::insert_data();

        $towers = $wpdb->get_results("SELECT * FROM $table_name");
        
        include plugin_dir_path(__FILE__) . 'template-towers.php';
    }

    public static function render_secondary_page() {
        global $wpdb;
        DB_Officer_Handler::insert_data();
        $officers = DB_Officer_Handler::get_all_officers();

        DB_District_Officers_Handler::insert_data();
        $district_officers = DB_District_Officers_Handler::get_all_district_officers();

        include plugin_dir_path(__FILE__) . 'template-officers.php';

    }
}
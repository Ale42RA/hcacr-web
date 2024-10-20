<?php

class Admin_Page {

    public static function init_menu() {
        add_menu_page(
            'Tower Manager',       
            'Tower Manager',       
            'manage_options',      
            'tower-manager',       
            [__CLASS__, 'render_page'],  
            'dashicons-admin-site',
            20                     
        );
    }

    public static function render_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'towers';

        // Optionally, fetch data from Google Sheets and insert into DB
        DB_Handler::insert_data();

        // Retrieve towers data
        $towers = $wpdb->get_results("SELECT * FROM $table_name");
        
        include plugin_dir_path(__FILE__) . 'template.php';
    }
}
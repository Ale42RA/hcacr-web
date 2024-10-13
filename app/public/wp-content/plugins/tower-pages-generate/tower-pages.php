<?php
/*
Plugin Name: Tower Pages Generator
Description: Automatically generates individual pages for each tower based on district initials and tower name.
Version: 1.0
Author: Your Name
*/

// Add rewrite rules on plugin activation
function tower_pages_rewrite_rules() {
    add_rewrite_rule('^([a-z]{3})-([a-z0-9-]+)/?', 'index.php?tower_district=$matches[1]&tower_name=$matches[2]', 'top');
}
add_action('init', 'tower_pages_rewrite_rules');

// Flush rewrite rules on activation/deactivation
function tower_pages_activation() {
    tower_pages_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'tower_pages_activation');

function tower_pages_deactivation() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'tower_pages_deactivation');

// Add query vars so WordPress recognizes them
function tower_pages_query_vars($query_vars) {
    $query_vars[] = 'tower_district';
    $query_vars[] = 'tower_name';
    return $query_vars;
}
add_filter('query_vars', 'tower_pages_query_vars');

// Handle the template for the tower pages
function tower_pages_template_include($template) {
    if (get_query_var('tower_district') && get_query_var('tower_name')) {
        return plugin_dir_path(__FILE__) . 'tower-page-template.php';
    }
    return $template;
}
add_filter('template_include', 'tower_pages_template_include');

// Fetch the tower details from the database
function get_tower_data($district, $name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'towers'; // Assuming the table is called wp_towers
    $district_like = $wpdb->esc_like($district) . '%';
    $name_like = '%' . str_replace('-', ' ', $wpdb->esc_like($name)) . '%';

    // Fetch the tower details
    $tower = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE District LIKE %s AND Dedication LIKE %s LIMIT 1",
            $district_like,
            $name_like
        )
    );

    return $tower;
}
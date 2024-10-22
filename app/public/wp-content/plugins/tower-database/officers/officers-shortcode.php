<?php

add_shortcode('display_patrons', 'hcacr_display_patrons');
function hcacr_display_patrons() {
    return hcacr_display_officers_by_role('Patrons');
}

add_shortcode('display_president', 'hcacr_display_president');
function hcacr_display_president() {
    return hcacr_display_officers_by_role('President');
}

add_shortcode('display_district_secretary', 'hcacr_display_district_secretary');
function hcacr_display_district_secretary() {
    return hcacr_display_officers_by_role('District Secretary');
}

add_shortcode('display_representatives', 'hcacr_display_representatives');
function hcacr_display_representatives() {
    return hcacr_display_officers_by_role('Representatives to the Central Council');
}

// Shortcode to display all other roles (excluding special roles)
add_shortcode('display_other_roles', 'hcacr_display_other_roles');
function hcacr_display_other_roles() {
    // Define special roles to exclude
    $excluded_roles = [
        'Patrons',
        'President',
        'District Secretary',
        'Representatives to the Central Council'
    ];

    ob_start();

    global $wpdb;
    $officer_handler = new DB_Officer_Handler();
    $officers = $officer_handler->get_officers_excluding_roles($excluded_roles);

    if (!empty($officers)) {
        include plugin_dir_path(__FILE__) . 'officers-template.php';
    } else {
        echo '<p>No other officer data available.</p>';
    }

    return ob_get_clean();
}

function hcacr_display_officers_by_role($role) {
    ob_start();

    global $wpdb;
    $officer_handler = new DB_Officer_Handler();
    $officers = $officer_handler->get_officers_by_role($role);

    if (!empty($officers)) {
        include plugin_dir_path(__FILE__) . 'officers-template.php';
    } else {
        echo '<p>No officers found for the role: ' . esc_html($role) . '.</p>';
    }

    return ob_get_clean();
}
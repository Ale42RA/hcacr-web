<?php
/*
Plugin Name: Tower Pages Generator
Description: Automatically generates individual pages for each tower based on district initials and tower name.
Version: 1.1
Author: Alejandra Rivas
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

// Function to create a WordPress page for each tower
function create_tower_pages() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'towers'; // Assuming the table is called wp_towers

    // Fetch all towers
    $towers = $wpdb->get_results("SELECT District, Dedication FROM $table_name");

    foreach ($towers as $tower) {
        // Generate the page title and slug from tower data
        $slug = strtolower(substr($tower->District, 0, 3)) . '-' . strtolower(str_replace(' ', '-', $tower->Dedication));
        $title = ucfirst($slug); // Capitalize the first letter

        // Check if the page already exists
        $existing_page = get_page_by_path($slug);

        // If the page does not exist, create it
        if (!$existing_page) {
            $new_page = array(
                'post_title'    => $title,
                'post_name'     => $slug,
                'post_content'  => '[tower_page_content]', // You can customize or replace this with actual content
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => 1, // Assuming the admin user with ID 1
                'page_template' => 'tower-page-template.php' // Assign the custom template
            );

            // Insert the post into the database
            wp_insert_post($new_page);
        }
    }
}
add_action('init', 'create_tower_pages');

// Shortcode to display tower content
function tower_page_content_shortcode() {
    $district = get_query_var('tower_district');
    $name = get_query_var('tower_name');

    if ($district && $name) {
        $tower = get_tower_data($district, $name);
        if ($tower) {
            ob_start();
            ?>
            <h1><?php echo esc_html($tower->Dedication); ?></h1>
            <p>District: <?php echo esc_html($tower->District); ?></p>
            <p>Height: <?php echo esc_html($tower->Height); ?> meters</p>
            <p>Year: <?php echo esc_html($tower->Year); ?></p>
            <?php
            return ob_get_clean();
        } else {
            return '<p>No tower found.</p>';
        }
    }
    return '<p>Invalid tower data.</p>';
}
add_shortcode('tower_page_content', 'tower_page_content_shortcode');
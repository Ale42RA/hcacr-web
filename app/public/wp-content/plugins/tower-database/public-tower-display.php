<?php
function tower_manager_display_towers() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'towers';

    // Fetch all towers from the database
    $towers = $wpdb->get_results("SELECT * FROM $table_name");

    // If no towers found, return a message
    if (empty($towers)) {
        return "<p>No towers found.</p>";
    }

    // Start output buffering to capture HTML output
    ob_start();

    // Include the template file to display towers
    include plugin_dir_path(__FILE__) . 'templates/public-tower-display.php';

    // Get the contents from the output buffer and return it
    return ob_get_clean();
}

// Hook function to display towers to the appropriate shortcode
add_shortcode('tower_manager_display_towers', 'tower_manager_display_towers');
add_shortcode('display_towers', 'tower_manager_display_towers');

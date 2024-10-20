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
    include plugin_dir_path(__FILE__) . 'template_listed.php';

    // Get the contents from the output buffer and return it
    return ob_get_clean();
}

// Hook function to display towers to the appropriate shortcode
add_shortcode('tower_manager_display_towers', 'tower_manager_display_towers');
add_shortcode('display_towers', 'tower_manager_display_towers');



function display_plugin_shortcode($atts) {
    // Extract the attribute from the shortcode like {{mpg_something}}
    $atts = shortcode_atts(
        array(
            'mpg_something' => ''  // Default to empty if not provided
        ),
        $atts
    );

    // Get the value of mpg_something (e.g., from an option or meta field)
    $mpg_value = get_option($atts['mpg_something'], false); // Replace with the actual retrieval method

    // Check if the value is true or false
    if ($mpg_value) {
        return "display";
    } else {
        return "not display";
    }
}

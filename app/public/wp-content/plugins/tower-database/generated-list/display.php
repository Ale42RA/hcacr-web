<?php
function tower_manager_display_towers() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'towers';

    $towers = $wpdb->get_results("SELECT * FROM $table_name");

    if (empty($towers)) {
        return "<p>No towers found.</p>";
    }

    // Start output buffering to capture HTML output
    ob_start();

    include plugin_dir_path(__FILE__) . 'template_listed.php';

    return ob_get_clean();
}

add_shortcode('tower_manager_display_towers', 'tower_manager_display_towers'); //Check if dead. Pretty sure unused
add_shortcode('display_towers', 'tower_manager_display_towers');



function display_plugin_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'mpg_something' => ''  
        ),
        $atts
    );

    // Get the value of mpg_something (e.g., from an option or meta field)
    $mpg_value = get_option($atts['mpg_something'], false); 

    if ($mpg_value) {
        return "display";
    } else {
        return "not display";
    }
}

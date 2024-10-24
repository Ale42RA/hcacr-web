<?php

function get_district_from_url() {
    $current_url = home_url( add_query_arg( null, null ) );
    $parsed_url = parse_url($current_url);
    $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';

    if (!empty($path)) {
        $path_parts = explode('/', $path);

        if (isset($path_parts[0])) {
            $district = $path_parts[0];

            if (strpos($district, '-') !== false) {
                $district_parts = explode('-', $district);
                array_pop($district_parts);
                $district = implode('-', $district_parts);
            }
            $district = sanitize_text_field(str_replace('-', ' ', $district));
           
            $district = str_replace(array("'", "(", ")"), '', $district);

            return $district;
        }
    }

    return null;
}


function tower_manager_display_towers($atts = []) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'towers';

    $atts = shortcode_atts(
        array(
            'district_based' => 'false', // default value
        ), 
        $atts
    );
    
    $district_based = filter_var($atts['district_based'], FILTER_VALIDATE_BOOLEAN);
 

    if ($district_based) {
        $district = get_district_from_url();
        // wp_die(esc_html($district));
        $query = $wpdb->prepare("
            SELECT * FROM $table_name 
            WHERE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(District, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '.', ''), '  ', ' ')) = LOWER(%s)
        ", $district);
    } else {
        $query = "SELECT * FROM $table_name";
    }

    $towers = $wpdb->get_results($query);

    if (empty($towers)) {
        return "<p>No towers found.</p>";
    }

    ob_start();

    include plugin_dir_path(__FILE__) . 'template_listed.php';

    return ob_get_clean();
}

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

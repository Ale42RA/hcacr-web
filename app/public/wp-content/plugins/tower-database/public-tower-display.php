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









function get_tower_info_from_url() {
    global $wpdb, $wp;

    // Use a static variable to cache the result
    static $tower = null;

    // If we've already retrieved the tower info, return the cached result
    if ($tower !== null) {
        return $tower;
    }

    // Define the table name with the correct prefix
    $table_name = $wpdb->prefix . 'towers';
    
    // Initialize the global $wp variable if it's not already initialized
    if (empty($wp)) {
        $wp = new WP();
    }

    // Get the current URL
    $current_url = home_url(add_query_arg(array(), $wp->request));

    // Parse the URL to extract the district, town, and dedication
    $url_parts = explode('/', trim(parse_url($current_url, PHP_URL_PATH), '/'));

    // Check if the URL structure is valid (at least three parts)
    if (count($url_parts) < 3) {
        return null;
    }

    // Extract district, town, and dedication from URL
    $district = sanitize_text_field(str_replace('-', ' ', $url_parts[count($url_parts) - 2]));
    $town_and_dedication = $url_parts[count($url_parts) - 1];
    $town_dedication_parts = explode('-', $town_and_dedication);

    if (count($town_dedication_parts) < 2) {
        return null;
    }

    $town = sanitize_text_field(str_replace('-', ' ', $town_dedication_parts[0]));
    $dedication = sanitize_text_field(str_replace('-', ' ', implode(' ', array_slice($town_dedication_parts, 1))));

    // Helper function to normalize strings
    function normalize_string($string) {
        return str_replace(array("'", "&"), '', $string);
    }

    $normalized_district = normalize_string($district);
    $normalized_town = normalize_string($town);
    $normalized_dedication = normalize_string($dedication);

    // Query the database for the matching tower
    $tower = $wpdb->get_row($wpdb->prepare("
        SELECT * 
        FROM $table_name 
        WHERE REPLACE(REPLACE(District, '&', ''), '''', '') = %s 
          AND REPLACE(REPLACE(Town, '&', ''), '''', '') = %s
          AND REPLACE(REPLACE(Dedication, '&', ''), '''', '') = %s
    ", $normalized_district, $normalized_town, $normalized_dedication));

    return $tower;
}


add_shortcode('display_secretary_info', 'display_secretary_info_shortcode');


function display_secretary_info_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    $output = '';

    if ($tower->Display_secretary) {
        $output .= esc_html($tower->Secretary) . '<br>';
        
        if ($tower->Display_secretary_telephone && !empty($tower->Secretary_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Secretary_telephone) . '<br>';
        }
        
        if ($tower->Display_secretary_mobile && !empty($tower->Secretary_mobile)) {
            $output .= 'Mobile: ' . esc_html($tower->Secretary_mobile) . '<br>';
        }
        
        if ($tower->Display_secretary_email && !empty($tower->Secretary_email)) {
            $output .= 'Email: ' . esc_html($tower->Secretary_email) . '<br>';
        }
        
        if ($tower->Display_secretary_address) {
            $address_parts = array_filter([
                $tower->Secretary_address_line_1,
                $tower->Secretary_address_line_2,
                $tower->Secretary_address_line_3,
                $tower->Secretary_address_line_4,
            ]);
            if (!empty($address_parts)) {
                $output .= 'Address: ' . esc_html(implode(', ', $address_parts)) . '<br>';
            }
        }
    } else {
        return 'Secretary information is not available.';
    }

    return $output;
}



add_shortcode('display_deputy_info', 'display_deputy_info_shortcode');

function display_deputy_info_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    $output = '';

    if (!empty($tower->Deputy_secretary)) {
        $output .= esc_html($tower->Deputy_secretary) . '<br>';
        
        if (!empty($tower->Deputy_secretary_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Deputy_secretary_telephone) . '<br>';
        }
        
        if (!empty($tower->Deputy_secretary_email)) {
            $output .= 'Email: ' . esc_html($tower->Deputy_secretary_email) . '<br>';
        }
    } else {
        return 'Deputy Secretary information is not available.';
    }

    return $output;
}

add_shortcode('display_master_info', 'display_master_info_shortcode');

function display_master_info_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    $output = '';

    if ($tower->Display_ringing_master) {
        $output .=esc_html($tower->Ringing_master) . '<br>';
        
        if (!empty($tower->Ringing_master_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Ringing_master_telephone) . '<br>';
        }
    } else {
        return 'Ringing Master information is not available.';
    }

    return $output;
}

add_shortcode('display_captain_info', 'display_captain_info_shortcode');


function display_captain_info_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    $output = '';

    if ($tower->Display_tower_captain) {
        $output .= esc_html($tower->Tower_captain) . '<br>';
    } else {
        return 'Tower Captain information is not available.';
    }

    return $output;
}
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
    include plugin_dir_path(__FILE__) . 'generated_list/template_listed.php';

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

    try {
        // Define the table name with the correct prefix
        $table_name = $wpdb->prefix . 'towers';
        
        // Initialize the global $wp variable if it's not already initialized
        if (empty($wp)) {
            $wp = new WP();
        }

        // Get the current URL
        $current_url = home_url(add_query_arg(array(), $wp->request));

        // Parse the URL and strip off the `/tower/` part
        $url_path = trim(parse_url($current_url, PHP_URL_PATH), '/');
        
        // Remove the 'tower/' prefix, so we only get the part after '/tower/'
        $url_path = preg_replace('/^tower\//', '', $url_path);
        
        // Now split the remaining part of the URL by '~'
        $url_parts = explode('~', $url_path);

        // Check if the URL structure is valid (should have three parts: district, town, and dedication)
        if (count($url_parts) < 3) {
            return null;
        }

        // Extract district, town, and dedication from URL
        $district = sanitize_text_field(str_replace('-', ' ', $url_parts[0]));
        $town = sanitize_text_field(str_replace('-', ' ', $url_parts[1]));
        $dedication = sanitize_text_field(str_replace('-', ' ',  $url_parts[2]));
    
        // Helper function to normalize strings (removing problematic characters like ' and &)
        function normalize_string($string) {
            // $string = str_replace('  ', ' ', $string);
            return str_replace(array("'", "(", ")"), '',$string);
        }
    
        // Normalize district, town, and dedication strings for comparison
        $normalized_district = normalize_string($district);
        $normalized_town = normalize_string($town);
        $normalized_dedication = normalize_string($dedication);

        // Ensure we have valid sanitized inputs
        if (empty($normalized_district) || empty($normalized_town) || empty($normalized_dedication)) {
            throw new Exception("One or more values (district, town, dedication) are missing after sanitization");
        }

    

        

       

        // Query the database for the matching tower
        $tower = $wpdb->get_row($wpdb->prepare("
            SELECT * 
            FROM $table_name 
            WHERE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(District, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '  ', ' ')) = LOWER(%s) 
            AND LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Town, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '  ', ' ')) = LOWER(%s)
            AND LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Dedication, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '  ', ' ')) = LOWER(%s)
        ", $normalized_district, $normalized_town, $normalized_dedication));

       

        // Check if the database query returned a result
        if ($tower === null) {
            throw new Exception("No matching tower found in the database");
        }

    } catch (Exception $e) {
        // Handle the exception and return null or log the error
        error_log("Error in get_tower_info_from_url: " . $e->getMessage());
        return null; // Return null to indicate failure
    }

    return $tower;
}

add_shortcode('display_secretary_info', 'display_secretary_info_shortcode');


function display_secretary_info_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    $output = '';

    if (!empty($tower->Secretary)) {
        // Display Secretary
        if ($tower->Display_secretary) {
            $output .= esc_html($tower->Secretary) . '<br>';
        }

        // Check and display Secretary Address if both address and display field are available
        if ($tower->Display_secretary_address && (!empty($tower->Secretary_address_Address_line_1) || !empty($tower->Secretary_address_Address_line_2) || !empty($tower->Secretary_address_Address_line_3) || !empty($tower->Secretary_address_Address_line_4))) {
            $address = array_filter([
                esc_html($tower->Secretary_address_Address_line_1),
                esc_html($tower->Secretary_address_Address_line_2),
                esc_html($tower->Secretary_address_Address_line_3),
                esc_html($tower->Secretary_address_Address_line_4),
            ]);

            if (count($address) > 0 && strlen($address[0]) > 3) {
                $output .= 'Address: ' . implode(', ', $address) . '<br>';
            }
        }

        // Check and display Secretary Telephone if both telephone and display field are available
        if ($tower->Display_secretary_telephone && !empty($tower->Secretary_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Secretary_telephone) . '<br>';
        }

        // Check and display Secretary Mobile if both mobile and display field are available
        if ($tower->Display_secretary_mobile && !empty($tower->Secretary_mobile)) {
            $output .= 'Mobile: ' . esc_html($tower->Secretary_mobile) . '<br>';
        }

        // Check and display Secretary Email if both email and display fields are available
        if ($tower->Expose_secretary_e_mail && $tower->Display_secretary_e_mail && !empty($tower->Secretary_e_mail_0)) {
            $output .= 'Email: ' . esc_html($tower->Secretary_e_mail_0) . '<br>';
        }

        // Check and display Secretary Comments if available
        if (!empty($tower->Secretary_comments)) {
            $output .= 'Comments: ' . esc_html($tower->Secretary_comments) . '<br>';
        }
        if (strlen($output) < 3) {
            return 'Secretary information is not available.';
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
        if ($tower->Display_deputy_secretary) {
            $output .= esc_html($tower->Deputy_secretary) . '<br>';
        }

        if ($tower->Display_deputy_secretary_address && !empty($tower->Deputy_secretary_address)) {
            $output .= 'Address: ' . esc_html($tower->Deputy_secretary_address) . '<br>';
        }

        if ($tower->Display_deputy_secretary_telephone && !empty($tower->Deputy_secretary_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Deputy_secretary_telephone) . '<br>';
        }

        if ($tower->Display_deputy_secretary_mobile && !empty($tower->Deputy_secretary_mobile)) {
            $output .= 'Mobile: ' . esc_html($tower->Deputy_secretary_mobile) . '<br>';
        }

        if (($tower->Expose_deputy_secretary_e_mail) && ($tower->Display_deputy_secretary_e_mail) && !empty($tower->Deputy_secretary_e_mail)) {
            $output .= 'Email: ' . esc_html($tower->Deputy_secretary_e_mail) . '<br>';
        }

        if (!empty($tower->Deputy_secretary_comments)) {
            $output .= 'Comments: ' . esc_html($tower->Deputy_secretary_comments) . '<br>';
        }

        if (strlen($output) < 3) {
            return 'Deputy Secretary  information is not available.';
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

    if (!empty($tower->Ringing_master)) {
        if ($tower->Display_ringing_master) {
            $output .= esc_html($tower->Ringing_master) . '<br>';
        }

        if ($tower->Display_ringing_master_telephone && !empty($tower->Ringing_master_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Ringing_master_telephone) . '<br>';
        }

        if (($tower->Expose_ringing_master_e_mail) && ($tower->Display_ringing_master_e_mail) && !empty($tower->Ringing_master_e_mail)) {
            $output .= 'Email: ' . esc_html($tower->Ringing_master_e_mail) . '<br>';
        }

        if (strlen($output) < 3) {
            return 'Ringing Master information is not available.';
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

    if (!empty($tower->Tower_captain)) {
        if ($tower->Display_tower_captain) {
            $output .= esc_html($tower->Tower_captain) . '<br>';
        }

        if ($tower->Display_tower_captain_telephone && !empty($tower->Tower_captain_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Tower_captain_telephone) . '<br>';
        }

        if (($tower->Expose_tower_captain_e_mail) && ($tower->Display_tower_captain_e_mail) && !empty($tower->Tower_captain_e_mail)) {
            $output .= 'Email: ' . esc_html($tower->Tower_captain_e_mail) . '<br>';
        }

        if (strlen($output) < 3) {
            return 'Tower Captain information is not available.';
        }

    } else {
        return 'Tower Captain information is not available.';
    }

    return $output;
}


function display_ringing_location_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    if (!empty($tower->Ground_floor) && $tower->Ground_floor == true) {
        return 'Ground floor ringing';
    } else {
        return 'Tower ringing';
    }
}

add_shortcode('ringing_location', 'display_ringing_location_shortcode');


function display_toilets_availability_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    // Check the Toilets field and return the corresponding message
    if (!empty($tower->Toilets) && $tower->Toilets == true) {
        return 'Toilets available';
    } else {
        return 'Toilets not available';
    }
}

add_shortcode('toilets_availability', 'display_toilets_availability_shortcode');



function display_bells_data_shortcode() {
    // Retrieve tower information from URL (or any other method)
    $tower = get_tower_info_from_url();
    
    // Check if the tower data is valid
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    $max_bells = $tower->Number_of_bells; // Limit to 10 bells
    $output = '<table border="1" cellpadding="5" cellspacing="0">';
    $output .= '<thead><tr><th>Number</th><th>Date</th><th>Weight</th><th>Note</th></tr></thead>';
    $output .= '<tbody>';
    
    // Assuming $tower is an object, access its properties using object notation
    for ($i = 0; $i < $max_bells; $i++) {
        // Construct the field names dynamically using object syntax
        $bell_number = isset($tower->{"Bells_Bell_{$i}_Number"}) ? sanitize_text_field($tower->{"Bells_Bell_{$i}_Number"}) : '';
        $bell_date = isset($tower->{"Bells_Bell_{$i}_Date"}) ? sanitize_text_field($tower->{"Bells_Bell_{$i}_Date"}) : '';
        $bell_weight = isset($tower->{"Bells_Bell_{$i}_Weight"}) ? sanitize_text_field($tower->{"Bells_Bell_{$i}_Weight"}) : '';
        $bell_note = isset($tower->{"Bells_Bell_{$i}_Note"}) ? sanitize_text_field($tower->{"Bells_Bell_{$i}_Note"}) : '';

        // Only display if the bell number exists
        if (!empty($bell_number)) {
            // Add a row for each bell
            $output .= '<tr>';
            $output .= '<td>' . esc_html($bell_number) . '</td>';
            $output .= '<td>' . esc_html($bell_date) . '</td>';
            $output .= '<td>' . esc_html($bell_weight) . '</td>';
            $output .= '<td>' . esc_html($bell_note) . '</td>';
            $output .= '</tr>';
        }
    }

    $output .= '</tbody></table>';
    
    // Return the final output
    return $output;
}
add_shortcode('tower_bell_data', 'display_bells_data_shortcode');
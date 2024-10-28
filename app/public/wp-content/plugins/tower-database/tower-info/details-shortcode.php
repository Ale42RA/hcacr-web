<?php


function get_district_town_dedication_from_url() {
    global $wp;

    if (empty($wp)) {
        $wp = new WP();
    }

    $current_url = home_url(add_query_arg(array(), $wp->request));
    $url_path = trim(parse_url($current_url, PHP_URL_PATH), '/');
    $url_path = preg_replace('/^tower\//', '', $url_path);
    $url_parts = explode('~', $url_path);

    if (count($url_parts) < 3) {
        return null;
    }

    $district = sanitize_text_field(str_replace('-', ' ', $url_parts[0]));
    $town = sanitize_text_field(str_replace('-', ' ', $url_parts[1]));
    $dedication = sanitize_text_field(str_replace('-', ' ', $url_parts[2]));

    function normalize_string($string) {
        return str_replace(array("'", "(", ")"), '', $string);
    }

    $normalized_district = normalize_string($district);
    $normalized_town = normalize_string($town);
    $normalized_dedication = normalize_string($dedication);

    return array(
        'district' => $normalized_district,
        'town' => $normalized_town,
        'dedication' => $normalized_dedication
    );
}

function get_tower_info_from_url() {
    global $wpdb;

    static $tower = null;

    if ($tower !== null) {
        return $tower;
    }

    try {
        $location_info = get_district_town_dedication_from_url();
        if ($location_info === null) {
            return null;
        }

        $normalized_district = $location_info['district'];
        $normalized_town = $location_info['town'];
        $normalized_dedication = $location_info['dedication'];

        if (empty($normalized_district) || empty($normalized_town) || empty($normalized_dedication)) {
            throw new Exception("One or more values (district, town, dedication) are missing after sanitization");
        }

        $table_name = $wpdb->prefix . 'towers';

        $tower = $wpdb->get_row($wpdb->prepare("
            SELECT * 
            FROM $table_name 
            WHERE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(District, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '  ', ' ')) = LOWER(%s) 
            AND LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Town, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '  ', ' ')) = LOWER(%s)
            AND LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Dedication, '-', ' '), '&', ''), '''', ''), '(', ''), ')', ''), '  ', ' ')) = LOWER(%s)
        ", $normalized_district, $normalized_town, $normalized_dedication));

        if ($tower === null) {
            throw new Exception("No matching tower found in the database");
        }

    } catch (Exception $e) {
        error_log("Error in get_tower_info_from_url: " . $e->getMessage());
        return null;
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
        $output .= esc_html($tower->Secretary) . '<br>';

        if ( (!empty($tower->Secretary_address_Address_line_1) || !empty($tower->Secretary_address_Address_line_2) || !empty($tower->Secretary_address_Address_line_3) || !empty($tower->Secretary_address_Address_line_4))) {
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

        if (!empty($tower->Secretary_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Secretary_telephone) . '<br>';
        }

        if (!empty($tower->Secretary_mobile)) {
            $output .= 'Mobile: ' . esc_html($tower->Secretary_mobile) . '<br>';
        }

        if (!empty($tower->Secretary_e_mail_0)) {
            $output .= 'Email: ' . esc_html($tower->Secretary_e_mail_0) . '<br>';
        }

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
        $output .= esc_html($tower->Deputy_secretary) . '<br>';


        if (!empty($tower->Deputy_secretary_address)) {
            $output .= 'Address: ' . esc_html($tower->Deputy_secretary_address) . '<br>';
        }

        if (!empty($tower->Deputy_secretary_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Deputy_secretary_telephone) . '<br>';
        }

        if (!empty($tower->Deputy_secretary_mobile)) {
            $output .= 'Mobile: ' . esc_html($tower->Deputy_secretary_mobile) . '<br>';
        }

        if (!empty($tower->Deputy_secretary_e_mail)) {
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
      
        $output .= esc_html($tower->Ringing_master) . '<br>';
        

        if (!empty($tower->Ringing_master_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Ringing_master_telephone) . '<br>';
        }

        if (!empty($tower->Ringing_master_e_mail)) {
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
        
        $output .= esc_html($tower->Tower_captain) . '<br>';
        

        if (!empty($tower->Tower_captain_telephone)) {
            $output .= 'Telephone: ' . esc_html($tower->Tower_captain_telephone) . '<br>';
        }

        if (!empty($tower->Tower_captain_e_mail)) {
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


add_shortcode('ringing_location', 'display_ringing_location_shortcode');
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



add_shortcode('toilets_availability', 'display_toilets_availability_shortcode');

function display_toilets_availability_shortcode() {
    $tower = get_tower_info_from_url();
    if (!$tower) {
        return 'Invalid URL or tower not found.';
    }

    if (!empty($tower->Toilets) && $tower->Toilets == true) {
        return 'Toilets available';
    } else {
        return 'Toilets not available';
    }
}



add_shortcode('tower_bell_data', 'display_bells_data_shortcode');
function display_bells_data_shortcode($atts = [], $tower = null) {
    $atts = shortcode_atts([
        'summary' => false 
    ], $atts);

    if ($atts['summary'] === 'true') {
        if (!$tower) {
            return 'Tower data not provided.';
        }
    } 

    $max_bells = $tower->Number_of_bells;

    if ($atts['summary'] === 'true') {
        $last_bell_index = $max_bells - 1;

        $last_bell_weight = isset($tower->{"Bells_Bell_{$last_bell_index}_Weight"}) ? sanitize_text_field($tower->{"Bells_Bell_{$last_bell_index}_Weight"}) : 'N/A';
        $last_bell_note = isset($tower->{"Bells_Bell_{$last_bell_index}_Note"}) ? sanitize_text_field($tower->{"Bells_Bell_{$last_bell_index}_Note"}) : 'N/A';

        if ($last_bell_note !== 'N/A' && strlen($last_bell_note) > 1) {
            $last_char = substr($last_bell_note, -1); // Get the last character
            $base_note = substr($last_bell_note, 0, -1); // Get the rest of the note
        
             if ($last_char === 'b') {
                // If last character is 'b', replace it with the flat symbol ♭
                $last_bell_note = $base_note . '♭';
            }
        }
        return esc_html($max_bells) . " (" . esc_html($last_bell_weight) . "), " . esc_html($last_bell_note);
    }
}

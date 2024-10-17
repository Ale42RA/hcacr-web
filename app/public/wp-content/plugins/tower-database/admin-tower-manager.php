<?php

function tower_manager_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . "towers";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
    CREATE TABLE $table_name (
        tower_id mediumint(9) NOT NULL AUTO_INCREMENT,
        ID mediumint(9) NOT NULL,
        Town text NOT NULL,
        Dedication text,
        District text,
        Photograph text,
        Practice_night text,
        Practice_night_italics text,
        Service_ringing text,
        Service_ringing_italics text,
        Ringing_master text,
        Display_ringing_master text,
        Tower_captain text,
        Display_tower_captain text,
        Secretary text,
        Display_secretary text,
        Secretary_address_line_1 text,
        Secretary_address_line_2 text,
        Secretary_address_line_3 text,
        Secretary_address_line_4 text,
        Secretary_address_line_5 text,
        Display_secretary_address text,
        Secretary_telephone text,
        Display_secretary_telephone text,
        Secretary_mobile text,
        Display_secretary_mobile text,
        Display_secretary_email text,
        Expose_secretary_email boolean,
        Secretary_email text,
        Secretary_comments text,
        OS_reference text,
        What3WordsDoorLoc_0 text,
        What3WordsDoorLoc_1 text,
        Number_of_bells mediumint(9) NOT NULL,
        Comment_1 text,
        Comment_1_link text,
        Comment_1_italics text,
        Comment_2 text,
        Comment_2_link text,
        Comment_2_italics text,
        Bell_comment text,
        Bell_comment_link text,
        Bell_comment_italics text,
        Postcode text,
        Felstead_ID int,
        RW_Peal_ID int,
        Bellboard_place text,
        Bellboard_dedication text,
        Bellboard_county text,
        DoveID text,
        Map_X float,
        Map_Y float,
        Ground_floor boolean,
        Toilet boolean,
        Bells_Bell_0_Number text,
        Bells_Bell_0_Date text,
        Bells_Bell_0_Weight text,
        Bells_Bell_0_Note text,
        Bells_Bell_1_Number text,
        Bells_Bell_1_Date text,
        Bells_Bell_1_Weight text,
        Bells_Bell_1_Note text,
        Bells_Bell_2_Number text,
        Bells_Bell_2_Date text,
        Bells_Bell_2_Weight text,
        Bells_Bell_2_Note text,
        Bells_Bell_3_Number text,
        Bells_Bell_3_Date text,
        Bells_Bell_3_Weight text,
        Bells_Bell_3_Note text,
        Bells_Bell_4_Number text,
        Bells_Bell_4_Date text,
        Bells_Bell_4_Weight text,
        Bells_Bell_4_Note text,
        Bells_Bell_5_Number text,
        Bells_Bell_5_Date text,
        Bells_Bell_5_Weight text,
        Bells_Bell_5_Note text,
        Bells_Bell_6_Number text,
        Bells_Bell_6_Date text,
        Bells_Bell_6_Weight text,
        Bells_Bell_6_Note text,
        Bells_Bell_7_Number text,
        Bells_Bell_7_Date text,
        Bells_Bell_7_Weight text,
        Bells_Bell_7_Note text,
        Tower_captain_telephone text,
        Display_tower_captain_telephone text,
        Tower_captain_email text,
        Display_tower_captain_email text,
        Expose_tower_captain_email boolean,
        Bells_Bell_8_Number text,
        Bells_Bell_8_Date text,
        Bells_Bell_8_Weight text,
        Bells_Bell_8_Note text,
        Bells_Bell_9_Number text,
        Bells_Bell_9_Date text,
        Bells_Bell_9_Weight text,
        Bells_Bell_9_Note text,
        Bells_Bell_10_Number text,
        Bells_Bell_10_Date text,
        Bells_Bell_10_Weight text,
        Bells_Bell_10_Note text,
        Bells_Bell_11_Number text,
        Bells_Bell_11_Date text,
        Bells_Bell_11_Weight text,
        Bells_Bell_11_Note text,
        Bells_Bell_12_Number text,
        Bells_Bell_12_Date text,
        Bells_Bell_12_Weight text,
        Bells_Bell_12_Note text,
        Include_dedication boolean,
        Deputy_secretary text,
        Display_deputy_secretary text,
        Deputy_secretary_address text,
        Display_deputy_secretary_address text,
        Deputy_secretary_telephone text,
        Display_deputy_secretary_telephone text,
        Deputy_secretary_mobile text,
        Display_deputy_secretary_mobile text,
        Display_deputy_secretary_email text,
        Expose_deputy_secretary_email boolean,
        Deputy_secretary_email text,
        Deputy_secretary_comments text,
        Ringing_master_telephone text,
        Display_ringing_master_telephone text,
        Ringing_master_email text,
        Display_ringing_master_email text,
        Expose_ringing_master_email boolean,
        Practice_night_phone_to_confirm boolean,
        Practice_night_comments text,
        PRIMARY KEY (tower_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

register_activation_hook(__FILE__, 'tower_manager_create_table');

// Add a menu item to the WordPress dashboard
function tower_manager_menu() {
    add_menu_page(
        'Tower Manager',       
        'Tower Manager',       
        'manage_options',      
        'tower-manager',       
        'tower_manager_page',  
        'dashicons-admin-site',
        20                     
    );
}
add_action('admin_menu', 'tower_manager_menu');

// Function to process CSV upload and store data
function tower_manager_process_csv_upload($table_name) {
    global $wpdb;

    // Check if the file is uploaded
    if (isset($_FILES['tower_csv']) && $_FILES['tower_csv']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['tower_csv']['tmp_name'];

        // Validate that the file is a CSV
        $file_type = mime_content_type($file);
        if ($file_type !== 'text/csv' && $file_type !== 'application/csv') {
            wp_die('Error: The uploaded file is not a valid CSV file.');
        }

        // Clear existing towers before importing new data
        try {
            $wpdb->query("TRUNCATE TABLE $table_name");
        } catch (Exception $e) {
            wp_die('Error: Failed to clear existing data: ' . $e->getMessage());
        }

        if (($handle = fopen($file, 'r')) !== false) {
            fgetcsv($handle); // Skip header row

            // Begin transaction for better error handling during database inserts
            $wpdb->query('START TRANSACTION');
            $insert_success = true;

            while (($data = fgetcsv($handle, 100000, ',')) !== false) {
                try {
                    // Check if required data fields are available and valid
                    if (empty($data[0]) || !is_numeric($data[0])) {
                        throw new Exception('Invalid or missing ID value.');
                    }

                    $tower_data = array(
                      'ID' => intval($data[0]),
'Town' => sanitize_text_field($data[1]),
'Dedication' => sanitize_text_field($data[2]),
'District' => sanitize_text_field($data[3]),
'Photograph' => sanitize_text_field($data[4]),
'Practice_night' => sanitize_text_field($data[5]),
'Practice_night_italics' => sanitize_text_field($data[6]),
'Service_ringing' => sanitize_text_field($data[7]),
'Service_ringing_italics' => sanitize_text_field($data[8]),
'Ringing_master' => sanitize_text_field($data[9]),
'Display_ringing_master' => sanitize_text_field($data[10]),
'Tower_captain' => sanitize_text_field($data[11]),
'Display_tower_captain' => sanitize_text_field($data[12]),
'Secretary' => sanitize_text_field($data[13]),
'Display_secretary' => sanitize_text_field($data[14]),
'Secretary_address_Address_line_1' => $data[15],
'Secretary_address_Address_line_2' => $data[16],
'Secretary_address_Address_line_3' => $data[17],
'Secretary_address_Address_line_4' => $data[18],
'Display_secretary_address' => $data[19],
'Secretary_telephone' => $data[20],
'Display_secretary_telephone' => $data[21],
'Secretary_mobile' => $data[22],
'Display_secretary_mobile' => $data[23],
'Display_secretary_e_mail' => $data[24],
'Expose_secretary_e_mail' => $data[25],
'Secretary_e_mail_0' => $data[26],
'Secretary_comments' => $data[27],
'OS_reference' => $data[28],
'What3WordsDoorLoc_0' => $data[29],
'Number_of_bells' => intval($data[30]),
'Comment_1' => $data[31],
'Comment_1_link' => $data[32],
'Comment_1_italics' => $data[33],
'Comment_2' => $data[34],
'Comment_2_link' => $data[35],
'Comment_2_italics' => $data[36],
'Bell_comment' => $data[37],
'Bell_comment_link' => $data[38],
'Bell_comment_italics' => $data[39],
'Postcode' => $data[40],
'Felstead_ID' => $data[41],
'RW_Peal_ID' => $data[42],
'Bellboard_place' => $data[43],
'Bellboard_dedication' => $data[44],
'Bellboard_county' => $data[45],
'DoveID' => $data[46],
'Map_X' => $data[47],
'Map_Y' => $data[48],
'Ground_floor' => $data[49],
'Toilet' => $data[50],
'Bells_Bell_0_Number' => $data[51],
'Bells_Bell_0_Date' => $data[52],
'Bells_Bell_0_Weight' => $data[53],
'Bells_Bell_0_Note' => $data[54],
'Bells_Bell_1_Number' => $data[55],
'Bells_Bell_1_Date' => $data[56],
'Bells_Bell_1_Weight' => $data[57],
'Bells_Bell_1_Note' => $data[58],
'Bells_Bell_2_Number' => $data[59],
'Bells_Bell_2_Date' => $data[60],
'Bells_Bell_2_Weight' => $data[61],
'Bells_Bell_2_Note' => $data[62],
'Bells_Bell_3_Number' => $data[63],
'Bells_Bell_3_Date' => $data[64],
'Bells_Bell_3_Weight' => $data[65],
'Bells_Bell_3_Note' => $data[66],
'Bells_Bell_4_Number' => $data[67],
'Bells_Bell_4_Date' => $data[68],
'Bells_Bell_4_Weight' => $data[69],
'Bells_Bell_4_Note' => $data[70],
'Bells_Bell_5_Number' => $data[71],
'Bells_Bell_5_Date' => $data[72],
'Bells_Bell_5_Weight' => $data[73],
'Bells_Bell_5_Note' => $data[74],
'Bells_Bell_6_Number' => $data[75],
'Bells_Bell_6_Date' => $data[76],
'Bells_Bell_6_Weight' => $data[77],
'Bells_Bell_6_Note' => $data[78],
'Bells_Bell_7_Number' => $data[79],
'Bells_Bell_7_Date' => $data[80],
'Bells_Bell_7_Weight' => $data[81],
'Bells_Bell_7_Note' => $data[82],
'Bells_Bell_8_Number' => $data[83],
'Bells_Bell_8_Date' => $data[84],
'Bells_Bell_8_Weight' => $data[85],
'Bells_Bell_8_Note' => $data[86],
'Bells_Bell_9_Number' => $data[87],
'Bells_Bell_9_Date' => $data[88],
'Bells_Bell_9_Weight' => $data[89],
'Bells_Bell_9_Note' => $data[90],
'Bells_Bell_10_Number' => $data[91],
'Bells_Bell_10_Date' => $data[92],
'Bells_Bell_10_Weight' => $data[93],
'Bells_Bell_10_Note' => $data[94],
'Bells_Bell_11_Number' => $data[95],
'Bells_Bell_11_Date' => $data[96],
'Bells_Bell_11_Weight' => $data[97],
'Bells_Bell_11_Note' => $data[98],
'Bells_Bell_12_Number' => $data[99],
'Bells_Bell_12_Date' => $data[100],
'Bells_Bell_12_Weight' => $data[101],
'Bells_Bell_12_Note' => $data[102],
'Include_dedication' => $data[103],
'Deputy_secretary' => $data[104],
'Display_deputy_secretary' => $data[105],
'Deputy_secretary_address' => $data[106],
'Display_deputy_secretary_address' => $data[107],
'Deputy_secretary_telephone' => $data[108],
'Display_deputy_secretary_telephone' => $data[109],
'Deputy_secretary_mobile' => $data[110],
'Display_deputy_secretary_mobile' => $data[111],
'Display_deputy_secretary_e_mail' => $data[112],
'Expose_deputy_secretary_e_mail' => $data[113],
'Deputy_secretary_e_mail' => $data[114],
'Deputy_secretary_comments' => $data[115],
'Ringing_master_telephone' => $data[116],
'Display_ringing_master_telephone' => $data[117],
'Ringing_master_e_mail' => $data[118],
'Display_ringing_master_e_mail' => $data[119],
'Expose_ringing_master_e_mail' => $data[120],
'Practice_night_phone_to_confirm' => $data[121],
'Practice_night_comments' => $data[122],
'Tower_captain_telephone' => $data[123],
'Display_tower_captain_telephone' => $data[124],
'Tower_captain_e_mail' => $data[125],
'Display_tower_captain_e_mail' => $data[126],
'Expose_tower_captain_e_mail' => $data[127],
  
                );
                if (false === $wpdb->insert($table_name, $tower_data)) {
                    throw new Exception('Database insert failed: ' . $wpdb->last_error);
                }
            } catch (Exception $e) {
                $insert_success = false;
                error_log('Bells_Bell_0_Weight: ' . $data[56]);
                error_log('Failed to insert row: ' . json_encode($data) . ' Error: ' . $e->getMessage());  
                wp_die('Failed to insert row: ' . json_encode($data) . ' Error: ' . $e->getMessage());

              }
        }

        fclose($handle);

        // Commit or rollback transaction based on success
        if ($insert_success) {
            $wpdb->query('COMMIT');
        } else {
            $wpdb->query('ROLLBACK');
            wp_die('Error: One or more rows could not be inserted. Check the error log for details.');
        }
    } else {
        wp_die('Error: Unable to open the CSV file.');
    }
} 
}

// Fetch towers from the database
function tower_manager_get_towers($table_name) {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM $table_name");
}

// Admin page display logic, only the PHP logic here, HTML in separate file
function tower_manager_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'towers';
    tower_manager_process_csv_upload($table_name);

    // Fetch existing towers
    $towers = tower_manager_get_towers($table_name);

    // Load HTML template
    include plugin_dir_path(__FILE__) . 'templates/admin-tower-manager.php';
}

// Ensure database table exists on plugin upgrade
function tower_manager_update_db_check() {
    tower_manager_create_table();
}
add_action('plugins_loaded', 'tower_manager_update_db_check');

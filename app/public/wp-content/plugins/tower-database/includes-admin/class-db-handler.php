<?php

class DB_Handler {
    private static $table_name = 'towers';

    public static function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
        CREATE TABLE $table_name (
            tower_id mediumint(9) NOT NULL AUTO_INCREMENT,
        ID mediumint(9) NOT NULL,
        Town text NOT NULL,
        Dedication text DEFAULT NULL,
        District text DEFAULT NULL,
        Photograph text DEFAULT NULL,
        Practice_night text DEFAULT NULL,
        Practice_night_italics text DEFAULT NULL,
        Service_ringing text DEFAULT NULL,
        Service_ringing_italics text DEFAULT NULL,
        Ringing_master text DEFAULT NULL,
        Display_ringing_master varchar(255) DEFAULT NULL,
        Tower_captain text DEFAULT NULL,
        Display_tower_captain varchar(255) DEFAULT NULL,
        Secretary text DEFAULT NULL,
        Display_secretary varchar(255),,
        Secretary_address_line_1 text DEFAULT NULL,
        Secretary_address_line_2 text DEFAULT NULL,
        Secretary_address_line_3 text DEFAULT NULL,
        Secretary_address_line_4 text DEFAULT NULL,
        Display_secretary_address varchar(255) DEFAULT NULL,
        Secretary_telephone text DEFAULT NULL,
        Display_secretary_telephone varchar(255) DEFAULT NULL,
        Secretary_mobile text DEFAULT NULL,
        Display_secretary_mobile varchar(255) DEFAULT NULL,
        Display_secretary_email varchar(255) DEFAULT NULL,
        Expose_secretary_email varchar(255) DEFAULT NULL,
        Secretary_email text DEFAULT NULL,
        Secretary_comments text DEFAULT NULL,
        OS_reference text DEFAULT NULL,
        What3WordsDoorLoc_0 text DEFAULT NULL,
        What3WordsDoorLoc_1 text DEFAULT NULL,
        Number_of_bells mediumint(9) NOT NULL DEFAULT NULL,
        Comment_1 text DEFAULT NULL,
        Comment_1_link text DEFAULT NULL,
        Comment_1_italics text DEFAULT NULL,
        Comment_2 text DEFAULT NULL,
        Comment_2_link text DEFAULT NULL,
        Comment_2_italics text DEFAULT NULL,
        Bell_comment text DEFAULT NULL,
        Bell_comment_link text DEFAULT NULL,
        Bell_comment_italics text DEFAULT NULL,
        Postcode text DEFAULT NULL,
        Felstead_ID int DEFAULT NULL,
        RW_Peal_ID int DEFAULT NULL,
        Bellboard_place text DEFAULT NULL,
        Bellboard_dedication text DEFAULT NULL,
        Bellboard_county text DEFAULT NULL,
        DoveID text DEFAULT NULL,
        Map_X float DEFAULT NULL,
        Map_Y float DEFAULT NULL,
        Ground_floor varchar(255) DEFAULT NULL,
        Toilet varchar(255) DEFAULT NULL,
        Bells_Bell_0_Number text DEFAULT NULL,
        Bells_Bell_0_Date text DEFAULT NULL,
        Bells_Bell_0_Weight text DEFAULT NULL,
        Bells_Bell_0_Note text DEFAULT NULL,
        Bells_Bell_1_Number text DEFAULT NULL,
        Bells_Bell_1_Date text DEFAULT NULL,
        Bells_Bell_1_Weight text DEFAULT NULL,
        Bells_Bell_1_Note text DEFAULT NULL,
        Bells_Bell_2_Number text DEFAULT NULL,
        Bells_Bell_2_Date text DEFAULT NULL,
        Bells_Bell_2_Weight text DEFAULT NULL,
        Bells_Bell_2_Note text DEFAULT NULL,
        Bells_Bell_3_Number text DEFAULT NULL,
        Bells_Bell_3_Date text DEFAULT NULL,
        Bells_Bell_3_Weight text DEFAULT NULL,
        Bells_Bell_3_Note text DEFAULT NULL,
        Bells_Bell_4_Number text DEFAULT NULL,
        Bells_Bell_4_Date text DEFAULT NULL,
        Bells_Bell_4_Weight text DEFAULT NULL,
        Bells_Bell_4_Note text DEFAULT NULL,
        Bells_Bell_5_Number text DEFAULT NULL,
        Bells_Bell_5_Date text DEFAULT NULL,
        Bells_Bell_5_Weight text DEFAULT NULL,
        Bells_Bell_5_Note text DEFAULT NULL,
        Bells_Bell_6_Number text DEFAULT NULL,
        Bells_Bell_6_Date text DEFAULT NULL,
        Bells_Bell_6_Weight text DEFAULT NULL,
        Bells_Bell_6_Note text DEFAULT NULL,
        Bells_Bell_7_Number text DEFAULT NULL,
        Bells_Bell_7_Date text DEFAULT NULL,
        Bells_Bell_7_Weight text DEFAULT NULL,
        Bells_Bell_7_Note text DEFAULT NULL,
        Tower_captain_telephone text DEFAULT NULL,
        Display_tower_captain_telephone varchar(255) DEFAULT NULL,
        Tower_captain_email text DEFAULT NULL,
        Display_tower_captain_email varchar(255) DEFAULT NULL,
        Expose_tower_captain_email varchar(255) DEFAULT NULL,
        Bells_Bell_8_Number text DEFAULT NULL,
        Bells_Bell_8_Date text DEFAULT NULL,
        Bells_Bell_8_Weight text DEFAULT NULL,
        Bells_Bell_8_Note text DEFAULT NULL,
        Bells_Bell_9_Number text DEFAULT NULL,
        Bells_Bell_9_Date text DEFAULT NULL,
        Bells_Bell_9_Weight text DEFAULT NULL,
        Bells_Bell_9_Note text DEFAULT NULL,
        Bells_Bell_10_Number text DEFAULT NULL,
        Bells_Bell_10_Date text DEFAULT NULL,
        Bells_Bell_10_Weight text DEFAULT NULL,
        Bells_Bell_10_Note text DEFAULT NULL,
        Bells_Bell_11_Number text DEFAULT NULL,
        Bells_Bell_11_Date text DEFAULT NULL,
        Bells_Bell_11_Weight text DEFAULT NULL,
        Bells_Bell_11_Note text DEFAULT NULL,
        Bells_Bell_12_Number text DEFAULT NULL
        Bells_Bell_12_Date text DEFAULT NULL,
        Bells_Bell_12_Weight text DEFAULT NULL,
        Bells_Bell_12_Note text DEFAULT NULL,
        Include_dedication varchar(255) DEFAULT NULL,
        Deputy_secretary text DEFAULT NULL,
        Display_deputy_secretary varchar(255) DEFAULT NULL,
        Deputy_secretary_address text DEFAULT NULL,
        Display_deputy_secretary_address varchar(255) DEFAULT NULL,
        Deputy_secretary_telephone text DEFAULT NULL,
        Display_deputy_secretary_telephone varchar(255) DEFAULT NULL,
        Deputy_secretary_mobile text DEFAULT NULL,
        Display_deputy_secretary_mobile varchar(255) DEFAULT NULL,
        Display_deputy_secretary_email varchar(255) DEFAULT NULL,
        Expose_deputy_secretary_email varchar(255) DEFAULT NULL,
        Deputy_secretary_email text DEFAULT NULL,
        Deputy_secretary_comments text DEFAULT NULL,
        Ringing_master_telephone text DEFAULT NULL,
        Display_ringing_master_telephone varchar(255) DEFAULT NULL,
        Ringing_master_email text DEFAULT NULL,
        Display_ringing_master_email varchar(255) DEFAULT NULL,
        Expose_ringing_master_email varchar(255) DEFAULT NULL,
        Practice_night_phone_to_confirm varchar(255) DEFAULT NULL,
        Practice_night_comments text DEFAULT NULL,
        PRIMARY KEY (tower_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
   

    public static function insert_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;

        // Fetch data from Google Sheets
        $sheet_data = json_decode(Google_Sheet::get_data(), true);

        
        // Start transaction
        $wpdb->query('START TRANSACTION');
        function sanitize_and_validate_field($field_value, $field_type) {
            switch ($field_type) {
                case 'text':
                    return empty($field_value) ? '' : sanitize_text_field($field_value);
                case 'boolean':
                    return filter_var($field_value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === null ? false : filter_var($field_value, FILTER_VALIDATE_BOOLEAN);
                case 'int':
                    return intval($field_value);
                case 'email':
                    return empty($field_value) ? '' : sanitize_email($field_value);
                default:
                    return empty($field_value) ? '' : sanitize_text_field($field_value); // Fallback to text sanitization
            }
        }
        
        // Clear table before insert
        $wpdb->query("TRUNCATE TABLE $table_name");

        // Insert data (loop through each entry in $sheet_data)
        if (empty($sheet_data)) {
            wp_die('Error: No data found from Google Sheets.');
        }
    
        $insert_success = true;
        foreach ($sheet_data as $data) {
            try {
                // Map the $data to the $tower_data array, ensuring all fields are sanitized or converted as needed
                $tower_data = array(
                    'ID' => sanitize_and_validate_field($data['ID'], 'int'),
                    'Town' => sanitize_and_validate_field($data['Town'], 'text'),
                    'Dedication' => sanitize_and_validate_field($data['Dedication'], 'text'),
                    'District' => sanitize_and_validate_field($data['District'], 'text'),
                    'Photograph' => sanitize_and_validate_field($data['Photograph'], 'text'),
                    'Practice_night' => sanitize_and_validate_field($data['Practice_night'], 'text'),
                    'Practice_night_italics' => sanitize_and_validate_field($data['Practice_night_italics'], 'boolean'),
                    'Service_ringing' => sanitize_and_validate_field($data['Service_ringing'], 'text'),
                    'Service_ringing_italics' => sanitize_and_validate_field($data['Service_ringing_italics'], 'boolean'),
                    'Ringing_master' => sanitize_and_validate_field($data['Ringing_master'], 'text'),
                    'Display_ringing_master' => sanitize_and_validate_field($data['Display_ringing_master'], 'boolean'),
                    'Tower_captain' => sanitize_and_validate_field($data['Tower_captain'], 'text'),
                    'Display_tower_captain' => sanitize_and_validate_field($data['Display_tower_captain'], 'boolean'),
                    'Secretary' => sanitize_and_validate_field($data['Secretary'], 'text'),
                    'Display_secretary' => sanitize_and_validate_field($data['Display_secretary'], 'boolean'),
                    'Secretary_address_Address_line_1' => sanitize_and_validate_field($data['Secretary_address_Address_line_1'], 'text'),
                    'Secretary_address_Address_line_2' => sanitize_and_validate_field($data['Secretary_address_Address_line_2'], 'text'),
                    'Secretary_address_Address_line_3' => sanitize_and_validate_field($data['Secretary_address_Address_line_3'], 'text'),
                    'Secretary_address_Address_line_4' => sanitize_and_validate_field($data['Secretary_address_Address_line_4'], 'text'),
                    'Display_secretary_address' => sanitize_and_validate_field($data['Display_secretary_address'], 'boolean'),
                    'Secretary_telephone' => sanitize_and_validate_field($data['Secretary_telephone'], 'text'),
                    'Display_secretary_telephone' => sanitize_and_validate_field($data['Display_secretary_telephone'], 'boolean'),
                    'Secretary_mobile' => sanitize_and_validate_field($data['Secretary_mobile'], 'text'),
                    'Display_secretary_mobile' => sanitize_and_validate_field($data['Display_secretary_mobile'], 'boolean'),
                    'Display_secretary_e_mail' => sanitize_and_validate_field($data['Display_secretary_e_mail'], 'boolean'),
                    'Expose_secretary_e_mail' => sanitize_and_validate_field($data['Expose_secretary_e_mail'], 'boolean'),
                    'Secretary_e_mail_0' => sanitize_and_validate_field($data['Secretary_e_mail_0'], 'text'),
                    'Secretary_comments' => sanitize_and_validate_field($data['Secretary_comments'], 'text'),
                    'OS_reference' => sanitize_and_validate_field($data['OS_reference'], 'text'),
                    'What3WordsDoorLoc_0' => sanitize_and_validate_field($data['What3WordsDoorLoc_0'], 'text'),
                    'Number_of_bells' => sanitize_and_validate_field($data['Number_of_bells'], 'int'),
                    'Comment_1' => sanitize_and_validate_field($data['Comment_1'], 'text'),
                    'Comment_1_link' => sanitize_and_validate_field($data['Comment_1_link'], 'text'),
                    'Comment_1_italics' => sanitize_and_validate_field($data['Comment_1_italics'], 'boolean'),
                    'Comment_2' => sanitize_and_validate_field($data['Comment_2'], 'text'),
                    'Comment_2_link' => sanitize_and_validate_field($data['Comment_2_link'], 'text'),
                    'Comment_2_italics' => sanitize_and_validate_field($data['Comment_2_italics'], 'boolean'),
                    'Bell_comment' => sanitize_and_validate_field($data['Bell_comment'], 'text'),
                    'Bell_comment_link' => sanitize_and_validate_field($data['Bell_comment_link'], 'text'),
                    'Bell_comment_italics' => sanitize_and_validate_field($data['Bell_comment_italics'], 'boolean'),
                    'Postcode' => sanitize_and_validate_field($data['Postcode'], 'text'),
                    'Felstead_ID' => sanitize_and_validate_field($data['Felstead_ID'], 'text'),
                    'RW_Peal_ID' => sanitize_and_validate_field($data['RW_Peal_ID'], 'text'),
                    'Bellboard_place' => sanitize_and_validate_field($data['Bellboard_place'], 'text'),
                    'Bellboard_dedication' => sanitize_and_validate_field($data['Bellboard_dedication'], 'text'),
                    'Bellboard_county' => sanitize_and_validate_field($data['Bellboard_county'], 'text'),
                    'DoveID' => sanitize_and_validate_field($data['DoveID'], 'text'),
                    'Map_X' => sanitize_and_validate_field($data['Map_X'], 'text'),
                    'Map_Y' => sanitize_and_validate_field($data['Map_Y'], 'text'),
                    'Ground_floor' => sanitize_and_validate_field($data['Ground_floor'], 'boolean'),
                    'Toilet' => sanitize_and_validate_field($data['Toilet'], 'boolean'),
                    'Bells_Bell_0_Number' => sanitize_and_validate_field($data['Bells_Bell_0_Number'], 'text'),
                    'Bells_Bell_0_Date' => sanitize_and_validate_field($data['Bells_Bell_0_Date'], 'text'),
                    'Bells_Bell_0_Weight' => sanitize_and_validate_field($data['Bells_Bell_0_Weight'], 'text'),
                    'Bells_Bell_0_Note' => sanitize_and_validate_field($data['Bells_Bell_0_Note'], 'text'),
                    'Bells_Bell_1_Number' => sanitize_and_validate_field($data['Bells_Bell_1_Number'], 'text'),
                    'Bells_Bell_1_Date' => sanitize_and_validate_field($data['Bells_Bell_1_Date'], 'text'),
                    'Bells_Bell_1_Weight' => sanitize_and_validate_field($data['Bells_Bell_1_Weight'], 'text'),
                    'Bells_Bell_1_Note' => sanitize_and_validate_field($data['Bells_Bell_1_Note'], 'text'),
                    'Bells_Bell_2_Number' => sanitize_and_validate_field($data['Bells_Bell_2_Number'], 'text'),
                    'Bells_Bell_2_Date' => sanitize_and_validate_field($data['Bells_Bell_2_Date'], 'text'),
                    'Bells_Bell_2_Weight' => sanitize_and_validate_field($data['Bells_Bell_2_Weight'], 'text'),
                    'Bells_Bell_2_Note' => sanitize_and_validate_field($data['Bells_Bell_2_Note'], 'text'),
                    'Bells_Bell_3_Number' => sanitize_and_validate_field($data['Bells_Bell_3_Number'], 'text'),
                    'Bells_Bell_3_Date' => sanitize_and_validate_field($data['Bells_Bell_3_Date'], 'text'),
                    'Bells_Bell_3_Weight' => sanitize_and_validate_field($data['Bells_Bell_3_Weight'], 'text'),
                    'Bells_Bell_3_Note' => sanitize_and_validate_field($data['Bells_Bell_3_Note'], 'text'),
                    'Bells_Bell_4_Number' => sanitize_and_validate_field($data['Bells_Bell_4_Number'], 'text'),
                    'Bells_Bell_4_Date' => sanitize_and_validate_field($data['Bells_Bell_4_Date'], 'text'),
                    'Bells_Bell_4_Weight' => sanitize_and_validate_field($data['Bells_Bell_4_Weight'], 'text'),
                    'Bells_Bell_4_Note' => sanitize_and_validate_field($data['Bells_Bell_4_Note'], 'text'),
                    'Bells_Bell_5_Number' => sanitize_and_validate_field($data['Bells_Bell_5_Number'], 'text'),
                    'Bells_Bell_5_Date' => sanitize_and_validate_field($data['Bells_Bell_5_Date'], 'text'),
                    'Bells_Bell_5_Weight' => sanitize_and_validate_field($data['Bells_Bell_5_Weight'], 'text'),
                    'Bells_Bell_5_Note' => sanitize_and_validate_field($data['Bells_Bell_5_Note'], 'text'),
                    'Bells_Bell_6_Number' => sanitize_and_validate_field($data['Bells_Bell_6_Number'], 'text'),
                    'Bells_Bell_6_Date' => sanitize_and_validate_field($data['Bells_Bell_6_Date'], 'text'),
                    'Bells_Bell_6_Weight' => sanitize_and_validate_field($data['Bells_Bell_6_Weight'], 'text'),
                    'Bells_Bell_6_Note' => sanitize_and_validate_field($data['Bells_Bell_6_Note'], 'text'),
                    'Bells_Bell_7_Number' => sanitize_and_validate_field($data['Bells_Bell_7_Number'], 'text'),
                    'Bells_Bell_7_Date' => sanitize_and_validate_field($data['Bells_Bell_7_Date'], 'text'),
                    'Bells_Bell_7_Weight' => sanitize_and_validate_field($data['Bells_Bell_7_Weight'], 'text'),
                    'Bells_Bell_7_Note' => sanitize_and_validate_field($data['Bells_Bell_7_Note'], 'text'),
                    'Bells_Bell_8_Number' => sanitize_and_validate_field($data['Bells_Bell_8_Number'], 'text'),
                    'Bells_Bell_8_Date' => sanitize_and_validate_field($data['Bells_Bell_8_Date'], 'text'),
                    'Bells_Bell_8_Weight' => sanitize_and_validate_field($data['Bells_Bell_8_Weight'], 'text'),
                    'Bells_Bell_8_Note' => sanitize_and_validate_field($data['Bells_Bell_8_Note'], 'text'),
                    'Bells_Bell_9_Number' => sanitize_and_validate_field($data['Bells_Bell_9_Number'], 'text'),
                    'Bells_Bell_9_Date' => sanitize_and_validate_field($data['Bells_Bell_9_Date'], 'text'),
                    'Bells_Bell_9_Weight' => sanitize_and_validate_field($data['Bells_Bell_9_Weight'], 'text'),
                    'Bells_Bell_9_Note' => sanitize_and_validate_field($data['Bells_Bell_9_Note'], 'text'),
                    'Bells_Bell_10_Number' => sanitize_and_validate_field($data['Bells_Bell_10_Number'], 'text'),
                    'Bells_Bell_10_Date' => sanitize_and_validate_field($data['Bells_Bell_10_Date'], 'text'),
                    'Bells_Bell_10_Weight' => sanitize_and_validate_field($data['Bells_Bell_10_Weight'], 'text'),
                    'Bells_Bell_10_Note' => sanitize_and_validate_field($data['Bells_Bell_10_Note'], 'text'),
                    'Bells_Bell_11_Number' => sanitize_and_validate_field($data['Bells_Bell_11_Number'], 'text'),
                    'Bells_Bell_11_Date' => sanitize_and_validate_field($data['Bells_Bell_11_Date'], 'text'),
                    'Bells_Bell_11_Weight' => sanitize_and_validate_field($data['Bells_Bell_11_Weight'], 'text'),
                    'Bells_Bell_11_Note' => sanitize_and_validate_field($data['Bells_Bell_11_Note'], 'text'),
                    'Bells_Bell_12_Number' => sanitize_and_validate_field($data['Bells_Bell_12_Number'], 'text'),
                    'Bells_Bell_12_Date' => sanitize_and_validate_field($data['Bells_Bell_12_Date'], 'text'),
                    'Bells_Bell_12_Weight' => sanitize_and_validate_field($data['Bells_Bell_12_Weight'], 'text'),
                    'Bells_Bell_12_Note' => sanitize_and_validate_field($data['Bells_Bell_12_Note'], 'text'),
                    'Include_dedication' => sanitize_and_validate_field($data['Include_dedication'], 'text'),
                    'Deputy_secretary' => sanitize_and_validate_field($data['Deputy_secretary'], 'text'),
                    'Display_deputy_secretary' => sanitize_and_validate_field($data['Display_deputy_secretary'], 'boolean'),
                    'Deputy_secretary_address' => sanitize_and_validate_field($data['Deputy_secretary_address'], 'text'),
                    'Display_deputy_secretary_address' => sanitize_and_validate_field($data['Display_deputy_secretary_address'], 'boolean'),
                    'Deputy_secretary_telephone' => sanitize_and_validate_field($data['Deputy_secretary_telephone'], 'text'),
                    'Display_deputy_secretary_telephone' => sanitize_and_validate_field($data['Display_deputy_secretary_telephone'], 'boolean'),
                    'Deputy_secretary_mobile' => sanitize_and_validate_field($data['Deputy_secretary_mobile'], 'text'),
                    'Display_deputy_secretary_mobile' => sanitize_and_validate_field($data['Display_deputy_secretary_mobile'], 'boolean'),
                    'Display_deputy_secretary_e_mail' => sanitize_and_validate_field($data['Display_deputy_secretary_e_mail'], 'boolean'),
                    'Expose_deputy_secretary_e_mail' => sanitize_and_validate_field($data['Expose_deputy_secretary_e_mail'], 'boolean'),
                    'Deputy_secretary_e_mail' => sanitize_and_validate_field($data['Deputy_secretary_e_mail'], 'text'),
                    'Deputy_secretary_comments' => sanitize_and_validate_field($data['Deputy_secretary_comments'], 'text'),
                    'Ringing_master_telephone' => sanitize_and_validate_field($data['Ringing_master_telephone'], 'text'),
                    'Display_ringing_master_telephone' => sanitize_and_validate_field($data['Display_ringing_master_telephone'], 'boolean'),
                    'Ringing_master_e_mail' => sanitize_and_validate_field($data['Ringing_master_e_mail'], 'text'),
                    'Display_ringing_master_e_mail' => sanitize_and_validate_field($data['Display_ringing_master_e_mail'], 'boolean'),
                    'Expose_ringing_master_e_mail' => sanitize_and_validate_field($data['Expose_ringing_master_e_mail'], 'boolean'),
                    'Practice_night_phone_to_confirm' => sanitize_and_validate_field($data['Practice_night_phone_to_confirm'], 'text'),
                    'Practice_night_comments' => sanitize_and_validate_field($data['Practice_night_comments'], 'text'),
                    'Tower_captain_telephone' => sanitize_and_validate_field($data['Tower_captain_telephone'], 'text'),
                    'Display_tower_captain_telephone' => sanitize_and_validate_field($data['Display_tower_captain_telephone'], 'boolean'),
                    'Tower_captain_e_mail' => sanitize_and_validate_field($data['Tower_captain_e_mail'], 'text'),
                    'Display_tower_captain_e_mail' => sanitize_and_validate_field($data['Display_tower_captain_e_mail'], 'boolean'),
                    'Expose_tower_captain_e_mail' => sanitize_and_validate_field($data['Expose_tower_captain_e_mail'], 'boolean'),
                );
    
                // Insert the data into the database
                if (false === $wpdb->insert($table_name, $tower_data)) {
                    throw new Exception('Database insert failed: ' . $wpdb->last_error);
                }
            } catch (Exception $e) {
                $insert_success = false;
                error_log('Failed to insert row: ' . json_encode($data) . ' Error: ' . $e->getMessage());
                wp_die('Failed to insert row: ' . json_encode($data) . ' Error: ' . $e->getMessage());
            }
        }
    
        // Check for transaction success
        if ($insert_success) {
            $wpdb->query('COMMIT');
        } else {
            $wpdb->query('ROLLBACK');
            wp_die('Error: One or more rows could not be inserted. Check the error log for details.');
        }
    }
    
}
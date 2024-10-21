<?php
//Class handles db into php term Makes that global
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
        Service_ringing text DEFAULT NULL,
        Ringing_master text DEFAULT NULL,
        Tower_captain text DEFAULT NULL,
        Secretary text DEFAULT NULL,
        Secretary_address_line_1 text DEFAULT NULL,
        Secretary_address_line_2 text DEFAULT NULL,
        Secretary_address_line_3 text DEFAULT NULL,
        Secretary_address_line_4 text DEFAULT NULL,
        Secretary_telephone text DEFAULT NULL,
        Secretary_mobile text DEFAULT NULL,
        Secretary_email text DEFAULT NULL,
        Secretary_comments text DEFAULT NULL,
        OS_reference text DEFAULT NULL,
        What3WordsDoorLoc_0 text DEFAULT NULL,
        Number_of_bells mediumint(9) NOT NULL DEFAULT NULL,
        Comment_1 text DEFAULT NULL,
        Comment_1_link text DEFAULT NULL,
        Comment_2 text DEFAULT NULL,
        Comment_2_link text DEFAULT NULL,
        Bell_comment text DEFAULT NULL,
        Bell_comment_link text DEFAULT NULL,
        DoveID text DEFAULT NULL,
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
        Tower_captain_email text DEFAULT NULL,
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
        Deputy_secretary text DEFAULT NULL,
        Deputy_secretary_address text DEFAULT NULL,
        Deputy_secretary_telephone text DEFAULT NULL,
        Deputy_secretary_mobile text DEFAULT NULL,
        Deputy_secretary_email text DEFAULT NULL,
        Deputy_secretary_comments text DEFAULT NULL,
        Ringing_master_telephone text DEFAULT NULL,
        Ringing_master_email text DEFAULT NULL,
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
                    return empty($field_value) ? '' : sanitize_text_field($field_value); 
            }
        }
        
        // Clear table before insert
        $wpdb->query("TRUNCATE TABLE $table_name");

        if (empty($sheet_data)) {
            wp_die('Error: No data found from Google Sheets.');
        }
    
        $insert_success = true;
        foreach ($sheet_data as $data) {
            try {
                $tower_data = array(
                    'ID' => sanitize_and_validate_field($data['ID'], 'int'),
                    'Town' => sanitize_and_validate_field($data['Town'], 'text'),
                    'Dedication' => sanitize_and_validate_field($data['Dedication'], 'text'),
                    'District' => sanitize_and_validate_field($data['District'], 'text'),
                    'Photograph' => sanitize_and_validate_field($data['Photograph'], 'text'),
                    'Practice_night' => sanitize_and_validate_field($data['Practice_night'], 'text'),
                    'Service_ringing' => sanitize_and_validate_field($data['Service_ringing'], 'text'),
                    'Ringing_master' => sanitize_and_validate_field($data['Ringing_master'], 'text'),
                    'Tower_captain' => sanitize_and_validate_field($data['Tower_captain'], 'text'),
                    'Secretary' => sanitize_and_validate_field($data['Secretary'], 'text'),
                    'Secretary_address_Address_line_1' => sanitize_and_validate_field($data['Secretary_address_Address_line_1'], 'text'),
                    'Secretary_address_Address_line_2' => sanitize_and_validate_field($data['Secretary_address_Address_line_2'], 'text'),
                    'Secretary_address_Address_line_3' => sanitize_and_validate_field($data['Secretary_address_Address_line_3'], 'text'),
                    'Secretary_address_Address_line_4' => sanitize_and_validate_field($data['Secretary_address_Address_line_4'], 'text'),
                    'Secretary_telephone' => sanitize_and_validate_field($data['Secretary_telephone'], 'text'),
                    'Secretary_mobile' => sanitize_and_validate_field($data['Secretary_mobile'], 'text'),
                    'Secretary_e_mail_0' => sanitize_and_validate_field($data['Secretary_e_mail_0'], 'text'),
                    'Secretary_comments' => sanitize_and_validate_field($data['Secretary_comments'], 'text'),
                    'What3WordsDoorLoc_0' => sanitize_and_validate_field($data['What3WordsDoorLoc_0'], 'text'),
                    'Number_of_bells' => sanitize_and_validate_field($data['Number_of_bells'], 'int'),
                    'Comment_1' => sanitize_and_validate_field($data['Comment_1'], 'text'),
                    'Comment_1_link' => sanitize_and_validate_field($data['Comment_1_link'], 'text'),
                    'Comment_2' => sanitize_and_validate_field($data['Comment_2'], 'text'),
                    'Comment_2_link' => sanitize_and_validate_field($data['Comment_2_link'], 'text'),
                    'Bell_comment' => sanitize_and_validate_field($data['Bell_comment'], 'text'),
                    'Bell_comment_link' => sanitize_and_validate_field($data['Bell_comment_link'], 'text'),
                    'DoveID' => sanitize_and_validate_field($data['DoveID'], 'text'),
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
                    'Deputy_secretary' => sanitize_and_validate_field($data['Deputy_secretary'], 'text'),
                    'Deputy_secretary_address' => sanitize_and_validate_field($data['Deputy_secretary_address'], 'text'),
                    'Deputy_secretary_telephone' => sanitize_and_validate_field($data['Deputy_secretary_telephone'], 'text'),
                    'Deputy_secretary_mobile' => sanitize_and_validate_field($data['Deputy_secretary_mobile'], 'text'),
                    'Deputy_secretary_e_mail' => sanitize_and_validate_field($data['Deputy_secretary_e_mail'], 'text'),
                    'Deputy_secretary_comments' => sanitize_and_validate_field($data['Deputy_secretary_comments'], 'text'),
                    'Ringing_master_telephone' => sanitize_and_validate_field($data['Ringing_master_telephone'], 'text'),
                    'Ringing_master_e_mail' => sanitize_and_validate_field($data['Ringing_master_e_mail'], 'text'),
                    'Practice_night_phone_to_confirm' => sanitize_and_validate_field($data['Practice_night_phone_to_confirm'], 'text'),
                    'Practice_night_comments' => sanitize_and_validate_field($data['Practice_night_comments'], 'text'),
                    'Tower_captain_telephone' => sanitize_and_validate_field($data['Tower_captain_telephone'], 'text'),
                    'Tower_captain_e_mail' => sanitize_and_validate_field($data['Tower_captain_e_mail'], 'text'),
                );
    
                if (false === $wpdb->insert($table_name, $tower_data)) {
                    throw new Exception('Database insert failed: ' . $wpdb->last_error);
                }
            } catch (Exception $e) {
                $insert_success = false;
                error_log('Failed to insert row: ' . json_encode($data) . ' Error: ' . $e->getMessage());
                wp_die('Failed to insert row: ' . json_encode($data) . ' Error: ' . $e->getMessage());
            }
        }
    
        // Check for transaction success?? No idea where these logs go but if it works it works. 
        if ($insert_success) {
            $wpdb->query('COMMIT');
        } else {
            $wpdb->query('ROLLBACK');
            wp_die('Error: One or more rows could not be inserted. Check the error log for details.');
        }
    }
    
}

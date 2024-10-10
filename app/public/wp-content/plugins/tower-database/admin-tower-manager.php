<?php

function tower_manager_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . "towers";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
    CREATE TABLE $table_name (
        tower_id mediumint(9) NOT NULL AUTO_INCREMENT,
        ID mediumint(9) NOT NULL,
        Town tinytext NOT NULL,
        Dedication text,
        District text,
        Photograph varchar(255),
        Practice_night varchar(255),
        Practice_night_italics varchar(255),
        Service_ringing varchar(255),
        Service_ringing_italics varchar(255),
        Ringing_master text,
        Display_ringing_master varchar(255),
        Tower_captain text,
        Display_tower_captain varchar(255),
        Secretary text,
        Display_secretary varchar(255),
        Secretary_address_line_1 text,
        Secretary_address_line_2 text,
        Secretary_address_line_3 text,
        Secretary_address_line_4 text,
        Display_secretary_address text,
        Secretary_telephone varchar(255),
        Display_secretary_telephone varchar(255),
        Secretary_mobile varchar(255),
        Display_secretary_mobile varchar(255),
        Display_secretary_email varchar(255),
        Expose_secretary_email boolean,
        Secretary_email text,
        Secretary_comments text,
        OS_reference varchar(255),
        What3WordsDoorLoc varchar(255),
        Number_of_bells int,
        Comment_1 text,
        Comment_1_link varchar(255),
        Comment_1_italics varchar(255),
        Comment_2 text,
        Comment_2_link varchar(255),
        Comment_2_italics varchar(255),
        Bell_comment text,
        Bell_comment_link varchar(255),
        Bell_comment_italics varchar(255),
        Postcode varchar(20),
        Felstead_ID varchar(50),
        RW_Peal_ID varchar(50),
        Bellboard_place text,
        Bellboard_dedication text,
        Bellboard_county text,
        DoveID varchar(255),
        Map_X float,
        Map_Y float,
        Ground_floor boolean,
        Toilet boolean,
        Practice_night_phone_to_confirm boolean,
        Practice_night_comments text,
        Secretary_address_line_5 text,
        Include_dedication boolean,
        Deputy_secretary text,
        Deputy_secretary_telephone varchar(255),
        Deputy_secretary_email text,
        Deputy_secretary_comments text,
        Ringing_master_telephone varchar(255),
        PRIMARY KEY  (tower_id)
    ) $charset_collate;

        tower_id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        bells int NOT NULL,
        image_id varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (tower_id)
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

    if (isset($_FILES['tower_csv']) && $_FILES['tower_csv']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['tower_csv']['tmp_name'];

        // Clear existing towers before importing new data
        $wpdb->query("TRUNCATE TABLE $table_name");

        if (($handle = fopen($file, 'r')) !== false) {
            fgetcsv($handle); // Skip header row

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
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
                    'Secretary_address_line_1' => $data[15],
                    'Secretary_address_line_2' => $data[16],
                    'Secretary_address_line_3' => $data[17],
                    'Secretary_address_line_4' => $data[18],
                    'Display_secretary_address' => $data[19],
                    'Secretary_telephone' => $data[20],
                    'Display_secretary_telephone' => $data[21],
                    'Secretary_mobile' => $data[22],
                    'Display_secretary_mobile' => $data[23],
                    'Display_secretary_email' => $data[24],
                    'Expose_secretary_email' => $data[25],
                    'Secretary_email' => $data[26],
                    'Secretary_comments' => $data[27],
                    'OS_reference' => $data[28],
                    'What3WordsDoorLoc' => $data[29],
                    'Number_of_bells' => $data[30],
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
                    'Practice_night_phone_to_confirm' => $data[51],
                    'Practice_night_comments' => $data[52],
                    'Secretary_address_line_5' => $data[53],
                    'Include_dedication' => $data[54],
                    'Deputy_secretary' => $data[55],
                    'Deputy_secretary_telephone' => $data[56],
                    'Deputy_secretary_email' => $data[57],
                    'Deputy_secretary_comments' => $data[58],
                    'Ringing_master_telephone' => $data[59]
                    
                );
                $wpdb->insert($table_name, $tower_data);
            }
            fclose($handle);
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

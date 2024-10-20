<?php

class Google_Sheet {
    public static $spreadsheetId = '1QcwrY0zJOH3Sv8iBQ9G_NiZj3DcVxOsn13QP-6JKvbA';
    private static $range = 'A1:DZ1008';
    
    public static function get_data() {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(__DIR__ . '/tower-database.json');
        $client->setAccessType('offline');
    
        $service = new Google_Service_Sheets($client);
        $response = $service->spreadsheets_values->get(self::$spreadsheetId, self::$range);
        $values = $response->getValues();
    
        if (empty($values)) {
            return 'No data found.';
        } else {
            $headers = array_shift($values);
            $json_data = [];
            foreach ($values as $row) {
                $json_row = [];
                foreach ($headers as $index => $header) {
                    $json_row[$header] = isset($row[$index]) ? $row[$index] : null;
                }
                $json_data[] = $json_row;
            }
            return json_encode($json_data, JSON_PRETTY_PRINT);
        }
    }
}

// Check if the 'action' query parameter is set to 'download_google_sheet_json'
add_action('init', function() {
    if (isset($_GET['action']) && $_GET['action'] === 'download_google_sheet_json') {
        // Fetch data from Google Sheets
        $json_data = Google_Sheet::get_data();
        
        // Set the content type header for JSON
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="google_sheet_data.json"');
        
        // Output the JSON data
        echo $json_data;
        
        // Terminate script to prevent further output
        exit();
    }
});
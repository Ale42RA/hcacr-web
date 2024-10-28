<?php
class Google_Sheet {
    public static $spreadsheetId;
    public static $range;

    // Static method to set spreadsheet ID and range
    public static function set_sheet_data($spreadsheetId, $range) {
        self::$spreadsheetId = $spreadsheetId;
        self::$range = $range;
    }

    public static function get_data() {
        // Check if spreadsheetId and range are set
        if (!self::$spreadsheetId || !self::$range) {
            throw new Exception('Spreadsheet ID or range is not set.');
        }

        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig(__DIR__ . '/tower-database.json');
        $client->setAccessType('offline');
    
        $service = new Google_Service_Sheets($client);
        
        // Add debug information for checking spreadsheetId and range
        error_log('Spreadsheet ID: ' . self::$spreadsheetId);
        error_log('Range: ' . self::$range);

        // Fetch data using the set spreadsheetId and range
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
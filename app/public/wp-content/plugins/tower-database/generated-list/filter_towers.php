<?php
   $search_term = $_GET['search'] ?? '';
   if ($search_term) {

        
        $search_metaphone = metaphone($search_term); 
        $towers = array_filter($towers, function($tower) use ($search_metaphone, $search_term) {
            //METAPHONE
            $town_metaphone = metaphone($tower->Town); 
            $town_name = $tower->Town; 

            if ($town_metaphone === $search_metaphone) {
                return true;
            }
            //Incomplete words
            if (stripos($town_name, $search_term) !== false) {
                return true;
            }
            //levenshtein -- typos
            $levenshtein_distance = levenshtein($search_term, $town_name);
            $max_distance = min(strlen($search_term), strlen($town_name)) / 3; 
            if ($levenshtein_distance <= $max_distance) {
                return true;
            }

            return false;
       });
   }

    // get the distinct towns for filtering
    $towns = array_unique(array_map(function($tower) {
        return $tower->Town;
    }, $towers));
    sort($towns);

    $selected_town = $_GET['town'] ?? '';
    $selected_practice_night = $_GET['practice_night'] ?? '';
    $sort_by = $_GET['sort_by'] ?? '';

    // filter by town
    if ($selected_town) {
        $towers = array_filter($towers, fn($tower) => $tower->Town === $selected_town);
    }

    // filter by practice night
    if ($selected_practice_night) {
        $towers = array_filter($towers, function($tower) use ($selected_practice_night) {
            $practice_day = strtok($tower->Practice_night, ' ');
            if (in_array($selected_practice_night, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
                return strcasecmp($practice_day, $selected_practice_night) === 0;
            }
            if ($selected_practice_night === 'Other') {
                return !in_array($practice_day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
            }
            return true;
        });
}

// sorting logic
if ($sort_by === 'name') {
    usort($towers, fn($a, $b) => strncasecmp($a->Dedication, $b->Dedication, 6));
} elseif ($sort_by === 'bells') {
    usort($towers, fn($a, $b) => $a->Number_of_bells - $b->Number_of_bells);
} elseif ($sort_by === 'practice_night') {
    usort($towers, fn($a, $b) => strcasecmp(strtok($a->Practice_night, ' '), strtok($b->Practice_night, ' ')));
}

?>
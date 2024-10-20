<?php

// Get the distinct districts and towns for filtering
$districts = array_unique(array_map(function($tower) {
    return $tower->District;
}, $towers));
sort($districts);

// Organize towns by district
$district_town_map = [];
foreach ($towers as $tower) {
    $district_town_map[$tower->District][] = $tower->Town;
}
foreach ($district_town_map as $district => $towns) {
    $district_town_map[$district] = array_unique($towns);
    sort($district_town_map[$district]);
}

// Filter and sorting logic
$selected_district = $_GET['district'] ?? '';
$selected_town = $_GET['town'] ?? '';
$selected_practice_night = $_GET['practice_night'] ?? '';
$sort_by = $_GET['sort_by'] ?? '';

if ($selected_district) {
    $towers = array_filter($towers, fn($tower) => strncasecmp($tower->District, $selected_district, 6) === 0);
}
if ($selected_town) {
    $towers = array_filter($towers, fn($tower) => $tower->Town === $selected_town);
}

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

if ($sort_by === 'name') {
    usort($towers, fn($a, $b) => strncasecmp($a->Dedication, $b->Dedication, 6));
} elseif ($sort_by === 'bells') {
    usort($towers, fn($a, $b) => $a->Number_of_bells - $b->Number_of_bells);
} elseif ($sort_by === 'practice_night') {
    usort($towers, fn($a, $b) => strcasecmp(strtok($a->Practice_night, ' '), strtok($b->Practice_night, ' ')));
}
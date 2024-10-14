<style>
    /* Existing CSS for the tower display with modifications */
    /* Ensure the tower-card container uses flexbox to align items evenly */
/* Ensure the tower-card container uses flexbox to align items evenly */
.tower-cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    align-items: stretch; /* Ensures all cards are the same height */
}

.tower-card {
    border: 1px solid #007bff; /* Change border color to blue */
    border-radius: 4px;
    padding: 0; /* Remove padding around the image */
    display: flex;
    flex-direction: column; /* Stack items vertically */
    width: 300px; /* Set fixed width for cards */
    background-color: #f5f5f5;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.tower-card img {
    width: 100%;
    height: auto;
    border-bottom: 2px solid #007bff; /* Add blue border below image */
}

.tower-card-header {
    background-color: #37496e; /* Dark blue background for text */
    color: white;
    padding: 10px 0;
}

.tower-card-header h2 {
    margin: 0;
    font-size: 1.4em;
    font-weight: bold;
    text-transform: uppercase;
}

.tower-card-body {
    flex-grow: 1; /* Makes the body grow to fill available space */
    padding: 20px;
    background-color: #37496e;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center; /* Center content vertically */
}

.tower-card-body p {
    font-size: 1.1em;
    margin: 10px 0;
    color: white;
}

.tower-card-footer {
    background-color: #563d7c; /* Purple footer */
    color: #ffc107; /* Yellow text for link */
    padding: 15px 0;
}

.tower-card-footer a {
    color: #ffc107; /* Yellow link */
    font-weight: bold;
    text-decoration: none;
    font-size: 1.1em;
}

.tower-card-footer a:hover {
    text-decoration: underline;
}

/* CSS to hide extra cards initially */
.tower-card.hidden {
    display: none;
}

/* Button to show more cards */
.show-more-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    font-size: 1.1em;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
}

.show-more-btn:hover {
    background-color: #0056b3;
}
</style>

<?php
// Retrieve the tower data from the database (assuming $towers is the list of towers from the database)

// Get the distinct districts and towns for filtering
$districts = array_unique(array_map(function($tower) {
    return $tower->District;
}, $towers));
sort($districts); // Sort districts alphabetically

// Organize towns by district
$district_town_map = [];
foreach ($towers as $tower) {
    $district_town_map[$tower->District][] = $tower->Town;
}
foreach ($district_town_map as $district => $towns) {
    $district_town_map[$district] = array_unique($towns); // Ensure towns are unique
    sort($district_town_map[$district]); // Sort towns alphabetically
}

// Check if there are any filter or sort inputs
$selected_district = isset($_GET['district']) ? $_GET['district'] : '';
$selected_town = isset($_GET['town']) ? $_GET['town'] : '';
$selected_practice_night = isset($_GET['practice_night']) ? $_GET['practice_night'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';

// Filter towers by district and town if selected
if ($selected_district) {
    $towers = array_filter($towers, function($tower) use ($selected_district) {
        return strncasecmp($tower->District, $selected_district, 6) === 0;
    });
}
if ($selected_town) {
    $towers = array_filter($towers, function($tower) use ($selected_town) {
        return $tower->Town === $selected_town;
    });
}

// Filter towers by practice night (Monday to Friday or Other)
if ($selected_practice_night) {
    $towers = array_filter($towers, function($tower) use ($selected_practice_night) {
        $practice_day = strtok($tower->Practice_night, ' '); // Get the first word of Practice Night

        // If filtering by a specific day (Monday to Friday)
        if (in_array($selected_practice_night, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
            return strcasecmp($practice_day, $selected_practice_night) === 0;
        }

        // If filtering by 'Other'
        if ($selected_practice_night === 'Other') {
            return !in_array($practice_day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
        }

        return true;
    });
}

// Sort towers based on the selected sort option
// Sort towers based on the selected sort option
if ($sort_by === 'name') {
    usort($towers, function($a, $b) {
        return strncasecmp($a->Dedication, $b->Dedication, 6);
    });
} elseif ($sort_by === 'bells') {
    usort($towers, function($a, $b) {
        return $a->Number_of_bells - $b->Number_of_bells;
    });
} elseif ($sort_by === 'practice_night') {
    // Sort by the first word of the Practice night field
    usort($towers, function($a, $b) {
        $first_word_a = strtok($a->Practice_night, ' '); // Get the first word (day) of Practice Night
        $first_word_b = strtok($b->Practice_night, ' ');
        return strcasecmp($first_word_a, $first_word_b);
    });
}

?>

<!-- Style the form -->
<style>
    .tower-filter-form {
        margin-bottom: 20px;
    }
    .tower-filter-form select, .tower-filter-form button {
        padding: 10px;
        margin-right: 10px;
        font-size: 1.1em;
    }
</style>

<!-- Display the filter and sort form -->
<!-- Display the filter and sort form -->
<form class="tower-filter-form" method="GET">
    <label for="district">Filter by District:</label>
    <select name="district" id="district" onchange="updateTownOptions()">
        <option value="">All Districts</option>
        <?php foreach ($districts as $district): ?>
            <option value="<?php echo esc_attr(htmlspecialchars(substr($district, 0, 6), ENT_QUOTES)); ?>" <?php selected($selected_district, substr($district, 0, 6)); ?>>
                <?php echo esc_html($district); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="town">Filter by Town:</label>
    <select name="town" id="town" disabled>
        <option value="">All Towns</option>
        <!-- Town options will be populated based on the selected district -->
    </select>

    <label for="practice_night">Filter by Practice Night:</label>
    <select name="practice_night" id="practice_night">
        <option value="">All Practice Nights</option>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Other">Other</option>
    </select>

    <button type="submit">Apply</button>
</form>

<!-- Display the towers -->
<div class="tower-cards-container">
    <?php if (!empty($towers)): ?>
        <?php foreach ($towers as $tower): ?>
            <div class="tower-card">
                <!-- Tower Card Header with Name -->
                <div class="tower-card-header">
                    <h2><?php echo esc_html($tower->Dedication); ?></h2>
                </div>

                <!-- tower card body with image and bells nfo -->
                <div class="tower-card-body">
                    <img src="<?php echo esc_url( wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph ); ?>" alt="<?php echo esc_attr($tower->Photograph); ?>">
                    <p><?php echo esc_html($tower->District . ', ' . $tower->Town); ?></p>
                    <p>Bells: <?php echo esc_html($tower->Number_of_bells); ?></p>
                    <p>Practice night: <br> <?php echo esc_html($tower->Practice_night); ?></p>
                </div>

                <!-- Optional Footer Section for Further Details Link -->
                <div class="tower-card-footer">
                <a href="/tower/<?php echo strtolower(str_replace([' ', "'"], ['-', ''], $tower->District)) . '/' . strtolower(str_replace(' ', '-', $tower->Town)) . '-' . strtolower(str_replace(' ', '-', $tower->Dedication)); ?>">See full details</a>             </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No towers found for the selected criteria.</p>
    <?php endif; ?>
</div>

<!-- town options -->
<script>
    var districtTownMap = <?php echo json_encode($district_town_map, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;

    function updateTownOptions() {
        var districtSelect = document.getElementById('district');
        var townSelect = document.getElementById('town');
        var selectedDistrict = districtSelect.value;

        // Clear existing town options
        townSelect.innerHTML = '<option value="">All Towns</option>';

        // Disable town dropdown if no district is selected
        if (selectedDistrict === "") {
            townSelect.disabled = true;
        } else {
            // Populate the town dropdown with relevant towns
            Object.keys(districtTownMap).forEach(function (district) {
                if (district.startsWith(selectedDistrict)) {
                    districtTownMap[district].forEach(function(town) {
                        var option = document.createElement('option');
                        option.value = town;
                        option.text = town;
                        townSelect.add(option);
                    });
                }
            });
            townSelect.disabled = false;
        }
    }
</script>
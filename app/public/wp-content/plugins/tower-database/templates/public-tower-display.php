<style>
/* Existing CSS for the tower display */
.tower-card {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    display: inline-block;
    width: 20%;
    vertical-align: top;
    text-align: center;
}
.tower-card-header h2 {
    margin: 0;
    font-size: 1.8em;
    font-weight: bold;
    text-align: center;
}
.tower-card-body p {
    font-size: 1.2em;
    margin-top: 10px;
}
.tower-card img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto 20px;
}
.tower-cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}
/* Styling for sorting and filtering options */
.filter-sort-container {
    margin-bottom: 20px;
    text-align: center;
}
.filter-sort-container label {
    font-size: 1.2em;
    margin-right: 10px;
}
.filter-sort-container select {
    padding: 5px;
    font-size: 1.1em;
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
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';

// Filter towers by district and town if selected
if ($selected_district) {
    $towers = array_filter($towers, function($tower) use ($selected_district) {
        return $tower->District === $selected_district;
    });
}
if ($selected_town) {
    $towers = array_filter($towers, function($tower) use ($selected_town) {
        return $tower->Town === $selected_town;
    });
}

// Sort towers based on the selected sort option
if ($sort_by === 'name') {
    usort($towers, function($a, $b) {
        return strcmp($a->Dedication, $b->Dedication);
    });
} elseif ($sort_by === 'bells') {
    usort($towers, function($a, $b) {
        return $a->Number_of_bells - $b->Number_of_bells;
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
<form class="tower-filter-form" method="GET">
    <label for="district">Filter by District:</label>
    <select name="district" id="district" onchange="updateTownOptions()">
        <option value="">All Districts</option>
        <?php foreach ($districts as $district): ?>
            <option value="<?php echo esc_attr($district); ?>" <?php selected($selected_district, $district); ?>>
                <?php echo esc_html($district); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="town">Filter by Town:</label>
    <select name="town" id="town" disabled>
        <option value="">All Towns</option>
        <!-- Town options will be populated based on the selected district -->
    </select>

    <label for="sort_by">Sort by:</label>
    <select name="sort_by" id="sort_by">
        <option value="name" <?php selected($sort_by, 'name'); ?>>Name (Alphabetical)</option>
        <option value="bells" <?php selected($sort_by, 'bells'); ?>>Number of Bells</option>
    </select>

    <button type="submit">Apply</button>
</form>

<!-- Display the towers -->
<div class="tower-cards-container">
    <?php if (!empty($towers)): ?>
        <?php foreach ($towers as $tower): ?>
            <div class="tower-card">
                <div class="tower-card-header">
                    <h2><?php echo esc_html($tower->Dedication); ?></h2>
                </div>
                <div class="tower-card-body">
                    <img src="<?php echo esc_url( wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph ); ?>" alt="<?php echo esc_attr($tower->Photograph); ?>">
                    <p><?php echo esc_html($tower->District . ', ' . $tower->Town); ?></p>
                    <p>Bells: <?php echo esc_html($tower->Number_of_bells); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No towers found for the selected criteria.</p>
    <?php endif; ?>
</div>

<!-- town options -->
<script>
    var districtTownMap = <?php echo json_encode($district_town_map, JSON_HEX_APOS | JSON_HEX_QUOT); ?>; 
    //TODO: FIX BISHOP's issue here. 

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
            districtTownMap[selectedDistrict].forEach(function(town) {
                var option = document.createElement('option');
                option.value = town;
                option.text = town;
                townSelect.add(option);
            });
            townSelect.disabled = false;
        }
    }
</script>
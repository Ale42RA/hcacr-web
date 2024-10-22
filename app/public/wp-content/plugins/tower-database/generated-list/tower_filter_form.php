<!-- tower_filter_form.php TODO GPT CHECK -->


<form class="tower-filter-form" method="GET">
    <label for="search">Search by tower Name:</label>
    <input type="text" name="search" id="search" placeholder="Enter tower name" value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">

    <label for="town">Filter by Town:</label>
    <select name="town" id="town">
        <option value="">All Towns</option>
        <?php foreach ($towns as $town): ?>
            <option value="<?php echo esc_attr(htmlspecialchars($town, ENT_QUOTES)); ?>" <?php selected($selected_town, $town); ?>>
                <?php echo esc_html($town); ?>
            </option>
        <?php endforeach; ?>
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


<script>
 
    var districtTownMap = <?php echo json_encode($district_town_map, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;

    function updateTownOptions() {
        var districtSelect = document.getElementById('district');
        var townSelect = document.getElementById('town');
        var selectedDistrict = districtSelect.value;

        townSelect.innerHTML = '<option value="">All Towns</option>';

        if (selectedDistrict === "") {
            townSelect.disabled = true;
        } else {
            districtTownMap[selectedDistrict]?.forEach(function(town) {
                var option = document.createElement('option');
                option.value = town;
                option.text = town;
                townSelect.add(option);
            });
            townSelect.disabled = false;
        }
    }
    
</script>
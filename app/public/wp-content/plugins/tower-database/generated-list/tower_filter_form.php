<!-- tower_filter_form.php TODO GPT CHECK -->

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
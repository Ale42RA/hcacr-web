<?php
// Check if either $officers or $dofficers is set, and assign to a common variable
$data = isset($officers) && !empty($officers) ? $officers : (isset($dofficers) && !empty($dofficers) ? $dofficers : []);

if (empty($data)) {
    echo '<p>No officer data found.</p>';
    return;
}

$hide_role_name_for = ['President', 'Patrons', 'Representatives to the Central Council'];

?>

<div class="officers-container">
    <?php foreach ($data as $officer): ?>
        <div class="officer-card">
            <?php
            if (!in_array($officer->Role, $hide_role_name_for)) {
                // If the role contains "District Secretary", remove "District Secretary" and display the rest
                if (strpos($officer->Role, 'District Secretary - ') === 0) {
                    $role_display = trim(str_replace('District Secretary - ', '', $officer->Role));
                } else {
                    $role_display = $officer->Role;
                }
            ?>
                <?php if (!empty($role_display)): ?>
                    <h3><?php echo esc_html($role_display); ?></h3>
                <?php endif; ?>
            <?php } ?>
            <p><?php echo esc_html($officer->Name); ?></p>
            <?php if (!empty($officer->Address)): ?>
                <p><?php echo esc_html($officer->Address); ?></p>
            <?php endif; ?>
            <?php if (!empty($officer->Phone)): ?>
                <p><?php echo esc_html($officer->Phone); ?></p>
            <?php endif; ?>
            <?php if (!empty($officer->Email)): ?>
                <p><a href="mailto:<?php echo esc_attr($officer->Email); ?>"><?php echo esc_html($officer->Email); ?></a></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<style>
.officers-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.officer-card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 20px;
    width: 300px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    text-align: center;
}

.officer-card h3 {
    color: #0073aa;
    font-size: 1.2em;
    margin-bottom: 10px;
}

.officer-card p {
    font-size: 1em;
    margin: 5px 0;
}

.officer-card a {
    color: #0073aa;
    text-decoration: none;
}

.officer-card a:hover {
    text-decoration: underline;
}
</style>
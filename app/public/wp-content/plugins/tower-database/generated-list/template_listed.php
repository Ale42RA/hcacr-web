<!-- main.php  TODO GPT GENERATED CHECK FOR REDUNDANCIES-->

<?php
// Include necessary files
include 'filter_towers.php';
?>
<style>
    .tower-cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    align-items: stretch; /* Ensures all cards are the same height */
}

.tower-card {
    border: 1px solid #007bff; 
    border-radius: 4px;
    padding: 0;
    display: flex;
    flex-direction: column;
    width: 400px;
    height: 800px; /* Fixed height for the card */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    font-family: Arial, Helvetica, sans-serif;
    color: white;
    overflow: hidden; /* Ensure content doesn't overflow the card */
    background-color: white; /* White background for the entire card */
}

.tower-card-image-container {
    width: 100%;
    height: 70%; /* The image takes 50% of the card's height */
    background-size: cover; /* Ensure background image covers this area */
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the background image */
    position: relative;
}

.tower-card-header {
    background-color: #25234d;
    color: white;
    padding: 10px 0;
    text-align: center;
    font-family: Arial, Helvetica, sans-serif;
    height: 72px; /* Fixed height for three lines */
    line-height: 1.2em; /* Adjust line height for readability */
    overflow: hidden; /* Ensures text beyond three lines is hidden */
    display: -webkit-box;
    -webkit-line-clamp: 3; /* Limits to three lines */
    -webkit-box-orient: vertical;
    text-overflow: ellipsis; /* Adds ellipsis if text is too long */
    white-space: normal; /* Allows text to wrap to the next line */
}

.tower-card-header h2 {
    margin: 0;
    font-size: 1.4em;
    font-weight: bold;
    text-transform: uppercase;
}

.tower-card-body {
    flex-grow: 1;
    padding: 20px;
    background-color: white; /* Set the body background to solid white */
    color: black;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 30%; /* Body and footer share the remaining 50% */
}
.tower-card-body p {
    padding: 10px;
    font-size: 0.9em;
}

.tower-card-footer {
    background-color: white; /* Set the footer background to solid white */
    color: #ffc107;
    padding: 15px 0;
    text-align: center;
    font-family: Arial, Helvetica, sans-serif;
}

.tower-card-footer a {
    color: darkblue;
    font-weight: bold;
    text-decoration: none;
    font-size: 1.1em;
}

.tower-card-footer a:hover {
    text-decoration: underlined;
    color: yellow;
    font-size: 1.1em;
    background-color: darkblue;
}
</style>

<?php include 'tower_filter_form.php'; ?>

<div class="tower-cards-container">
    <?php if (!empty($towers)): ?>
        <?php foreach ($towers as $tower): ?>
            <div class="tower-card">
                <div class="tower-card-image-container" style="background-image: url('<?php echo esc_url(wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph); ?>');">
                    <div class="tower-card-header">
                        <h2><?php echo esc_html($tower->Dedication); ?></h2>
                    </div>
                </div>

                <div class="tower-card-body">
                    <p><?php echo esc_html($tower->District . ', ' . $tower->Town); ?></p>
                    <p>Practice night: <?php echo esc_html($tower->Practice_night); ?></p>
                    <p><?php echo display_bells_data_shortcode(['summary' => 'true'], $tower); ?></p>
                    </div>

                <div class="tower-card-footer">
                    <a href="/tower/<?php echo strtolower(str_replace([' ', '(', ')', '&', "'"], ['-', '', '', '', ''], $tower->District)) . '~' . strtolower(str_replace([' ', '(', ')', '&', "'"], ['-', '', '', '', ''], $tower->Town)) . '~' . strtolower(str_replace([' ', '(', ')', '&', "'"], ['-', '', '', '', ''], $tower->Dedication)); ?>">See full details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No towers found for the selected criteria.</p>
    <?php endif; ?>
</div>
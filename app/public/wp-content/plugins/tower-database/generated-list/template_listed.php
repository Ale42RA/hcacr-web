<?php
include 'filter_towers.php';
?>
<style>
.tower-cards-scrollable-container {
    max-height: 600px; /* Adjust this height as needed */
    overflow-y: auto;
    border: 1px solid #ccc; /* Optional: add a border */
    padding: 10px; /* Optional: add some padding */
}

.tower-cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    align-items: stretch;
}

.tower-card-link {
    text-decoration: none;
    color: inherit;
    width: 350px;
    display: block;
}

.tower-card {
    border: 1px solid #007bff; 
    border-radius: 25px;
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 500px; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    font-family: Arial, Helvetica, sans-serif;
    color: white;
    overflow: hidden;
    background-color: white;
    margin: 20px;
}

.tower-card-image-container {
    width: 100%;
    height: 70%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
}

.tower-card-body {
    flex-grow: 1;
    padding: 10px;
    background-color: white;
    color: black;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tower-card-body h2 {
    margin: 0;
    font-size: 1.4em;
    font-weight: bold;
    text-transform: uppercase;
    text-align: left;
    font-family: avenir, sans-serif; 
}

.tower-card-body-columns {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    gap: 10px;
}

.tower-card-body-column {
    flex: 1;
    padding: 5px;
}

.tower-card-body-column p {
    margin: 5px 0;
    font-size: 1em;
    font-family: avenir, sans-serif; 
}

.tower-card-footer a {
    display: none;
}
</style>

<?php include 'tower_filter_form.php'; ?>

<div class="tower-cards-scrollable-container">
    <div class="tower-cards-container">
        <?php if (!empty($towers)): ?>
            <?php foreach ($towers as $tower): ?>
                <a href="/tower/<?php echo strtolower(str_replace([' ', '(', ')', '&', "'"], ['-', '', '', '', ''], $tower->District)) . '~' . strtolower(str_replace([' ', '(', ')', '&', "'"], ['-', '', '', '', ''], $tower->Town)) . '~' . strtolower(str_replace([' ', '(', ')', '&', "'"], ['-', '', '', '', ''], $tower->Dedication)); ?>" class="tower-card-link">
                    <div class="tower-card">
                        <div class="tower-card-image-container" style="background-image: url('<?php echo esc_url(wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph); ?>');">
                        </div>

                        <div class="tower-card-body">
                            <h2><?php echo esc_html($tower->Town); ?></h2>

                            <div class="tower-card-body-columns">
                                <div class="tower-card-body-column">
                                    <p><?php echo esc_html($tower->Dedication); ?></p>
                                </div>

                                <div class="tower-card-body-column">
                                    <p><?php echo display_bells_data_shortcode(['summary' => 'true'], $tower); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No towers found for the selected criteria.</p>
        <?php endif; ?>
    </div>
</div>
<style>
.tower-cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Center the cards within the container */
    width: 500%; /* Ensure the total row spans 70% of the screen */
    margin: 0 auto; /* Center the container on the screen */
}

.tower-card {
    background-color: black; /* Black background to match the design */
    color: white; /* White text color */
    border: none; /* Removing the border */
    width: 22%; /* Adjusted width for 4 cards in a row */
    padding: 15px;
    box-sizing: border-box;
    text-align: center;
}

.tower-card-header h2 {
    margin: 0;
    font-size: 1.8em;
    color: white; /* Ensuring the title is white */
    font-weight: bold;
    text-align: center;
}

.tower-card-body p {
    font-size: 1.2em;
    text-align: center;
    margin-top: 10px;
    color: white; /* Ensuring text is white */
}

.tower-card img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
    margin-bottom: 20px;
    border-radius: 0; /* No border-radius to match design */
}

/* Ensure responsiveness */
@media (max-width: 1200px) {
    .tower-card {
        max-width: 31%; /* Adjust to 3 cards per row */
    }
}

@media (max-width: 900px) {
    .tower-card {
        max-width: 48%; /* Adjust to 2 cards per row */
    }
}

@media (max-width: 600px) {
    .tower-card {
        max-width: 100%; /* Adjust to 1 card per row */
    }
}


</style>

<div class="tower-cards-container">
    <?php foreach ($towers as $tower): ?>
        <div class="tower-card">
            <div class="tower-card-header">
                <h2><?php echo esc_html($tower->Dedication); ?></h2>
            </div>
            <div class="tower-card-body">
                <img src="<?php echo esc_url( wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph ); ?>" alt="<?php echo esc_attr($tower->Photograph); ?>">
                <p><?php echo esc_html($tower->District . ', ' . $tower->Town); ?></p>
                <p>Bells: <?php echo esc_html($tower->Number_of_bells); ?>, temporary text</p>
            </div>
        </div>
    <?php endforeach; ?>
</div>


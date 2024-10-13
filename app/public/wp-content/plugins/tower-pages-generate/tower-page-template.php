<?php
// Fetch the tower data based on query vars
$tower_district = get_query_var('tower_district');
$tower_name = get_query_var('tower_name');
$tower = get_tower_data($tower_district, $tower_name);

if ($tower) : ?>

<div class="tower-page">
    <h1><?php echo esc_html($tower->Dedication); ?></h1>
    <p><strong>District:</strong> <?php echo esc_html($tower->District); ?></p>
    <p><strong>Town:</strong> <?php echo esc_html($tower->Town); ?></p>
    <p><strong>Number of Bells:</strong> <?php echo esc_html($tower->Number_of_bells); ?></p>
    <img src="<?php echo esc_url(wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph); ?>" alt="<?php echo esc_attr($tower->Dedication); ?>">
</div>

<?php else : ?>

<div class="tower-page">
    <h1>Tower Not Found</h1>
    <p>Sorry, we could not find any tower with the specified district and name.</p>
</div>

<?php endif; ?>
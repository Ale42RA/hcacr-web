<div class="wrap">
    <h1>Tower Manager</h1>

    <h2>Existing Towers</h2>
    <button id="downloadJson" class="button">Download Google Sheet JSON</button>
    <button id="goToGoogleSheet" class="button">GoToGoogleSheet</button>


    <div class="table-container">
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Town</th>
                    <th>Dedication</th>
                    <th>District</th>
                    <th>Number of Bells</th>
                    <th>Ringing Master</th>
                    <th>Tower Captain</th>
                    <th>Secretary</th>
                    <th>Practice Night</th>
                    <th>Service Ringing</th>
                    <th>Photograph</th>
                    <th>DoveID</th>
                    <th>Ground Floor</th>
                    <th>Toilet</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($towers): ?>
                    <?php foreach ($towers as $tower): ?>
                        <tr>
                            <td><?php echo esc_html($tower->ID); ?></td>
                            <td><?php echo esc_html($tower->Town); ?></td>
                            <td><?php echo esc_html($tower->Dedication); ?></td>
                            <td><?php echo esc_html($tower->District); ?></td>
                            <td><?php echo esc_html($tower->Number_of_bells); ?></td>
                            <td><?php echo esc_html($tower->Ringing_master); ?></td>
                            <td><?php echo esc_html($tower->Tower_captain); ?></td>
                            <td><?php echo esc_html($tower->Secretary); ?></td>
                            <td><?php echo esc_html($tower->Practice_night); ?></td>
                            <td><?php echo esc_html($tower->Service_ringing); ?></td>
                            <td>
                                <?php
                                $image_url = esc_url(wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph);
                                echo '<a href="' . $image_url . '">' . $image_url . '</a>';
                                ?>
                            </td>
                            <td><?php echo esc_html($tower->DoveID); ?></td>
                            <td><?php echo esc_html($tower->Ground_floor ? 'Yes' : 'No'); ?></td>
                            <td><?php echo esc_html($tower->Toilet ? 'Yes' : 'No'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="24">No towers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-container {
        max-height: 400px; /* Adjust height as needed */
        overflow-y: auto;
        overflow-x: auto; /* Enables horizontal scrolling */
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    table.widefat {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px; /* Set a min-width to force horizontal scrolling */
    }

    table.widefat th,
    table.widefat td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    table.widefat th {
        background-color: #f4f4f4;
    }
</style>


<script>
document.getElementById('downloadJson').addEventListener('click', function () {
    fetch('<?php echo admin_url('admin.php?action=download_google_sheet_json'); ?>')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Create a blob from the JSON data
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'google_sheet_data.json';
            
            link.click();
        })
        .catch(error => {
            console.error('Error downloading the JSON file:', error);
        });
});
document.getElementById('goToGoogleSheet').addEventListener('click', function () {
    const googleSheetId = '<?php echo Google_Sheet::$spreadsheetId; ?>'; // Sheet ID from PHP
    
    if (googleSheetId) {
        const googleSheetUrl = `https://docs.google.com/spreadsheets/d/${googleSheetId}/edit`;
        window.open(googleSheetUrl, '_blank'); //open in new tab 
    } else {
        console.error('Google Sheet ID not found');
    }
});
</script>
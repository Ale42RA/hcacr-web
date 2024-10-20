<div class="wrap">
    <h1>Tower Manager</h1>

    <h2>Existing Towers</h2>
    <button id="downloadJson" class="button">Download Google Sheet JSON</button>
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
                <th>Image URL</th> <!-- Keep this column -->
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
                        <td>
                            <?php
                            $image_url = esc_url(wp_upload_dir()['baseurl'] . '/tower/' . $tower->Photograph);
                            echo '<a href="' . $image_url . '">' . $image_url . '</a>';
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No towers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


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
            
            // Create a download link
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'google_sheet_data.json';
            
            // Trigger the download
            link.click();
        })
        .catch(error => {
            console.error('Error downloading the JSON file:', error);
        });
});
</script>
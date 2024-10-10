<div class="wrap">
    <h1>Tower Manager</h1>
    <h2>Upload CSV</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="tower_csv" accept=".csv">
        <p class="submit">
            <input type="submit" class="button-primary" value="Upload CSV">
        </p>
    </form>

    <h2>Existing Towers</h2>
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

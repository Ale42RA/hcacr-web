<div class="wrap">
    <h1>HCACR Database</h1>

    <h2>HCACR Officers List</h2>
<div class="table-container">
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th>Role</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($officers): ?>
                <?php foreach ($officers as $officer): ?>
                    <tr>
                        <td><?php echo esc_html($officer->Role); ?></td>
                        <td><?php echo esc_html($officer->Name); ?></td>
                        <td><?php echo esc_html($officer->Address); ?></td>
                        <td><?php echo esc_html($officer->Phone); ?></td>
                        <td><?php echo esc_html($officer->Email); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No officers data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<h2>District Officers List</h2>
<div class="table-container">
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th>District</th>
                <th>Role</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($district_officers): ?>
                <?php foreach ($district_officers as $officer): ?>
                    <tr>
                        <td><?php echo esc_html($officer->District); ?></td>
                        <td><?php echo esc_html($officer->Role); ?></td>
                        <td><?php echo esc_html($officer->Name); ?></td>
                        <td><?php echo esc_html($officer->Address); ?></td>
                        <td><?php echo esc_html($officer->Phone); ?></td>
                        <td><?php echo esc_html($officer->Email); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No district officers data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>

<style>
  
    .table-container {
        max-height: 650px; /* Adjust height as needed */
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

    table.widefat th {
    background-color: grey; /* Light grey background for header */
    font-weight: bold; /* Bold text for header */
    padding: 8px;
    border: 1px solid #ddd;
    text-align: left;
    }

    table.widefat td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
        font-weight: normal; /* Normal text for table data */
    }
    table.widefat th {
        background-color: #f4f4f4;
    }
</style>


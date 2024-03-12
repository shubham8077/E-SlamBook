<?php
session_start();
// Include database connection
include 'partials/dbcon.php';

// Function to fetch data from the database based on date range
function fetch_data_from_database($from_date, $to_date)
{
    global $conn;

    $sql = "SELECT * FROM `user` WHERE admin_id = {$_SESSION['admin_id']} AND `date` BETWEEN '$from_date' AND '$to_date'";
    $result = $conn->query($sql);

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// Function to download data as CSV
function download_csv($data)
{
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    $headers = array_keys($data[0]);
    fputcsv($output, $headers);

    // Write data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Close output stream
    fclose($output);
}

// Check if form is submitted
if (isset($_POST['search'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $result = fetch_data_from_database($from_date, $to_date);
} else {
    // Default dates
    $from_date = date('Y-m-d', strtotime('-7 days')); // Default from date is 7 days ago
    $to_date = date('Y-m-d'); // Default to date is today
    $result = fetch_data_from_database($from_date, $to_date);
}

// Check if form is submitted for download
if (isset($_POST['submit_download'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Fetch data from the database
    $data = fetch_data_from_database($from_date, $to_date);

    // Download data as CSV
    download_csv($data);

    // Stop further execution after download
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report</title>
    <link rel="stylesheet" href="/index.css">
    <script src="tailwind.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="_favicon_/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="_favicon_/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="_favicon_/favicon-16x16.png">
    <link rel="manifest" href="_favicon_/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body class="bg-gradient-to-r from-indigo-500 to-orange-400 mb-24">
    <?php include 'partials/navbar.php'; ?>
    <h1
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Report 🔍</h1>
    <form method="post" action="dreport.php" class="flex justify-center mt-6">
        <p class="text-white font-bold">From</p><input type="date" placeholder="From" name="from_date"
            class="m-2 p-1 rounded border-2 border-blue-500" value="<?php echo $from_date; ?>">
        <p class="text-white font-bold">To</p><input type="date" placeholder="To" name="to_date"
            class="m-2 p-1 rounded border-2 border-blue-500" value="<?php echo $to_date; ?>">
        <input type="submit" name="search" value="Search"
            class="bg-cyan-400 rounded font-bold text-black my-2 p-1 border-2 border-black hover:bg-cyan-500">
    </form>
    <form method="post" action="dreport.php" class="flex justify-center">
        <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
        <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
        <button type="submit" name="submit_download"
            class="bg-blue-500 rounded font-bold my-2 p-1 text-white border-2 border-blue-500 hover:bg-blue-600">Download</button>
    </form>
    <?php if (!empty($result)): ?>
        <div class="flex justify-center">
            <table border="1" class="border-collapse border border-blue-500 m-2 bg-white">

                <tr>

                    <th class="border border-blue-500 p-1">Date</th>
                    <th class="border border-blue-500 p-1">Username</th>
                    <th class="border border-blue-500 p-1">Email</th>
                </tr>
                <?php foreach ($result as $row): ?>
                    <?php
                    echo "<tr>";
                    echo "<td class='border border-blue-500 p-1'>" . $row["date"] . "</td>";
                    echo "<td class='border border-blue-500 p-1'>" . $row["username"] . "</td>";
                    echo "<td class='border border-blue-500 p-1'>" . $row["email"] . "</td>";
                    echo "</tr>";
                    ?>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else: ?>
        <p>No data found.</p>
    <?php endif; ?>
    <?php include 'partials/footer.php'; ?>
</body>

</html>
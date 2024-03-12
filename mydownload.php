<?php
// Include database connection
include 'partials/dbcon.php';

// Function to fetch data from the database based on date range
function fetch_data_from_database($from_date, $to_date)
{
    global $conn;

    $sql = "SELECT * FROM `user` WHERE `date` BETWEEN '$from_date' AND '$to_date'";
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

    // Write CSV header
    fputcsv($output, array_keys($data[0]));

    // Write data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // Close output stream
    fclose($output);
}

// Check if form is submitted for download
if (isset($_POST['submit_download'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Fetch data from the database
    $data = fetch_data_from_database($from_date, $to_date);

    // Download data as CSV
    download_csv($data);
}
?>
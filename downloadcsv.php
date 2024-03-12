<?php
session_start();

// Check if the admin ID is present in the URL
if (!isset($_GET['user_id']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: error.php");
    exit;
}

// Add database connection and necessary queries here
include 'partials/dbcon.php';

// Fetch user data based on user ID
$userId = $_GET['user_id'];
$stmtDownload = $conn->prepare("
    SELECT user.*, basicinfo.*, fav.*, aspiration.*, questions.*, images.*
    FROM user
    LEFT JOIN basicinfo ON user.id = basicinfo.user_id
    LEFT JOIN fav ON basicinfo.id = fav.u_id
    LEFT JOIN aspiration ON basicinfo.id = aspiration.u_id
    LEFT JOIN questions ON aspiration.id = questions.u_id
    LEFT JOIN images ON questions.id = images.u_id
    WHERE user.id = ?
");

if (!$stmtDownload) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    exit;
}

$stmtDownload->bind_param('i', $userId);
$stmtDownload->execute();

$result = $stmtDownload->get_result();
$userDataDownload = $result->fetch_all(MYSQLI_ASSOC);

// Implement the logic to generate a downloadable file (e.g., CSV or Excel)

// Send headers to force file download
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="user_info.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Output CSV/Excel headers
fputcsv($output, array_keys($userDataDownload[0]));

// Output user data
foreach ($userDataDownload as $row) {
    fputcsv($output, $row);
}

// Close the output stream
fclose($output);

// Close database connection
$conn->close();
?>
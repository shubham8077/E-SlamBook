<?php
session_start();



// Check if user is logged in as admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin_id']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

// Include necessary files and initialize variables
include 'partials/dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to output CSV data
function outputCSV($data)
{
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}

// Define default SQL query
$sql = "SELECT * FROM user WHERE admin_id = {$_SESSION['admin_id']}";

// Modify SQL query if search criteria are set
if (isset($_POST['searchfrom'], $_POST['searchto'])) {
    $searchfrom = $_POST['searchfrom'];
    $searchto = $_POST['searchto'];
    $sql .= " AND date BETWEEN '$searchfrom' AND '$searchto'";
}

// Handle download request
if (isset($_POST['download'])) {
    $result = $conn->query($sql);

    // Generate CSV data
    $csv_data = array();
    $csv_data[] = array('Date', 'User Name', 'Email');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $csv_data[] = array($row["date"], $row["username"], $row["email"]);
        }
    }

    // Output CSV data
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=report.csv");
    outputCSV($csv_data);
    exit;
}

// Fetch data for display
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

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

<body class="bg-gradient-to-r from-indigo-500 to-orange-400">
    <?php include 'partials/navbar.php'; ?>
    <h1
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Report 🔍</h1>

    <!-- Search form -->
    <form method="POST" class="flex justify-center">
        <input type="text" name="searchfrom" placeholder="Search from" class="m-2 p-1 rounded border-2 border-blue-500">
        <input type="text" name="searchto" placeholder="Search to" class="m-2 p-1 rounded border-2 border-blue-500">
        <!-- <input type="text" name="search" placeholder="Search anything..."
            class="m-2 p-1 rounded border-2 border-blue-500"> -->
        <button type="submit"
            class="bg-cyan-400 rounded font-bold my-2 p-1 border-2 border-black hover:bg-cyan-500">Search</button>
    </form>




    <?php
    if ($result->num_rows > 0) {
        ?>
        <!-- Display search results -->
        <div class="flex justify-center">
            <table class="border-collapse border border-blue-500 m-2 bg-white">
                <thead>
                    <tr>
                        <th class="border border-blue-500 p-1">Date</th>
                        <th class="border border-blue-500 p-1">User Name</th>
                        <th class="border border-blue-500 p-1">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-blue-500 p-1'>" . $row["date"] . "</td>";
                        echo "<td class='border border-blue-500 p-1'>" . $row["username"] . "</td>";
                        echo "<td class='border border-blue-500 p-1'>" . $row["email"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<div class="flex flex-col justify-center items-center"><p class="text-lg text-white font-bold text-center bg-red-600 w-fit p-1 rounded mx-auto">No Results Found.</p><img src="img/notfound.png" class="w-60"></img></div>';
    }
    ?>

    <?php
    $conn->close();
    ?>

    <!-- Include your footer -->
    <?php include 'partials/footer.php' ?>
</body>

</html>
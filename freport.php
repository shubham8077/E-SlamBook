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
function outputCSV($data)
{
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}
// Fetch data based on search query
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM basicinfo WHERE BirthDay like '%$search%' or Full_Name like '%$search%' or Gender='$search' or Phone='$search' or Address like '%$search%' or Email like '%$search%'";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT * FROM basicinfo";
    $result = $conn->query($sql);
}

if (isset($_POST['download'])) {
    $result = $conn->query($sql);

    // Generate CSV data
    $csv_data = array();
    $csv_data[] = array('BirthDay', 'Full Name', 'Gender', 'Phone', 'Address', 'Email');
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $csv_data[] = array($row["BirthDay"], $row["Full_Name"], $row["Gender"], $row["Phone"], $row["Address"], $row["Email"]);
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

<body class="bg-gradient-to-r from-indigo-500 to-orange-400 mb-24">
    <?php include 'partials/navbar.php'; ?>
    <h1
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Report 🔍</h1>

    <!-- Search form -->
    <form method="POST" class="flex justify-center">
        <input type="text" name="search" placeholder="Search Here..." class="m-2 p-1 rounded border-2 border-blue-500">
        <button type="submit"
            class="bg-cyan-500 rounded font-bold my-2 p-1 text-white border-2 border-blue-500 hover:bg-cyan-600">Search</button>
    </form>
    <form method="POST" class="flex justify-center">
        <button type="submit" name="download" value="1"
            class="bg-blue-500 rounded font-bold my-2 p-1 text-white border-2 border-blue-500 hover:bg-blue-600">Download
            Report</button>
    </form>
    <!-- Display search results -->
    <div>
        <?php
        if ($result->num_rows > 0) {
            ?>
            <!-- Display search results -->
            <div class="flex justify-center">
                <table class="border-collapse border border-blue-500 m-2 bg-white">
                    <thead>
                        <tr>
                            <th class="border border-blue-500 p-1">BirthDay</th>
                            <th class="border border-blue-500 p-1">Full_Name</th>
                            <th class="border border-blue-500 p-1">Gender</th>
                            <th class="border border-blue-500 p-1">Phone</th>
                            <th class="border border-blue-500 p-1">Address</th>
                            <th class="border border-blue-500 p-1">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='border border-blue-500 p-1'>" . $row["BirthDay"] . "</td>";
                            echo "<td class='border border-blue-500 p-1'>" . $row["Full_Name"] . "</td>";
                            echo "<td class='border border-blue-500 p-1'>" . $row["Gender"] . "</td>";
                            echo "<td class='border border-blue-500 p-1'>" . $row["Phone"] . "</td>";
                            echo "<td class='border border-blue-500 p-1'>" . $row["Address"] . "</td>";
                            echo "<td class='border border-blue-500 p-1'>" . $row["Email"] . "</td>";
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
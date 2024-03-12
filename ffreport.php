<?php
session_start();

include 'partials/dbcon.php';

// Function to fetch data from the database based on search term
function fetch_data_from_database($search)
{
    global $conn;

    $sql = "SELECT * FROM basicinfo WHERE BirthDay like '%$search%' or Full_Name like '%$search%' or Gender='$search' or Phone='$search' or Address like '%$search%' or Email like '%$search%'";
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
function download_selected_data_csv($selectedData)
{
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write headers
    $headers = array_keys($selectedData[0]);
    fputcsv($output, $headers);


    // // Write data rows
    // foreach ($data as $row) {
    //     fputcsv($output, $row);
    // }

    // Write selected data rows
    foreach ($selectedData as $row) {
        fputcsv($output, $row);
    }

    // Close output stream
    fclose($output);
}

// Check if form is submitted for search
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $result = fetch_data_from_database($searchTerm);
}

// Check if form is submitted for download
if (isset($_POST['submit_download'])) {
    $searchTerm = $_POST['search'];

    // Fetch data from the database
    $data = fetch_data_from_database($searchTerm);

    // Download data as CSV
    download_selected_data_csv($data);


    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
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
        Search 🔍</h1>
    <form method="post" action="ffreport.php" class="flex justify-center mt-6">
        <input type="text" placeholder="Search Anything..." name="search"
            class="m-2 p-1 rounded border-2 border-blue-500"
            value="<?php echo isset($searchTerm) ? $searchTerm : ''; ?>">
        <input type="submit" value="Search"
            class="bg-cyan-400 rounded font-bold text-black my-2 p-1 border-2 border-black hover:bg-cyan-500">
    </form>
    <!-- <form method="post" action="ffreport.php" class="flex justify-center">
        <input type="hidden" name="search" value="?php echo isset($searchTerm) ? $searchTerm : ''; ?>">
        <button type="submit" name="submit_download"
            class="bg-blue-500 rounded font-bold my-2 p-1 text-white border-2 border-blue-500 hover:bg-blue-600">Download</button>
    </form> -->
    <?php if (!empty($result)): ?>
        <div class="flex justify-center">
            <table border="1" class="border-collapse border border-blue-500 m-2 bg-white">
                <tr>
                    <th class="border border-blue-500 p-1">BirthDay</th>
                    <th class="border border-blue-500 p-1">Full_Name</th>
                    <th class="border border-blue-500 p-1">Gender</th>
                    <th class="border border-blue-500 p-1">Phone</th>
                    <th class="border border-blue-500 p-1">Address</th>
                    <th class="border border-blue-500 p-1">Email</th>
                </tr>
                <?php foreach ($result as $row): ?>
                    <?php echo "<tr>"; ?>
                    <?php echo "<td class='border border-blue-500 p-1'>" . $row["BirthDay"] . "</td>"; ?>
                    <?php echo "<td class='border border-blue-500 p-1'>" . $row["Full_Name"] . "</td>"; ?>
                    <?php echo "<td class='border border-blue-500 p-1'>" . $row["Gender"] . "</td>"; ?>
                    <?php echo "<td class='border border-blue-500 p-1'>" . $row["Phone"] . "</td>"; ?>
                    <?php echo "<td class='border border-blue-500 p-1'>" . $row["Address"] . "</td>"; ?>
                    <?php echo "<td class='border border-blue-500 p-1'>" . $row["Email"] . "</td>"; ?>
                    <?php echo "</tr>"; ?>
                <?php endforeach; ?>
            </table>
        </div>
    <?php else: ?>
        <div class="flex flex-col justify-center items-center">
            <p class="text-lg text-white font-bold text-center bg-red-600 w-fit p-1 rounded mx-auto">No Results Found.</p>
            <img src="img/notfound.png" class="w-60"></img>
        </div>
    <?php endif; ?>
    <?php include 'partials/footer.php'; ?>
</body>

</html>
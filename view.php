<?php
session_start();
include "partials/dbcon.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['uloggedin']) || !isset($_SESSION['userimg_id'])) {
    header("location: ulogin.php");
    exit;
}

// Get user ID from session
$userId = $_SESSION['userimg_id'];

// Retrieve images for the logged-in user
$sql = "SELECT * FROM images WHERE u_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Check for database errors
if (!$result) {
    die("Database error: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
    <link rel="stylesheet" href="/index.css">
    <script src="tailwind.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="_favicon_/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="_favicon_/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="_favicon_/favicon-16x16.png">
    <link rel="manifest" href="_favicon_/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            min-height: 100vh;
        }

        .alb {
            width: 200px;
            height: 200px;
            padding: 5px;
        }

        .alb img {
            width: 100%;
            height: 100%;
        }

        a {
            text-decoration: none;
            color: black;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        form input {
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-indigo-500 to-orange-400">

    <div class="w-full">
        <div class="flex flex-row">
            <?php
            // Display images
            if ($result->num_rows > 0) {
                while ($images = $result->fetch_assoc()) {
                    ?>
                    <div class="alb">
                        <img src="uploads/<?= $images['image_url'] ?>" class="rounded-lg border-2 border-white">
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <?php
        // Generate hashed user ID for URL
        $hasheduserId = hash('sha256', $_SESSION['userimg_id']);

        // Display error message if present
        if (isset($_GET['error'])) {
            echo '<p>' . $_GET['error'] . '</p>';
        }
        ?>
        <div class="flex flex-row justify-center">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="my_image">
                <input type="submit" name="submit" value="Upload"
                    class="bg-white p-1 rounded-lg font-bold border-2 border-black">
            </form>
            <form action="userresult.php?userId=<?= $hasheduserId ?>" method="post">
                <input type="submit" name="submit" value="Submit"
                    class="bg-green-500 p-1 rounded-lg font-bold border-2 hover:text-white border-black">
            </form>
        </div>
        <?php include 'partials/footer.php' ?>
    </div>

</body>

</html>
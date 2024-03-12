<?php
session_start();

// Include your database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "slambook";

try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the admin ID is present in the URL
    if (!isset($_GET['admin_id']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        header("location: error.php");
        exit;
    }

    $hashedAdminId = $_GET['admin_id'];

    // Verify the admin ID by hashing the stored admin ID
    $isValidAdminId = hash_equals(hash('sha256', $_SESSION['admin_id']), $hashedAdminId);

    if (!$isValidAdminId) {
        // Invalid admin ID, redirect to the error page
        header("location: error.php");
        exit;
    }

    $adminId = $_SESSION['admin_id'];

    // Prepare SQL statement to count the number of basicinfo table IDs for the admin
    $countSql = "SELECT COUNT(DISTINCT basicinfo.id) as count
                 FROM user
                 LEFT JOIN basicinfo ON user.id = basicinfo.user_id
                 WHERE user.admin_id = :adminId";

    $countStmt = $conn->prepare($countSql);
    $countStmt->bindParam(':adminId', $adminId);
    $countStmt->execute();

    // Fetch the count result
    $userCount = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Close the count statement
    $countStmt = null;

    // Close the connection
    $conn = null;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badges</title>
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
    <div
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Badges<img src="img/Badge.png" class="w-10 h-10"> </div>
    <div class="text-center mb-24">
        <?php
        // Define the specific user counts at which badges will be displayed
        $badgeUserCounts = [1, 3, 5, 7, 10, 15, 20, 25]; // Add more user counts as needed
        
        // Display the circles with badge images
        foreach ($badgeUserCounts as $i => $badgeUserCount) {
            $badgeClass = ($userCount >= $badgeUserCount) ? 'bg-green-500' : 'locked-badge';

            echo '<div class="inline-block w-56 h-56 rounded-full m-4 mb-10 text-center' . $badgeClass . '">';

            if ($userCount >= $badgeUserCount) {
                if ($i == 0 && $userCount >= 1) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg1.png" alt="First Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 1 friend</div>';
                } elseif ($i == 1 && $userCount >= 3) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg2.jpg" alt="Second Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 3 friends</div>';
                } elseif ($i == 2 && $userCount >= 5) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg3.jpg" alt="Third Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 5 friends</div>';
                } elseif ($i == 3 && $userCount >= 7) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg4.png" alt="Third Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 7 friends</div>';
                } elseif ($i == 4 && $userCount >= 10) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg5.jpg" alt="Third Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 10 friends</div>';
                } elseif ($i == 5 && $userCount >= 15) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg6.jpg" alt="Third Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 15 friends</div>';
                } elseif ($i == 6 && $userCount >= 20) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg7.jpg" alt="Third Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 20 friends</div>';
                } elseif ($i == 7 && $userCount >= 25) {
                    echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/bdg8.jpg" alt="Third Badge" class="rounded-full border-2 border-black hover:animate-pulse">Awarded for 25 friends</div>';
                }
                // Add more conditions for additional badges if needed
            } else {
                // Display the locked badge image
                echo '<div class="text-center font-semibold text-xl bg-white rounded-t-full p-1"><img src="img/qbg.png" alt="Locked Badge" class="rounded-full border-2 border-black"><p class="text-center font-semibold text-xl" >Locked</p></div>';
            }

            echo '</div>';
        }
        ?>
    </div>
    <?php include 'partials/footer.php' ?>

</body>

</html>
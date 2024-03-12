<?php
session_start();

if (!isset($_SESSION['uloggedin']) || $_SESSION['uloggedin'] != true || !isset($_SESSION['user_id'])) {
    header("location: ulogin.php");
    exit;
}
$userId = $_SESSION['user_id'];
$adminname = $_SESSION['adminname'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn'])) {
    // Redirect to sbform.php with user_id
    $hashedUserId = hash('sha256', $userId);

    // Redirect to sbform.php with hashed user_id
    header("Location: sbform.php?user_id=$hashedUserId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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

    <?php
    include("partials/unavbar.php");
    ?>

    <!-- card -->

    <div class="w-full drop-shadow-2xl mb-24 flex justify-center items-center select-none">
        <div>
            <p class="m-4 text-white text-3xl font-normal drop-shadow-lg text-center">Welcome to
                <?php echo $_SESSION['adminname'] ?>'s E-Slambook
            </p>

            <p class="m-4 text-lime-400 text-3xl font-normal drop-shadow-lg text-center">Share our sweet memories...</p>

            <div>
                <img src="https://images.pexels.com/photos/2874998/pexels-photo-2874998.jpeg?auto=compress&cs=tinysrgb&w=600"
                    class="h-60 flex mx-auto border-2 border-dashed border-white">
            </div>

            <form action="welcome.php" method="post">
                <button type="submit" name="btn"
                    class="rounded-lg bg-cyan-400 text-center font-bold border-2 border-black hover:bg-cyan-500 p-1 m-2 text-xl w-11/12 mx-auto flex justify-center">Let's
                    get started</button>
            </form>
        </div>
    </div>
    </div>
    <?php include 'partials/footer.php' ?>


</body>

</html>
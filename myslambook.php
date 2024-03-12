<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}
// Generate a secure hash of the admin ID
$adminId = $_SESSION['admin_id'];
$hashedAdminId = hash('sha256', $adminId);

// Generate the URL for result.php with hashed admin ID
$resultUrl = "http://localhost/Slambook/result.php?admin_id=$hashedAdminId";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
    include("partials/navbar.php");
    ?>
    <div
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        See Your Friendlist🧾</div>
    <!-- card -->

    <div class="w-full drop-shadow-2xl flex flex-col justify-center items-center">

        <br>
        <img src="img/girl.png" alt="girl" class="h-64">
    </div>

    <div class="bg-slate-700 w-fit flex-auto mx-auto rounded-md m-1">
        <input type="text" value="<?php echo $resultUrl; ?>" id="urlField" readonly
            class="border-2 border-black p-1 sm:p-2" onselectstart=" return false;">
    </div>
    <div class="w-fit mx-auto mb-24">
        <!-- Use copyUrl() function on button click -->
        <button onclick="redirectToSlambook()" id="btn"
            class="rounded-lg bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black p-1 m-2 sm:m-4 text-xl">Slambook</button>

    </div>
    <script>

        function copyUrl() {
            var urlField = document.getElementById("urlField");
            urlField.select();
            document.execCommand("copy");
            alert("URL copied to clipboard!");
        }

        function redirectToSlambook() {
            var urlField = document.getElementById("urlField").value;
            window.location.href = urlField;
        }


    </script>
    <?php include 'partials/footer.php' ?>

</body>

</html>
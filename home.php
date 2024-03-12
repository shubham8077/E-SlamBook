<?php
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin_id']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}
$adminId = $_SESSION["admin_id"];
$hashedAdminId = hash('sha256', $adminId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slambook link</title>
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

    <!-- card -->
    <div
        class="text-xl lg:text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Welcome -
        <?php echo $_SESSION['adminname'] ?>
    </div>
    <div class="w-full drop-shadow-2xl flex flex-col items-center">

        <img src="img/boy.png" alt="boy" class="h-64">
        <div class="text-center">
            <p class="text-gray-800 bg-slate-200 outline-2 outline-black font-bold text-xl lg:text-4xl p-2 m-2">Your
                Slambook link is ready.</p>
        </div>

    </div>


    <div class="w-fit flex-auto mx-auto rounded-md m-1">

        <input type="text" value="<?php echo "http://localhost/Slambook/usignup.php?admin_id=$hashedAdminId;" ?>"
            id="urlField" readonly class="rounded-md border-2 border-black p-1 m-1 sm:p-2">

        <button onclick="copyUrl()"
            class="rounded-lg bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black p-1 m-2 text-xl">Copy</button>
    </div>
    <script>
        function copyUrl() {
            var urlField = document.getElementById("urlField");
            urlField.select();
            document.execCommand("copy");
            alert("URL copied to clipboard!");
        }
    </script>

    <p class="text-center text-white font-bold">Now,Share link in below social media platforms...</p>
    <div class="flex justify-center mb-24">
        <div class="m-3"><a href="https://www.facebook.com/facebook/"><img
                    src="https://cdn.pixabay.com/photo/2017/06/22/06/22/facebook-2429746_1280.png"
                    class="w-12 hover:scale-90 rounded-md saturate-200"></a>
        </div>
        <div class="m-3"><a href="https://www.instagram.com/instagram/"><img
                    src="https://cdn.pixabay.com/photo/2016/09/17/07/03/instagram-1675670_1280.png"
                    class="w-12 hover:scale-90 rounded-md saturate-200"></a>
        </div>
        <div class="m-3"><a href="https://web.telegram.org/k/"><img
                    src="https://cdn.pixabay.com/photo/2021/05/04/11/13/telegram-6228343_1280.png"
                    class="w-12 hover:scale-90 rounded-md saturate-200"></a>
        </div>
        <div class="m-3"><a href="https://www.whatsapp.com/"><img
                    src="https://cdn.pixabay.com/photo/2016/08/27/03/07/whatsapp-1623579_1280.png"
                    class="w-12 hover:scale-90 rounded-md saturate-200"></a>
        </div>
    </div>
    <?php include 'partials/footer.php' ?>

</body>

</html>
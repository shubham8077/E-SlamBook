<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About E-Slambook</title>
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

    <?php include "partials/unavbar.php"; ?>

    <div class="w-full h-screen flex justify-center items-center">
        <div class="text-center">
            <div class="flex flex-row justify-center">
                <img src="img/readb.png" alt="readb" class="h-16">
                <p class="text-6xl font-extrabold text-white mb-4">E-Slambook </p><img src="img/readg.png" alt="readg"
                    class="h-16">
            </div>
            <p class="text-2xl font-bold mb-4">Digital way to keep memories...</p>

            <p class="text-lg text-white font-medium p-2 border-2 border-dashed m-2">
                Welcome to E-Slambook, the digital platform that revolutionizes the way you capture and cherish
                memories.
                Our E-Slambook application provides a modern and convenient way to create and share slambooks with your
                friends. It's a digital space where you can reminisce about the good times, share personal insights,
                and stay connected with your friends through the memories you've created together.
            </p>

            <p class="text-lg text-white mt-4">
                Start filling out your digital slambook today and embark on a journey of nostalgia with E-Slambook!
            </p>
        </div>
    </div>
    <?php include 'partials/footer.php' ?>


</body>

</html>
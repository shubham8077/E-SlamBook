<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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

    <div class="h-screen w-full drop-shadow-2xl flex justify-center items-center">

        <div class="w-72 sm:w-96 h-72 sm:h-80 bg-slate-200 mx-auto rounded-xl shadow-lg relative">
            <div class=" text-center font-extrabold text-xl text-black m-2">
                Thanks for sharing your memories with me...</div>
            <img src="img/thanks.png" alt="signup image" class="w-48 mt-2 mb-0 mx-auto">


            <button type="submit" name="btn"
                class="rounded-lg bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black p-1 m-4 text-xl w-11/12 absolute bottom-0 left-0">
                <a href="signup.php"> Create
                    your own slambook</a></button>

        </div>

    </div>
    <?php include 'partials/footer.php'; ?>

</body>

</html>
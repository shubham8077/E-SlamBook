<!DOCTYPE html>
<html lang="en">
<!-- https://cdn.pixabay.com/photo/2018/11/29/21/51/social-media-3846597_1280.png -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Slambook</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="/index.css">
    <script src="tailwind.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="_favicon_/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="_favicon_/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="_favicon_/favicon-16x16.png">
    <link rel="manifest" href="_favicon_/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body class="bg-cover bg-center"
    style="background-image: url('https://cdn.pixabay.com/photo/2023/05/02/15/52/support-7965543_1280.jpg'); height:100vh;">
    <?php
    include("partials/navbar.php");
    ?>
    <div class="container mx-auto mt-8 text-center">
        <h1 class="text-4xl sm:text-5xl md:text-6xl text-yellow-100 font-bold mb-4">Digital Slambook</h1>
        <p class="text-xl sm:text-2xl md:text-3xl text-white mb-8">Capture memories and messages from your friends in a
            modern way!</p>

        <div
            class="bg-orange-100 mx-auto font-bold rounded-md sm:rounded-sm p-2 sm:p-4 flex flex-col sm:flex-row items-center opacity-80">

            <div class="mb-4 sm:mr-4 sm:mb-0">
                <p>
                    🎉 **Welcome to Our Digital Slambook!** 🎉 <br>
                    Hey there! Ever wanted to cherish the sweet memories and messages from your friends in a modern way?
                    Well, you're in
                    the right place! Our Digital Slambook is here to capture those awesome moments and heartfelt notes
                    from your
                    pals in a whole new, digital way.
                </p>
            </div>

            <div class="mb-4 sm:mr-4 sm:mb-0">
                <p>
                    📚 **What's a Digital Slambook?** <br>
                    Think of it as a virtual time capsule filled with memories! Instead of passing around a physical
                    book, your
                    friends can share their thoughts, wishes, and funny anecdotes with you online. It's a fun and
                    interactive way
                    to celebrate your friendship journey.
                </p>
            </div>

            <div>
                <p>
                    💬 **How Does It Work?** <br>
                    Just hit the "Let's Get Started" button below, and you'll be on your way to creating your very own
                    digital
                    slambook. Invite your friends, ask them to share their thoughts, and watch your slambook come to
                    life! <br>
                    Ready to dive into the world of memories?
                </p>
            </div>

        </div>

        <button class="bg-blue-500 mt-4 sm:mt-8 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <a href="login.php">Let's Get Started</a>
        </button>
    </div>


</body>

</html>
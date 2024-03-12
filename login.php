<?php

session_start();

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/dbcon.php';

    $email = $_POST["email"];
    $password = $_POST["password"];
    $adminname = $_POST["name"];
    // $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM usersb WHERE adminname = '$adminname' AND email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($password, $row["password"])) {
            // Password is correct, set session variables and redirect to home page
            $_SESSION["admin_id"] = $row["admin_id"];
            $_SESSION['loggedin'] = true;
            $_SESSION['adminname'] = $adminname;

            header("Location: home.php");
            exit();
        } else {
            $showError = "Invalid login credentials. Please try again.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    /*$sql = "Select * from usersb where username='$username' AND email='$email'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['url'] = $url;
                header("location: home.php");
                exit();
            } else {
                $showError = "Invalid Credentials";
            }
        }


    } else {
        $showError = "Invalid Credentials";
    }
*/

}
// $showError = "User not found. Signup!";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

    <!-- <body class="bg-cover bg-center"
    style="background-image: url('https://cdn.pixabay.com/photo/2018/01/29/13/03/internet-3116062_1280.jpg'); height:100vh;"> -->

    <?php
    include("partials/navbar.php");
    ?>

    <?php
    if ($login) {
        echo '
        <div class="alert text-center bg-lime-400 font-bold">You are logged in Successfully!</div>
        ';
    }
    ?>

    <?php
    if ($showError) {
        echo '
        <div class="alert text-center bg-red-500 font-bold">' . $showError . '
        </div>
        ';
    }
    ?>

    <!-- card -->

    <div class=" w-full drop-shadow-2xl flex justify-center items-center">
        <div><img src="img/smile.png" alt="signup image" class="w-40 mt-2 mb-0 mx-auto">

            <div class="w-72 sm:w-96 h-fit sm:h-fit bg-slate-200 mx-auto rounded-xl shadow-lg relative mb-4">
                <div class=" text-center font-extrabold text-xl text-black m-0">
                    Login</div>

                <form action="login.php" method="post">
                    <input type="text" name="name" placeholder="Enter your name"
                        class="bg-transparent border-t-2 border-r-2 border-black p-2 m-4 mb-0 w-11/12 placeholder-black"
                        required>

                    <input type="email" name="email" placeholder="Enter your email"
                        class="bg-transparent border-t-2 border-l-2 border-black p-2 m-4 mb-0 mt-0 w-11/12 placeholder-black"
                        required>

                    <input type="password" name="password" placeholder="Enter password"
                        class="bg-transparent border-y-2 border-r-2 border-black p-2 m-4 mt-0 mb-0 w-11/12 placeholder-black"
                        required>

                    <button type="submit" name="btn" onclick="playClickSound()"
                        class="rounded-lg bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black p-1 m-3 sm:m-4 text-xl w-11/12 ">Login
                    </button>
                    <p class="text-base text-center font-medium">Don't have an account?<a href="signup.php"
                            class="text-cyan-400 font-bold">
                            Sign
                            up</a></p>
                </form>

            </div>
        </div>
    </div>

    <audio id="loginBtnSound" src="a6.mp3"></audio>
</body>
<script>
    function playClickSound() {
        // Play the click sound
        loginBtnSound.play();
    }
</script>

</html>
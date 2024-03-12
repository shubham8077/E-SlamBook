<?php

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/dbcon.php';

    $adminname = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $exists = false;

    if ($password == $cpassword) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `usersb` (`adminname`, `email`, `password`, `date`) VALUES ('$adminname', '$email', '$hash', current_timestamp())";

        if ($conn->query($sql) === TRUE) {

            header("Location: login.php");
            exit();
        } else {
            echo "Error registering admin: " . $conn->error;
        }
    } else {
        $showError = "Passwords Not Matching!";
    }
}


?>


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
    <script>

        function validate() {
            var isNameValid = validateName();
            var isEmailValid = validateEmail();
            var isPasswordValid = validatePassword();

            // Return true only if all validations pass
            return isNameValid && isEmailValid && isPasswordValid;
        }

        // Name validation
        function validateName() {
            var nameInput = document.getElementById("name");
            var nameValue = nameInput.value;

            // Regular expression for a name with alphabets and space
            var nameRegex = /^[A-Za-z\s]+$/;

            if (!nameRegex.test(nameValue)) {
                alert("Please enter a valid name. It should contain only alphabets and spaces.");
                return false;
            }

            return true;
        }

        // Email validation

        function validateEmail() {
            var emailInput = document.getElementById("email");
            var emailValue = emailInput.value;

            // Regular expression for a valid email address
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailRegex.test(emailValue)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }

        //password validate
        function validatePassword() {
            var passwordInput = document.getElementById("password");
            var passwordValue = passwordInput.value;

            // Regular expression for a strong password
            // It requires at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordRegex.test(passwordValue)) {
                alert("Please enter a strong password. It should contain at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.");
                return false;
            }

            return true;
        }

    </script>
</head>

<body class="bg-gradient-to-r from-indigo-500 to-orange-400">


    <?php
    include("partials/navbar.php");
    ?>

    <?php
    if ($showAlert) {
        echo '
    <div class="alert text-center bg-lime-400 font-bold">Your Account Created Successfully!</div>
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

    <div class="w-full drop-shadow-2xl flex justify-center items-center">
        <div><img src="img/happy.png" alt="signup image" class="w-40 mt-2 mb-0 mx-auto">

            <div class="w-72 sm:w-96 h-fit sm:h-80 bg-slate-200 mx-auto rounded-xl shadow-lg relative mb-4">
                <div class=" text-center font-extrabold text-xl text-black m-0">
                    Sign Up</div>

                <form action="signup.php" method="post" onsubmit="return validate()">

                    <input type="text" name="name" id="name" placeholder="Enter your name"
                        class="bg-transparent border-t-2 border-l-2 border-black p-2 m-4 mb-0 w-11/12 placeholder-black"
                        required>

                    <input type="email" name="email" id="email" placeholder="Enter your email"
                        class="bg-transparent border-t-2 border-r-2 border-black p-2 m-4 mt-0 mb-0 w-11/12 placeholder-black"
                        required>

                    <input type="password" name="password" id="password" placeholder="Enter password"
                        class="bg-transparent border-t-2 border-l-2 border-black p-2 m-4 mt-0 mb-0 w-11/12 placeholder-black"
                        required>

                    <input type="password" name="cpassword" placeholder="Confirm password"
                        class="bg-transparent border-y-2 border-r-2 border-black p-2 m-4 mt-0 mb-0 w-11/12 placeholder-black"
                        required>

                    <button type="submit" name="btn"
                        class="rounded-lg bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black p-1 m-3 text-xl w-11/12">Sign
                        Up</button>
                    <p class="text-base text-center font-medium">Already have an account?<a href="login.php"
                            class="text-cyan-400 font-bold">
                            Log
                            in</a></p>
                </form>


            </div>
        </div>
    </div>

</body>

</html>
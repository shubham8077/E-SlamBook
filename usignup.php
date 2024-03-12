<?php
session_start();


$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/dbcon.php';

    $adminId = $_SESSION['admin_id'];
    $username = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $exists = false;

    if ($password == $cpassword) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user` (`admin_id`, `username`, `email`, `password`, `date`) VALUES ('$adminId', '$username', '$email', '$hash', current_timestamp())";

        if ($conn->query($sql) === TRUE) {
            // $userId = $conn->insert_id;
            // $_SESSION['user_id'] = $userId;

            //$adminId = $conn->insert_id;
            //session_start();
            // $_SESSION['admin_id'] = $adminId;
            //echo "Admin registered successfully with ID: " . $adminId . "<br>";
            // $url = "user.php?admin_id=" . urlencode($adminId);
            //$url = "login.php?admin_id=" . urlencode($adminId);
            //$_SESSION['url'] = $url;
            header("Location: ulogin.php");
            exit();
        } else {
            echo "Error registering admin: " . $conn->error;
        }
    } else {
        $showError = "Passwords Not Matching!";
    }
}


?>

<!-- Rest of your HTML code remains unchanged -->

<!-- ?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <link rel="stylesheet" href="/index.css">
    <script src="tailwind.js"></script>

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

        // Password validation
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
    <link rel="apple-touch-icon" sizes="180x180" href="_favicon_/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="_favicon_/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="_favicon_/favicon-16x16.png">
    <link rel="manifest" href="_favicon_/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body class="bg-gradient-to-r from-indigo-500 to-orange-400">

    <!-- <body class="bg-cover bg-center"
    style="background-image: url('https://cdn.pixabay.com/photo/2019/01/09/14/13/leaves-3923413_1280.jpg'); height: 100vh;"> -->

    <?php
    include("partials/unavbar.php");
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
        <div><img src="img/usignup.png" alt="signup image" class="w-40 mt-2 mb-0 mx-auto">

            <div class="w-72 sm:w-96 h-fit sm:h-80 bg-slate-200 mx-auto rounded-xl shadow-lg mb-4 relative">
                <div class=" text-center font-extrabold text-xl text-black m-0">
                    Sign Up</div>



                <form action="usignup.php" method="post" onsubmit="return validate()">

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
                    <p class="text-base text-center font-medium">Already have an account?<a href="ulogin.php"
                            class="text-cyan-400 font-bold">
                            Log
                            in</a></p>
                </form>


            </div>
        </div>
    </div>

</body>

</html>
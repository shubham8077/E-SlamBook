<?php
session_start();

if (!isset($_SESSION['uloggedin']) || $_SESSION['uloggedin'] != true || !isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hashedUserId = $_GET['user_id'];
    include 'partials/dbcon.php';
    $userId = $_SESSION['user_id'];

    // basic info
    $gender = $_POST["gender"];
    $rstatus = $_POST["rstatus"];
    $fname = $_POST["fname"];
    $nkname = $_POST["nkname"];
    $bday = $_POST["bday"];
    $job = $_POST["job"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    // $chkbox = $_POST["checkbox"];

    // fav
    $food = $_POST["food"];
    $sub = $_POST["sub"];
    $movie = $_POST["movie"];
    $actor = $_POST["actor"];
    $clr = $_POST["clr"];
    $place = $_POST["plce"];
    $sngr = $_POST["sngr"];
    $game = $_POST["game"];
    $hobby = $_POST["hoby"];

    // aspiration
    $fgoals = $_POST["fgoals"];
    $djob = $_POST["djob"];
    $blist = $_POST["blist"];

    //questions
    $qo = $_POST["qo"];
    $qt = $_POST["qt"];
    $qth = $_POST["qth"];

    $smedia = $_POST["smedia"];
    $userId1 = $userId2 = $userId3 = $userId4 = null;

    // Function to validate date format (YYYY-MM-DD)
    function validateDateFormat($date)
    {
        $dateFormat = 'Y-m-d';
        $dateObj = DateTime::createFromFormat($dateFormat, $date);

        // Check if the date is valid and user is at least 12 years old
        if ($dateObj && $dateObj->format($dateFormat) === $date) {
            $minAllowedDate = new DateTime('-12 years');

            if ($dateObj < $minAllowedDate) {
                return true; // Date is valid, and user is at least 12 years old
            } else {
                return false; // Date is valid, but user is less than 12 years old
            }
        } else {
            return false; // Invalid date format
        }
    }


    if (validateDateFormat($bday)) {
        // Convert the selected date to a DateTime object
        $selectedDateTime = new DateTime($bday);
        $selectedDateTime->modify('-12 years');

        // Get the current date
        $currentDateTime = new DateTime();

        // Compare the selected year with the current year
        if ($selectedDateTime->format('Y') > $currentDateTime->format('Y')) {
            echo "Please select a date from the past or the present.";
        } else {
            // Generate a unique file name for the uploaded image
            $imgFileName = uniqid('profile_img_') . '_' . time() . '.' . pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

            // Define the target directory for image upload
            $targetDir = 'profile_uploads/';

            // Move the uploaded image to the target directory
            $targetPath = $targetDir . $imgFileName;
            move_uploaded_file($_FILES['img']['tmp_name'], $targetPath);

            // Update the SQL query to use the image file name
            $sql = "INSERT INTO basicinfo (user_id, Photo, Gender, Relationship_status, Full_Name, Nick_Name, BirthDay, Class_Job, Email, Phone, Address) VALUES ('$userId', '$imgFileName', '$gender', '$rstatus', '$fname', '$nkname', '$bday', '$job', '$email', '$phone', '$address')";

            // $sql = "INSERT INTO basicinfo (user_id, Photo, Gender, Relationship_status, Full_Name, Nick_Name, BirthDay, Class_Job, Email, Phone, Address) VALUES ('$userId', '$imgUrl', '$gender', '$rstatus', '$fname', '$nkname', '$bday', '$job', '$email', '$phone', '$address')";

            $result = mysqli_query($conn, $sql);
            if ($result) {
                $userId1 = $conn->insert_id;
                $showAlert = true;
            } else {
                $showError = "Record not inserted!";
            }

            $personality = isset($_POST['checkbox']) ? $_POST['checkbox'] : [];
            // Implode checkbox values to store as a comma-separated string in the database
            $personalityString = implode(',', $personality);

            $sql = "INSERT INTO fav (u_id, Food, Subject, Movie, Actor_Actress, Color, Place, Singer, Game, Hobbies, Personality) VALUES ('$userId1', '$food', '$sub', '$movie', '$actor', '$clr', '$place', '$sngr', '$game', '$hobby','$personalityString')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $userId2 = $conn->insert_id;
                $showAlert = true;
            } else {
                $showError = "Error storing checkbox values in the database.";
            }

            $sql = "INSERT INTO aspiration (u_id, Future_goals, Dream_job, Bucket_list) VALUES ('$userId2', '$fgoals', '$djob', '$blist')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $userId3 = $conn->insert_id;
                $showAlert = true;
            } else {
                $showError = "Data not inserted!";
            }

            $sql = "INSERT INTO `questions` (`u_id`, `qone`, `qtwo`, `qthree`, `Social_media`) VALUES ('$userId3', '$qo', '$qt', '$qth', '$smedia')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $userId4 = $conn->insert_id;
                $showAlert = true;
                $_SESSION['userimg_id'] = $userId4;
            } else {
                $showError = "Error inserting data: " . mysqli_error($conn);
            }
            // Proceed with further processing or save the data to the database
            echo "Date validation successful. You can proceed with the form submission.";
        }
    } else {
        echo "Invalid date format.";
        // header("location: sbform.php");
    }


    // taken from here
    if ($showAlert) {
        header("Location: view.php?user_id=" . $userId);
        exit;
        // echo '<div class="alert text-center bg-lime-400 font-bold">Form Submitted Successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>slambook Form</title>
    <link rel="stylesheet" href="/index.css">
    <script src="tailwind.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="_favicon_/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="_favicon_/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="_favicon_/favicon-16x16.png">
    <link rel="manifest" href="_favicon_/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script>

        function preimg() {
            let img = document.getElementById("img");
            let imgpreview = document.getElementById("imgpreview");

            if (img.files && img.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    imgpreview.src = e.target.result;
                };

                reader.readAsDataURL(img.files[0]);
            } else {
                imgpreview.src = "";
            }
        }


        function validateCheckbox() {
            var checkboxes = document.getElementsByName("checkbox[]");
            var isChecked = false;

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    isChecked = true;
                    break;
                }
            }

            if (!isChecked) {
                alert("Please select at least one personality trait.");
                return false;
            }

            return true;
        }

        // Call all validations
        function validateForm() {
            // Call individual validation functions
            var isDateValid = validateDate();
            var isEmailValid = validateEmail();
            var isPhoneValid = validatePhone();
            var isCheckboxValid = validateCheckbox();

            // Return true only if all validations pass
            return isDateValid && isEmailValid && isPhoneValid && isCheckboxValid;
        }

        //  <!-- Date Validate -->

        function validateDate() {
            // Get the selected date from the input
            var selectedDate = new Date(document.getElementById("bday").value);

            // Calculate the minimum allowed birth year (current year - 12 years)
            var minAllowedYear = new Date().getFullYear() - 12;

            // Check if the selected year is greater than or equal to the minimum allowed year
            if (selectedDate.getFullYear() > minAllowedYear) {
                alert("You must be at least 12 years old. Please select a valid date of birth.");
                return false;
            }

            // If the validation passes, you can proceed with form submission or further processing
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

        // Phone validation

        function validatePhone() {
            // Get the entered phone number
            var phoneNumber = document.getElementById("phone").value;

            // Validate the phone number using a regular expression
            var phoneRegex = /^\d{10}$/;

            // Check if the entered phone number matches the regular expression
            if (!phoneRegex.test(phoneNumber)) {
                alert("Please enter a valid 10-digit phone number.");
                return false;
            }

            // If the validation passes, you can proceed with form submission or further processing
            return true;
        }
    </script>

</head>

<body class="bg-gradient-to-r from-indigo-500 to-orange-400">

    <?php include "partials/unavbar.php"; ?>

    <?php if (!empty($dateError)): ?>
        <div class="alert text-center bg-red-500 font-bold">
            <?php echo $dateError; ?>
        </div>
    <?php endif; ?>



    <?php if ($showError) {
        echo '
    <div class="alert text-center bg-red-500 font-bold">' .
            $showError .
            '
    </div>
';
    } ?>

    <!-- Main Content -->
    <!-- <div class="border-2 border-black rounded-xl m-2"> -->

    <form action="sbform.php" method="post" onsubmit="return validateForm()" enctype="multipart/form-data"
        class="mb-24">

        <!-- Basic Information -->

        <div class="border-2 border-black bg-gradient-to-r from-slate-300 to-slate-500 rounded-xl m-2 p-1">

            <div class="border-2 border-black bg-yellow-300 rounded-xl m-1 p-1">
                <p class="text-center text-xl font-bold m-1">All About Me</p>
            </div>
            <div>
                <img src="img/newbio.png" alt="Bio image" class="w-40 mt-2 mb-0 mx-auto">

                <hr class="mt-2">
                <div class="m-1 sm:m-4 sm:flex justify-evenly">


                    <div class="m-2">
                        <div class="w-full sm:m-2 sm:w-32"><img src="img/profile.png" id="imgpreview"
                                class="h-48 sm:h-44 w-40 block m-auto border-2 border-black">
                        </div>

                        <p class="text-lg font-bold p-1">Upload photo</p>
                        <input type="file" name="img" id="img" accept="image/*" onchange="preimg()"
                            class="w-full cursor-pointer" required>

                        <p class="text-lg font-bold p-1">Gender</p>
                        <label class="lg:font-semibold">
                            <input type="radio" name="gender" value="Male" required
                                class="mr-2 w-4 h-4 cursor-pointer">Male
                        </label>
                        <br>
                        <label class="lg:font-semibold">
                            <input type="radio" name="gender" value="Female" required
                                class="mr-2 w-4 h-4 cursor-pointer">Female
                        </label>
                        <br>
                        <label class="lg:font-semibold">
                            <input type="radio" name="gender" value="Other" required
                                class="mr-2 w-4 h-4 cursor-pointer">Other
                        </label>

                        <p class="text-lg font-bold p-1">Relationship Status</p>
                        <label class="lg:font-semibold">
                            <input type="radio" name="rstatus" value="Single" required
                                class="mr-2 w-4 h-4 cursor-pointer">Single
                        </label>
                        <br>
                        <label class="lg:font-semibold">
                            <input type="radio" name="rstatus" value="Married" required
                                class="mr-2 w-4 h-4 cursor-pointer">Married
                        </label>
                    </div>

                    <div class="sm:w-1/2">

                        <div class="m-2">

                            <p class="text-lg font-bold p-1">My name is</p>
                            <input type="text" name="fname" id="fname" placeholder="Enter full name"
                                class="w-full p-1 rounded-md" required>

                            <p class="text-lg font-bold p-1">Nickname</p>
                            <input type="text" name="nkname" id="nkname" placeholder="Enter Nickname"
                                class="w-full p-1 rounded-md" required>

                        </div>

                        <div class="m-2">
                            <p class="text-lg font-bold p-1">Birthday</p>
                            <input type="date" name="bday" id="bday" max="<?php echo date('Y-m-d'); ?>"
                                placeholder="Enter date of birth" class="w-full p-1 rounded-md" required>

                            <p class="text-lg font-bold p-1">Class/Job</p>
                            <input type="text" name="job" id="job" placeholder="Class/Occupation"
                                class="w-full p-1 rounded-md" required>

                        </div>

                        <div class="m-2">
                            <p class="text-lg font-bold p-1">Email</p>
                            <input type="email" name="email" id="email" placeholder="Enter your Email"
                                class="w-full p-1 rounded-md" required
                                pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">

                            <p class="text-lg font-bold p-1">Phone</p>
                            <input type="number" name="phone" id="phone" placeholder="Enter phone number"
                                class="w-full p-1 rounded-md" required pattern="\d{10}" minlength="10" maxlength="10">

                            <p class="text-lg font-bold p-1">Address</p>
                            <input type="text" name="address" id="address" placeholder="Enter your address"
                                class="w-full p-1 rounded-md" required>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- main content -->

        <div class="sm:flex w-full">

            <!-- Favourites -->

            <div class="border-2 border-black bg-gradient-to-r from-slate-300 to-slate-500 rounded-xl m-2 p-1 sm:w-1/2">

                <div class="border-2 border-black bg-yellow-300 rounded-xl m-1 p-1">
                    <p class="text-center text-xl font-bold m-1">My Favourites</p>
                </div>
                <div>
                    <img src="img/newlikes.png" alt="signup image" class="w-40 mt-2 mb-0 mx-auto">

                    <hr class="mt-2">

                    <label class="text-lg font-bold p-1">Food</label>
                    <br>
                    <input type="text" name="food" id="food" class="w-full p-1 rounded-md"
                        placeholder="Your favourite food name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Subject</label>
                    <br>
                    <input type="text" name="sub" id="sub" class="w-full p-1 rounded-md"
                        placeholder="Your favourite subject name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Movie</label>
                    <br>
                    <input type="text" name="movie" id="movie" class="w-full p-1 rounded-md"
                        placeholder="Your favourite Movie name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Actor/Actress</label>
                    <br>
                    <input type="text" name="actor" id="actor" class="w-full p-1 rounded-md"
                        placeholder="Your favourite Actor or Actress name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Colour</label>
                    <br>
                    <input type="text" name="clr" id="clr" class="w-full p-1 rounded-md"
                        placeholder="Your favourite Colour name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Place/Country</label>
                    <br>
                    <input type="text" name="plce" id="plce" class="w-full p-1 rounded-md"
                        placeholder="Your favourite Place name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Singer</label>
                    <br>
                    <input type="text" name="sngr" id="sngr" class="w-full p-1 rounded-md"
                        placeholder="Your favourite Singer name..." required>
                    <br>
                    <label class="text-lg font-bold p-1">Game/Sport</label>
                    <br>
                    <input type="text" name="game" id="game" class="w-full p-1 rounded-md"
                        placeholder="Your favourite Game or Sport name..." required>
                    <label class="text-lg font-bold p-1">Hobbies</label>
                    <br>
                    <input type="text" name="hoby" id="hoby" class="w-full p-1 rounded-md" placeholder="Your Hobbies..."
                        required>
                </div>
            </div>


            <!-- Personality -->

            <div class="border-2 border-black bg-gradient-to-r from-slate-300 to-slate-500 rounded-xl m-2 p-1 sm:w-1/2">

                <div class="border-2 border-black bg-yellow-300 rounded-xl m-1 p-1">
                    <p class="text-center text-xl font-bold m-1">My Personality</p>
                </div>
                <div>
                    <img src=" img/newpersonality.png" alt="personality image" class="w-40 mt-2 mb-0 mx-auto">
                    <hr class="mt-2">

                </div>


                <div class="flex-col flex-wrap items-center gap-4 m-2">
                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Creative" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Creative</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Confident" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Confident</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Loyal" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Loyal</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Caring" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Caring</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Open-minded" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Open-minded</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Intelligent" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Intelligent</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Optimistic" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Optimistic</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Curious" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Curious</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Humourous" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Humorous</label>
                    </div>

                    <div
                        class="rounded-lg overflow-hidden bg-white shadow-md p-2 border-2 border-black m-1 flex items-center">
                        <input type="checkbox" name="checkbox[]" value="Wise" class="mr-2 w-6 h-6">
                        <label class="text-lg font-bold">Wise</label>
                    </div>

                    <!-- Add similar blocks for other personality traits -->

                </div>

            </div>
        </div>
        <!-- </div> -->

        <!-- Aspirations -->

        <div class="border-2 border-black bg-gradient-to-r from-slate-300 to-slate-500 rounded-xl m-2 p-1">
            <div class="border-2 border-black bg-yellow-300 rounded-xl m-1 p-1">
                <p class="text-center text-xl font-bold m-1">Aspirations</p>
            </div>
            <p class="text-lg font-bold p-1">Future goals</p>
            <input type="text" name="fgoals" id="fgoals" class="w-full p-1 rounded-md"
                placeholder="What are your future goals?" required>

            <p class="text-lg font-bold p-1">Dream job</p>
            <input type="text" name="djob" id="djob" class="w-full p-1 rounded-md" placeholder="What is your dream job?"
                required>

            <p class="text-lg font-bold p-1">Bucket list</p>
            <textarea type="text" name="blist" id="blist" class="w-full p-1 rounded-md"
                placeholder="Your wishes or bucket-list" required></textarea>
        </div>

        <!-- Questions -->

        <div class="border-2 border-black bg-gradient-to-r from-slate-300 to-slate-500 rounded-xl m-2 p-1">
            <div class="border-2 border-black bg-yellow-300 rounded-xl m-1 p-1">
                <p class="text-center text-xl font-bold m-1">Questions</p>
            </div>
            <p class="text-lg font-bold p-1">If you got to choose your name, what would it be and why?</p>
            <textarea type="text" name="qo" id="qo" class="w-full p-1 rounded-md"></textarea>

            <p class="text-lg font-bold p-1">If you could be best friends with a character in any animated show,
                which would you choose?</p>
            <textarea type="text" name="qt" id="qt" class="w-full p-1 rounded-md"></textarea>

            <p class="text-lg font-bold p-1">If you could have one superpower,what would it be?</p>
            <textarea type="text" name="qth" id="qth" class="w-full p-1 rounded-md"></textarea>
        </div>

        <!-- Social Media -->

        <div class="border-2 border-black bg-gradient-to-r from-slate-300 to-slate-500 rounded-xl m-2 p-1">
            <p class="text-lg font-bold p-1">Social Media</p>
            <textarea type="textbox" name="smedia" id="smedia"
                placeholder="Share links of social media like Instagram, Facebook, etc..."
                class="w-full p-1 rounded-md"></textarea>
        </div>


        <!-- <div class="border-2 border-black bg-yellow-300 rounded-xl m-1 p-1">
        <textarea placeholder="What you think about me..." name="thoughts" class="p-1 w-full"></textarea>
    </div> -->

        <!-- Submit button -->

        <div class=" p-1">
            <button type="submit" name="btn"
                class="rounded-xl bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black shadow-lg shadow-black p-1 my-3 text-xl w-full mx-auto">SUBMIT</button>
        </div>


    </form>


    <?php include 'partials/footer.php' ?>


</body>

</html>
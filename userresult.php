<?php
session_start();

// Check if the admin ID is present in the URL
if (!isset($_SESSION['uloggedin']) || $_SESSION['uloggedin'] != true) {
    header("location: error.php");
    exit;
}
$hashedUserId = $_GET['userId'];
$userId = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="/index.css">
    <script src="tailwind.js"></script>
    <!-- Add Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <!-- Add Slick Carousel JS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="_favicon_/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="_favicon_/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="_favicon_/favicon-16x16.png">
    <link rel="manifest" href="_favicon_/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <style>
        .image-slider {
            width: 100%;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            /* Adjust the min and max width as needed */
            gap: 10px;
            /* Adjust the gap between images */
        }

        .image-item {
            width: 100%;
            /* Ensure each image item spans the full width of its grid cell */
            height: auto;
            /* Let the height adjust according to the image content */
        }

        .image-item img {
            max-width: 100%;
            /* Ensure the image doesn't exceed the width of its container */
            height: auto;
            /* Let the height adjust according to the image content */
        }
    </style>
    <script>
        function confirmDelete(userId) {
            const confirmation = confirm("Are you sure you want to delete this user?");
            if (confirmation) {
                window.location.href = "udelete.php?user_id=" + userId;
            }
        }
    </script>

</head>

<body class="bg-gradient-to-r from-indigo-500 to-orange-400 mb-24">
    <?php include 'partials/unavbar.php'; ?>

    <div
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Your Profile 📇</div>
    <?php
    try {
        $conn = new PDO("mysql:host=localhost;dbname=slambook", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("
            SELECT * FROM user
            LEFT JOIN basicinfo ON user.id = basicinfo.user_id
            LEFT JOIN fav ON basicinfo.id = fav.u_id
            LEFT JOIN aspiration ON basicinfo.id = aspiration.u_id
            LEFT JOIN questions ON aspiration.id = questions.u_id
            LEFT JOIN images ON questions.id = images.u_id
            WHERE user.id = :userId
        ");

        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            ?>

            <!-- <div class="border-2 border-black p-4 mb-4 bg-white rounded"> -->
            <?php if (!empty($userData['Full_Name'])): ?>

                <div class="container mx-auto mt-4 p-4 bg-white rounded-md drop-shadow-md mb-4"
                    style="background-image: url('img/pg.jpeg'); background-size: cover;">
                    <!-- Display images -->
                    <div class="flex">
                        <p class="toggle-button bg-cyan-400 text-xl lg:text-3xl text-center font-bold p-2 mb-2 rounded-md hover:bg-cyan-500 drop-shadow-md select-none w-full"
                            onclick="toggleInfo('<?php echo $userData['user_id']; ?>')">
                            <span class="user-name">
                                <?php echo $userData['Full_Name']; ?>
                            </span>
                        </p>

                    </div>

                <?php endif; ?>

                <div id="info_<?php echo $userData['user_id']; ?>" class="user-info"
                    style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;">
                    <div class="bfa lg:flex w-full justify-evenly">
                        <div
                            class="lg:w-4/12 m-1 p-1 rounded-lg bg-gradient-to-r from-indigo-600 to-orange-600 border-2 border-black">
                            <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-2">Basic Information</h2>

                            <hr>
                            <div class="m-2 w-fit border-2 bg-white border-black mx-auto">
                                <div class="m-2">
                                    <?php if (!empty($userData['Photo'])): ?>
                                        <img src="profile_uploads/<?php echo $userData['Photo']; ?>" alt="User Image"
                                            class="mt-4 w-full md:w-40 h-auto md:h-40 rounded border-2 border-black">
                                    <?php else:
                                        echo '<p>No photo available.</p>';
                                    endif; ?>
                                </div>
                            </div>

                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Full Name: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Full_Name']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Nick Name: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Nick_Name']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Gender: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Gender']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Relationship
                                    Status:
                                </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Relationship_status']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Birthday: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['BirthDay']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Class/Job: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Class_Job']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Email: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Email']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Phone: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Phone']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Address: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Address']; ?>
                                </p>
                            </div>
                        </div>
                        <!-- Add more fields as needed -->

                        <!-- Display additional information from other tables -->
                        <hr>
                        <div
                            class="lg:w-6/12 m-1 p-1 rounded-lg bg-gradient-to-r from-indigo-600 to-orange-600 border-2 border-black">
                            <!-- <div class="flex-wrap"> -->
                            <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-2">Favourites Information
                            </h2>
                            <!-- <div class="border-2 border-dashed border-red-500 lg:w-full p-1"> -->
                            <hr>

                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Food: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Food']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Subject: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Subject']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Movie: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Movie']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Actor/Actress: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Actor_Actress']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Color: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Color']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Place: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Place']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Singer: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Singer']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Game: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Game']; ?>
                                </p>
                            </div>
                            <div class="m-2 flex items-center">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Hobbies: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Hobbies']; ?>
                                </p>
                            </div>
                            <div class="overflow-hidden md:overflow-visible">

                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Personality: </p>
                                <p class="text-white text-xl break-words font-bold">
                                    <?php echo $userData['Personality']; ?>
                                </p>
                            </div>
                            <!-- </div> -->
                        </div>
                        <div
                            class="lg:w-3/12 m-1 p-1 rounded-lg bg-gradient-to-r from-indigo-600 to-orange-600 border-2 border-black">

                            <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-2">Aspiration</h2>
                            <hr>
                            <div class="m-2">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Future Goals: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Future_goals']; ?>
                                </p>
                            </div>
                            <div class="m-2">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Dream job: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Dream_job']; ?>
                                </p>
                            </div>
                            <div class="m-2">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Bucket list: </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['Bucket_list']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="fm w-full justify-evenly">
                        <div class="m-1 p-1 rounded-lg bg-gradient-to-r from-indigo-600 to-orange-600 border-2 border-black">

                            <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-1">Fun Questions</h2>
                            <hr>
                            <div class="m-2">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">If you got to choose your
                                    name,
                                    what would it be and why? </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['qone']; ?>
                                </p>
                            </div>
                            <div class="m-2">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">If you could be best friends
                                    with
                                    a
                                    character in any animated show,
                                    which would you choose? </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['qtwo']; ?>
                                </p>
                            </div>
                            <div class="m-2">
                                <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">If you could have one
                                    superpower,what would it be? </p>
                                <p class="text-white text-xl font-bold">
                                    <?php echo $userData['qthree']; ?>
                                </p>
                            </div>
                        </div>

                        <div>
                            <div
                                class="m-1 p-1 rounded-lg bg-gradient-to-r from-indigo-600 to-orange-600 border-2 border-black">

                                <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-1">Social Media</h2>
                                <hr>
                                <div class="m-2">
                                    <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Social Media:</p>
                                    <p class="text-white text-xl font-bold">
                                        <?php echo $userData['Social_media']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="bg-black pt-1 mt-1 rounded-t-lg">
                        <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-2">Gallery</h2>
                        <div class="image-grid" id="grid_<?php echo $userData['user_id']; ?>">
                            <?php
                            // Fetch images for the current user ID
                            $stmtImages = $conn->prepare("SELECT * FROM images WHERE u_id = :userId");
                            $stmtImages->bindParam(':userId', $userData['user_id']);
                            $stmtImages->execute();
                            $userImages = $stmtImages->fetchAll(PDO::FETCH_ASSOC);

                            // Display images for the current user
                            foreach ($userImages as $image) {
                                ?>
                                <div class="image-item">
                                    <img src="uploads/<?php echo $image['image_url']; ?>" alt="Gallery Image"
                                        class="rounded-lg mx-auto border-4 border-double border-black m-4">
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>


            </div>
            <?php


        } else {
            echo '<p class="bg-white rounded-lg p-1 m-2 text-3xl font-bold text-center">No friends added☹️</p>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
    ?>

    <script>
        function toggleInfo(userId) {
            const userInfo = document.getElementById('info_' + userId);
            userInfo.style.maxHeight = userInfo.style.maxHeight === "0px" ? userInfo.scrollHeight + "px" : "0";
        }
    </script>
    <div class=" p-1">
        <a href="thanks.php"><button type="submit" name="btn"
                class="rounded-xl bg-cyan-400 text-center font-bold border-2 border-black  hover:bg-cyan-500 text-black shadow-lg shadow-black p-1 my-3 text-xl w-full mx-auto">Done</button></a>
    </div>
    <?php include 'partials/footer.php' ?>


</body>

</html>
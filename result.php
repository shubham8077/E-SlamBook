<?php
session_start();

// Check if the admin ID is present in the URL
if (!isset($_GET['admin_id']) || !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: error.php");
    exit;
}

$hashedAdminId = $_GET['admin_id'];

// Verify the admin ID by hashing the stored admin ID
$isValidAdminId = hash_equals(hash('sha256', $_SESSION['admin_id']), $hashedAdminId);

if (!$isValidAdminId) {
    // Invalid admin ID, redirect to error page
    header("location: error.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
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
    <?php include 'partials/navbar.php'; ?>

    <div
        class="text-3xl flex justify-center mx-auto w-full font-bold text-center text-white bg-gradient-to-r from-green-400 via-cyan-600 to-green-400 p-1">
        Friends<img src="img/frnds.png" class="w-10 h-10"> </div>
    <?php
    try {
        // Establish a database connection
        $conn = new PDO("mysql:host=localhost;dbname=slambook", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Admin ID (change this based on the logged-in admin)
        $adminId = $_SESSION['admin_id'];

        // Fetch user data related to the admin using JOIN
        $stmt = $conn->prepare("
            SELECT DISTINCT user.*, basicinfo.*, fav.*, aspiration.*, questions.*, images.*
            FROM user
            LEFT JOIN basicinfo ON user.id = basicinfo.user_id
            LEFT JOIN fav ON basicinfo.id = fav.u_id
            LEFT JOIN aspiration ON basicinfo.id = aspiration.u_id
            LEFT JOIN questions ON aspiration.id = questions.u_id
            LEFT JOIN images ON questions.id = images.u_id
            WHERE user.admin_id = :adminId
        ");

        $stmt->bindParam(':adminId', $adminId);
        $stmt->execute();

        // Fetch the result as an associative array
        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Keep track of displayed user IDs
        $displayedUserIds = [];

        if ($userData) {
            // Display user information
            foreach ($userData as $user) {
                // Check if the user ID has already been displayed
                if (!in_array($user['user_id'], $displayedUserIds)) {
                    ?>
                    <!-- <div class="border-2 border-black p-4 mb-4 bg-white rounded"> -->
                    <?php if (!empty($user['Full_Name'])): ?>

                        <div class="container mx-auto mt-4 p-4 bg-white rounded-md drop-shadow-md mb-4"
                            style="background-image: url('img/pg.jpeg'); background-size: cover;">
                            <!-- Display images -->
                            <div class="flex">
                                <p class="toggle-button bg-cyan-400 text-xl lg:text-3xl text-center font-bold p-2 mb-2 rounded-md hover:bg-cyan-500 drop-shadow-md select-none w-10/12"
                                    onclick="toggleInfo('<?php echo $user['user_id']; ?>')">
                                    <span class="user-name">
                                        <?php echo $user['Full_Name']; ?>
                                    </span>
                                </p>
                                <a class="download-btn" href="download.php?user_id=<?php echo $user['user_id']; ?>"><button class="rounded-lg bg-green-500 text-center font-bold flex border-2 border-black hover:bg-green-700 hover:text-white p-1
                            m-2 ml-4 mr-0 text-xl">Download</button></a>
                                <button class="rounded-lg bg-red-500 text-center font-bold flex justify-end border-2 border-black hover:bg-red-700 hover:text-white p-1
                            m-2 ml-4 mr-0 text-xl"
                                    onclick="confirmDelete('<?php echo $user['user_id']; ?>')">Delete</button>
                            </div>

                        <?php endif; ?>

                        <div id="info_<?php echo $user['user_id']; ?>" class="user-info"
                            style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out;">
                            <div class="bfa lg:flex w-full justify-evenly">
                                <div
                                    class="lg:w-4/12 m-1 p-1 rounded-lg bg-gradient-to-r from-indigo-600 to-orange-600 border-2 border-black">
                                    <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-2">Basic Information</h2>

                                    <hr>
                                    <div class="m-2 w-fit border-2 bg-white border-black mx-auto">
                                        <div class="m-2">
                                            <?php if (!empty($user['Photo'])): ?>
                                                <img src="profile_uploads/<?php echo $user['Photo']; ?>" alt="User Image"
                                                    class="mt-4 w-full md:w-40 h-auto md:h-40 rounded border-2 border-black">
                                            <?php else:
                                                echo '<p>No photo available.</p>';
                                            endif; ?>
                                        </div>
                                    </div>

                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Full Name: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Full_Name']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Nick Name: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Nick_Name']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Gender: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Gender']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Relationship
                                            Status:
                                        </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Relationship_status']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Birthday: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['BirthDay']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Class/Job: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Class_Job']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Email: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Email']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Phone: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Phone']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Address: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Address']; ?>
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
                                            <?php echo $user['Food']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Subject: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Subject']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Movie: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Movie']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Actor/Actress: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Actor_Actress']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Color: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Color']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Place: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Place']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Singer: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Singer']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Game: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Game']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2 flex items-center">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Hobbies: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Hobbies']; ?>
                                        </p>
                                    </div>
                                    <div class="overflow-hidden md:overflow-visible">

                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Personality: </p>
                                        <p class="text-white text-xl break-words font-bold">
                                            <?php echo $user['Personality']; ?>
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
                                            <?php echo $user['Future_goals']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Dream job: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Dream_job']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">Bucket list: </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['Bucket_list']; ?>
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
                                            <?php echo $user['qone']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">If you could be best friends
                                            with
                                            a
                                            character in any animated show,
                                            which would you choose? </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['qtwo']; ?>
                                        </p>
                                    </div>
                                    <div class="m-2">
                                        <p class="text-yellow-400 p-1 text-xl bg-opacity-50 font-bold">If you could have one
                                            superpower,what would it be? </p>
                                        <p class="text-white text-xl font-bold">
                                            <?php echo $user['qthree']; ?>
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
                                                <?php echo $user['Social_media']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="bg-black pt-1 mt-1 rounded-t-lg">
                                <h2 class="text-2xl font-bold text-center bg-yellow-400 rounded-lg m-2">Gallery</h2>
                                <div class="image-grid" id="grid_<?php echo $user['user_id']; ?>">
                                    <?php
                                    // Fetch images for the current user ID
                                    $stmtImages = $conn->prepare("SELECT * FROM images WHERE u_id = :userId");
                                    $stmtImages->bindParam(':userId', $user['user_id']);
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
                    <?php
                    // Add the displayed user ID to the array
                    $displayedUserIds[] = $user['user_id'];
                    ?>

                    </div>
                    <?php
                }
            }
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
    <?php include 'partials/footer.php' ?>


</body>

</html>
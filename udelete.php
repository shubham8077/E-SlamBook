<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: error.php");
    exit;
}

if (isset($_GET['user_id'])) {
    $userIdToDelete = $_GET['user_id'];

    try {
        // Establish a database connection
        $conn = new PDO("mysql:host=localhost;dbname=slambook", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Perform the necessary DELETE queries for user data in different tables
        $deleteUserImages = $conn->prepare("DELETE FROM images WHERE u_id = :userId");
        $deleteUserImages->bindParam(':userId', $userIdToDelete);
        $deleteUserImages->execute();

        $deleteUserAspiration = $conn->prepare("DELETE FROM aspiration WHERE u_id = :userId");
        $deleteUserAspiration->bindParam(':userId', $userIdToDelete);
        $deleteUserAspiration->execute();

        $deleteUserBasicInfo = $conn->prepare("DELETE FROM basicinfo WHERE user_id = :userId");
        $deleteUserBasicInfo->bindParam(':userId', $userIdToDelete);
        $deleteUserBasicInfo->execute();

        $deleteUserFav = $conn->prepare("DELETE FROM fav WHERE u_id = :userId");
        $deleteUserFav->bindParam(':userId', $userIdToDelete);
        $deleteUserFav->execute();

        $deleteUserQuestions = $conn->prepare("DELETE FROM questions WHERE u_id = :userId");
        $deleteUserQuestions->bindParam(':userId', $userIdToDelete);
        $deleteUserQuestions->execute();

        // Add more DELETE queries for other tables as needed...

        // Redirect to the friends page after deletion
        $adminId = $_SESSION['admin_id'];
        $hashedAdminId = hash('sha256', $adminId);
        header("location: result.php?admin_id=" . $hashedAdminId);
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}
?>
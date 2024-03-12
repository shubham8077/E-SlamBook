<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uloggedin']) || $_SESSION['uloggedin'] != true) {
    header("location: error.php");
    exit;
}

// Include database connection
include "partials/dbcon.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission

    // Retrieve form data
    $userId = $_POST['userId']; // Assuming you have a hidden input field in the form to store the user ID
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    // Add more fields as needed

    // Update user information in the database
    try {
        $sql = $conn->prepare("UPDATE user SET Full_Name = :fullName, Email = :email WHERE id = :userId");
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT); // Assuming user ID is an integer
        $stmt->execute();

        // Redirect to the view page after successful update
        header("location: userresult.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// If not a POST request or form not submitted, redirect to view.php
header("location: userresult.php");
exit;
?>
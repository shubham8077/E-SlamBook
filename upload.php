<?php
session_start();

if (!isset($_SESSION['uloggedin']) || $_SESSION['uloggedin'] !== true || !isset($_SESSION['userimg_id'])) {
    header("location: login.php");
    exit;
}

if (isset($_POST['submit']) && isset($_FILES['my_image'])) {
    include 'partials/dbcon.php';

    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];
    $userId = $_SESSION['userimg_id'];

    if ($error === 0) {
        if ($img_size > 2000000) {
            $em = "Sorry, your file is too large.";
            header("location: view.php?error=$em");
            exit;
        }

        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'uploads/' . $new_img_name;

            if (move_uploaded_file($tmp_name, $img_upload_path)) {
                // Insert into the database
                $sql = "INSERT INTO images (u_id, image_url) VALUES ('$userId', '$new_img_name')";
                if (mysqli_query($conn, $sql)) {
                    header("location: view.php");
                    exit;
                } else {
                    $em = "Error inserting data into the database.";
                    header("location: view.php?error=$em");
                    exit;
                }
            } else {
                $em = "Error moving the uploaded file.";
                header("location: view.php?error=$em");
                exit;
            }
        } else {
            $em = "You can't upload files of this type.";
            header("location: view.php?error=$em");
            exit;
        }
    } else {
        $em = "Unknown error occurred!";
        header("location: view.php?error=$em");
        exit;
    }
} else {
    header("location: view.php");
    exit;
}
?>
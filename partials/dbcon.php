<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "slambook";
$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("connection unsuccessful");
}
?>
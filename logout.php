<?php
session_start();
// Destroy the session.
session_destroy();
// Redirect to login page after logout
header("location: login.php");
exit;
?>
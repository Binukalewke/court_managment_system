<?php
session_start();

// Debugging: Check if session is working
// if (!isset($_SESSION['user_id'])) {
//     die("Error: No user_id in session. Are you logged in?");
// }

// Debugging: Print user_id
// echo "DEBUG: user_id = " . $_SESSION['role_id'] . "<br>";

// Determine redirect path
$redirectPath = ($_SESSION['role_id'] == 1) 
    ? '/KMSC/pages/admin/login.php' 
    : '/KMSC/pages/login.php';

// Debug test v1
// echo "DEBUG: Redirecting to: " . $redirectPath . "<br>";

// Destroy session
session_unset();
session_destroy();

// Redirect to the location based on the user
header("Location: $redirectPath");
exit;
?>
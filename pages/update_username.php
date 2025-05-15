<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /KMSC/pages/login.php');
    exit;
}

// Validate and sanitize input
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

// Connect to the database
$conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Update username
$stmt = $conn->prepare("UPDATE users SET username = ? WHERE userID = ?");
$stmt->bind_param('si', $username, $_SESSION['user_id']);
if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['success'] = 'Username updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update username. Please try again.';
}

// Close the connection
$stmt->close();
$conn->close();

header('Location: /KMSC/pages/dashboard.php');
exit;
?>
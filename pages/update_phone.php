<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /KMSC/pages/login.php');
    exit;
}

// Validate and sanitize input
$phone = htmlspecialchars(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));

// Validate phone number (10 digits)
if (!preg_match('/^\d{10}$/', $phone)) {
    $_SESSION['error'] = 'Invalid phone number. It must contain exactly 10 digits.';
    header('Location: /KMSC/pages/dashboard.php');
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Update phone number
$stmt = $conn->prepare("UPDATE users SET phone = ? WHERE userID = ?");
$stmt->bind_param('si', $phone, $_SESSION['user_id']);
if ($stmt->execute()) {
    $_SESSION['phone'] = $phone;
    $_SESSION['success'] = 'Phone number updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update phone number. Please try again.';
}

// Close the connection
$stmt->close();
$conn->close();

header('Location: /KMSC/pages/dashboard.php');
exit;
?>
<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /KMSC/pages/login.php');
    exit;
}

// Validate and sanitize input
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$confirmNewPassword = $_POST['confirmNewPassword'];

// Check if new passwords match
if ($newPassword !== $confirmNewPassword) {
    $_SESSION['error'] = 'New passwords do not match.';
    header('Location: /KMSC/pages/dashboard.php');
    exit;
}

// Validate new password (minimum 8 characters, at least one uppercase, one lowercase, and one number)
if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $newPassword)) {
    $_SESSION['error'] = 'New password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.';
    header('Location: /KMSC/pages/dashboard.php');
    exit;
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch the current password hash from the database
$stmt = $conn->prepare("SELECT passwordHash FROM users WHERE userID = ?");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verify the current password
if (!password_verify($currentPassword, $user['passwordHash'])) {
    $_SESSION['error'] = 'Current password is incorrect.';
    header('Location: /KMSC/pages/dashboard.php');
    exit;
}

// Hash the new password
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the password in the database
$stmt = $conn->prepare("UPDATE users SET passwordHash = ? WHERE userID = ?");
$stmt->bind_param('si', $newPasswordHash, $_SESSION['user_id']);
if ($stmt->execute()) {
    $_SESSION['success'] = 'Password changed successfully.';
} else {
    $_SESSION['error'] = 'Failed to change password. Please try again.';
}

// Close the connection
$stmt->close();
$conn->close();

header('Location: /KMSC/pages/dashboard.php');
exit;
?>
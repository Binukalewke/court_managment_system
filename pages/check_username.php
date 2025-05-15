<?php
require_once '../includes/config.php'; // Your database connection file

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    
    // Connect to database
    $conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
    if ($conn->connect_error) {
        die(json_encode(['available' => false, 'error' => 'Database connection failed']));
    }
    
    // Check if username exists (excluding current user's username)
    $stmt = $conn->prepare("SELECT userID FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode(['available' => $result->num_rows === 0]);
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['available' => false]);
}
?>
<?php
require_once __DIR__ . '/../dbh.inc.php';

function validateLogin($email, $password) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        return ['success' => false, 'message' => 'User not found'];
    }

    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid credentials'];
    }

    return ['success' => true, 'user_id' => $user['id'], 'role' => $user['role']];
}

<?php
session_start(); // Start the session

// Redirect to the registration page if no verification code is set
if (!isset($_SESSION['verification_code'])) {
    header('Location: /KMSC/pages/register.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userCode = $_POST['verification_code'];

    // Compare the entered code with the stored code
    if ($userCode === $_SESSION['verification_code']) {
        // Code is correct, save the user to the database
        $conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $username = $_SESSION['temp_user']['username'];
        $passwordHash = $_SESSION['temp_user']['password'];
        $fullName = $_SESSION['temp_user']['fullName'];
        $email = $_SESSION['temp_user']['email'];
        $phone = $_SESSION['temp_user']['phone'];

        // Insert the user into the database
        $stmt = $conn->prepare('INSERT INTO Users (Username, PasswordHash, FullName, Email, Phone, RoleID) VALUES (?, ?, ?, ?, ?, 2)');
        $stmt->bind_param('sssss', $username, $passwordHash, $fullName, $email, $phone);

        if ($stmt->execute()) {
            // Registration successful, log the user in
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['role_id'] = 2;

            // Clear temporary session data
            unset($_SESSION['verification_code']);
            unset($_SESSION['verification_code_expiry']);
            unset($_SESSION['temp_user']);

            // Redirect to the dashboard
            header('Location: /KMSC/pages/dashboard.php');
            exit;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }

        $stmt->close();
        $conn->close();
    } else {
        $errors[] = 'Invalid verification code.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-center mb-6">Verify Email</h2>

            <!-- Display Success Message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message px-4 py-3 rounded mb-6 bg-green-100 border border-green-400 text-green-700">
                    <p><?php echo $_SESSION['success']; ?></p>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Display Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="error-message px-4 py-3 rounded mb-6 bg-red-100 border border-red-400 text-red-700">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/KMSC/pages/verify.php" method="POST">
                <!-- Verification Code -->
                <div class="mb-6">
                    <label for="verification_code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                    <input type="text" id="verification_code" name="verification_code" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-red-600 text-white w-full py-2 rounded-md hover:bg-red-500 transition duration-300 text-center mb-2">Verify</button>
                </div>
            </form>

            <!-- Resend Code Button -->
            <div class="text-center">
                <form action="/KMSC/pages/resend-code.php" method="POST">
                    <button type="submit" class="resend-code-button w-full py-2 rounded-md text-center">Resend Code</button>
                </form>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
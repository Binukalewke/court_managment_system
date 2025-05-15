<?php
session_start(); // Start the session

// Redirect to dashboard if the user is already logged in
if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 1) {
    header('Location: /KMSC/pages/admin/dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Initialize error array
    $errors = [];

    // Validate Username
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, hyphens, and underscores.';
    }

    // Validate Password
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number.';
    } elseif (strpos($password, ' ') !== false) {
        $errors[] = 'Password cannot contain spaces.';
    }

    // If no validation errors, proceed with database operations
    if (empty($errors)) {
        // Connect to the database
        $conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Fetch user from the database (only for RoleID = 2)
        $stmt = $conn->prepare('SELECT UserID, Username, PasswordHash, RoleID FROM Users WHERE (Username = ? OR Email = ?) AND RoleID = 1');
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['PasswordHash'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role_id'] = $user['RoleID'];

                // Redirect to dashboard
                header('Location: /KMSC/pages/admin/dashboard.php');
                exit;
            } else {
                $errors[] = 'Invalid password.';
            }
        } else {
            $errors[] = 'Oops! We couldn\'t find an account with that username';
        }

        $stmt->close();
        $conn->close();
    }

    // Store errors in session if any
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = ['username' => $username]; // Save username for repopulation
        header('Location: /KMSC/pages/admin/login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="../../assets/css/output.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto px-4 py-12 flex items-center justify-center min-h-screen">
        <div class="max-w-lg w-full mx-auto bg-white p-8 rounded-lg shadow-lg">
            <!-- Card Header -->
            <div class="bg-black py-5 px-6 rounded-lg mb-6 text-center">
                <h2 class="text-3xl font-bold text-white">Management Portal</h2>
                <p class="text-xs text-red-600 mt-2">
                    Authorized personnel only
                </p>
            </div>

            <!-- Display Error Messages -->
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="error-message px-4 py-3 rounded mb-6" id="error-message">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); // Clear the errors after displaying them ?>
            <?php endif; ?>

            <form action="/KMSC/pages/admin/login.php" method="POST" id="loginForm">
                <!-- Username -->
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" 
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>"
                           required>
                    <span id="username-error" class="text-sm text-red-600 hidden">Username can only contain letters, numbers, hyphens, and underscores.</span>
                    <?php unset($_SESSION['form_data']); ?>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           required>
                    <span id="password-error" class="text-sm text-red-600 hidden">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</span>
                </div>

                <!-- Keep Me Logged In -->
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="keep_logged_in" name="keep_logged_in" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="keep_logged_in" class="ml-2 text-sm text-gray-700">Keep me logged in</label>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-red-600 text-white w-full py-2 rounded-md hover:bg-red-500 transition duration-300 text-center">Login</button>
                </div>
            </form>
        </div>
    </main>
    <script src="../../assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Username Validation
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('username-error');
            
            // Password Validation
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('password-error');
            
            // Function to validate username
            function validateUsername() {
                const username = usernameInput.value;
                if (username === '') {
                    usernameError.classList.add('hidden');
                    usernameInput.classList.remove('border-red-600');
                    usernameInput.classList.add('border-gray-300');
                    return false;
                }
                
                const regex = /^[a-zA-Z0-9_-]+$/;
                if (!regex.test(username)) {
                    usernameError.classList.remove('hidden');
                    usernameInput.classList.add('border-red-600');
                    usernameInput.classList.remove('border-gray-300');
                    return false;
                }
                
                usernameError.classList.add('hidden');
                usernameInput.classList.remove('border-red-600');
                usernameInput.classList.add('border-gray-300');
                return true;
            }
            
            // Function to validate password
            function validatePassword() {
                const password = passwordInput.value;
                if (password === '') {
                    passwordError.classList.add('hidden');
                    passwordInput.classList.remove('border-red-600');
                    passwordInput.classList.add('border-gray-300');
                    return false;
                }
                
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const isLengthValid = password.length >= 8;
                const hasSpaces = /\s/.test(password);

                if (!hasUppercase || !hasLowercase || !hasNumber || !isLengthValid || hasSpaces) {
                    passwordError.classList.remove('hidden');
                    passwordInput.classList.add('border-red-600');
                    passwordInput.classList.remove('border-gray-300');
                    return false;
                }
                
                passwordError.classList.add('hidden');
                passwordInput.classList.remove('border-red-600');
                passwordInput.classList.add('border-gray-300');
                return true;
            }
            
            // Event listeners for real-time validation
            usernameInput.addEventListener('input', validateUsername);
            passwordInput.addEventListener('input', validatePassword);
            
            // Form submission handler
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                // Validate fields
                const isUsernameValid = validateUsername();
                const isPasswordValid = validatePassword();
                
                // If any validation fails, prevent form submission
                if (!isUsernameValid || !isPasswordValid) {
                    event.preventDefault();
                    
                    // Show error messages if fields are empty
                    if (usernameInput.value === '') {
                        usernameError.textContent = 'Username is required.';
                        usernameError.classList.remove('hidden');
                        usernameInput.classList.add('border-red-600');
                    }
                    
                    if (passwordInput.value === '') {
                        passwordError.textContent = 'Password is required.';
                        passwordError.classList.remove('hidden');
                        passwordInput.classList.add('border-red-600');
                    }
                }
            });
        });
    </script>
</body>
</html>
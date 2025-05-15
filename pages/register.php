<?php
session_start(); // Start the session

// Include PHPMailer
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Initialize error array
    $errors = [];

    // Validate inputs
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $errors[] = 'Username can only contain letters, numbers, hyphens, and underscores.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number.';
    } elseif (strpos($password, ' ') !== false) {
        $errors[] = 'Password cannot contain spaces.';
    }

    if (empty($confirmPassword)) {
        $errors[] = 'Confirm Password is required.';
    } elseif ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($fullName)) {
        $errors[] = 'Full Name is required.';
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (empty($phone)) {
        $errors[] = 'Phone Number is required.';
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = 'Phone Number must contain exactly 10 digits.';
    }

    // Check if username or email already exists in the database
    if (empty($errors)) {
        // Create a mysqli connection
        $conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if ($user['Username'] === $username && $user['Email'] !== $email) {
                $errors[] = 'Username is taken please try another username.';
            }
            if ($user['Email'] === $email && $user['Username'] !== $username) {
                $errors[] = 'Email already exists.';
            }
            if ($user['Username'] === $username && $user['Email'] === $email) {
                $errors[] = 'Username and Email already exist.';
            }
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    }

    // If there are errors, store them in the session and redirect back to the form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: /KMSC/pages/register.php');
        exit;
    }

    // If no errors, proceed with email verification
    $verificationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store the code and user data in the session
    $_SESSION['verification_code'] = $verificationCode;
    $_SESSION['verification_code_expiry'] = time() + 600; // 10 minutes from now
    $_SESSION['temp_user'] = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT), // Hash the password
        'fullName' => $fullName,
        'email' => $email,
        'phone' => $phone,
    ];

    // Send the verification email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP(); // Use SMTP
        $mail->Host = 'sandbox.smtp.mailtrap.io'; // SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = '6039652b84e6e8'; // email
        $mail->Password = 'aa925e50fb8a5d'; // email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('no-reply@kmsc.com', 'KMSC'); // Sender email and name
        $mail->addAddress($email); // Recipient email

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your Verification Code';
        $mail->Body = '
                        <html>
                        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
                            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                                <h2 style="color: #333333; text-align: center;">Welcome to KMSC!</h2>
                                <p style="color: #555555;">Your verification code is: <b style="color: #dc2626;">' . $verificationCode . '</b></p>
                                <p style="color: #555555;">This code will expire in 10 minutes.</p>
                                <p style="color: #555555;">If you did not request this, please ignore this email.</p>
                                <p style="text-align: center; margin-top: 20px;">
                                    <a href="http://localhost/KMSC/" style="background-color: #dc2626; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Visit KMSC</a>
                                </p>
                            </div>
                        </body>
                        </html>
                    ';

        // Send the email
        $mail->send();

        // Redirect to the verification page
        header('Location: /KMSC/pages/verify.php');
        exit;
    } catch (Exception $e) {
        // Handle errors
        $errors[] = 'Failed to send the verification email. Error: ' . $mail->ErrorInfo;
        $_SESSION['errors'] = $errors;
        header('Location: /KMSC/pages/register.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-center mb-6">Register</h2>

            <!-- Display Error Messages -->
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="error-message px-4 py-3 rounded mb-6" id="error-message">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); // Clear the errors after displaying them ?>
            <?php endif; ?>

            <form action="/KMSC/pages/register.php" method="POST">
                <!-- Username -->
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <span id="username-error" class="text-sm text-red-600 hidden">No special characters are allowed in usernames other than letters, underscores, and hyphens.</span>
                </div>

                <!-- Full Name -->
                <div class="mb-6">
                    <label for="fullName" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="fullName" name="fullName" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <span id="fullName-error" class="text-sm text-red-600 hidden">Full Name can only contain letters and spaces.</span>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <span id="password-error" class="text-sm text-red-600 hidden">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</span>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <span id="confirmPassword-error" class="text-sm text-red-600 hidden">Passwords do not match.</span>
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <span id="email-error" class="text-sm text-red-600 hidden">Please enter a valid email address.</span>
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <span id="phone-error" class="text-sm text-red-600 hidden">Phone Number must contain exactly 10 digits and no letters.</span>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-red-600 text-white w-full py-2 rounded-md hover:bg-red-500 transition duration-300 text-center">Register</button>
                </div>

                <!-- Already have an account? Login Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">Already have an account? 
                        <a href="/KMSC/pages/login.php" class="underline-effect text-red-600 hover:text-red-700 transition duration-300">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Username Validation
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('username-error');
            usernameInput.addEventListener('input', function () {
                const username = usernameInput.value;
                if (username === '') {
                    usernameError.classList.add('hidden'); // Hide error if field is empty
                } else {
                    const regex = /^[a-zA-Z0-9_-]+$/; // Allow letters, numbers, underscores, and hyphens
                    if (!regex.test(username)) {
                        usernameError.classList.remove('hidden'); // Show error if input is invalid
                    } else {
                        usernameError.classList.add('hidden'); // Hide error if input is valid
                    }
                }
            });

            // Full Name Validation
            const fullNameInput = document.getElementById('fullName');
            const fullNameError = document.getElementById('fullName-error');
            fullNameInput.addEventListener('input', function () {
                const fullName = fullNameInput.value;
                if (fullName === '') {
                    fullNameError.classList.add('hidden'); // Hide error if field is empty
                } else {
                    const regex = /^[a-zA-Z\s]+$/; // Allow letters and spaces
                    if (!regex.test(fullName)) {
                        fullNameError.classList.remove('hidden'); // Show error if input is invalid
                    } else {
                        fullNameError.classList.add('hidden'); // Hide error if input is valid
                    }
                }
            });

            // Password Validation
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('password-error');
            passwordInput.addEventListener('input', function () {
                const password = passwordInput.value;
                if (password === '') {
                    passwordError.classList.add('hidden'); // Hide error if field is empty
                } else {
                    const hasUppercase = /[A-Z]/.test(password);
                    const hasLowercase = /[a-z]/.test(password);
                    const hasNumber = /[0-9]/.test(password);
                    const isLengthValid = password.length >= 8;

                    if (!hasUppercase || !hasLowercase || !hasNumber || !isLengthValid) {
                        passwordError.classList.remove('hidden'); // Show error if input is invalid
                    } else {
                        passwordError.classList.add('hidden'); // Hide error if input is valid
                    }
                }
            });

            // Confirm Password Validation
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const confirmPasswordError = document.getElementById('confirmPassword-error');
            confirmPasswordInput.addEventListener('input', function () {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                if (confirmPassword === '') {
                    confirmPasswordError.classList.add('hidden'); // Hide error if field is empty
                } else if (password !== confirmPassword) {
                    confirmPasswordError.classList.remove('hidden'); // Show error if passwords do not match
                } else {
                    confirmPasswordError.classList.add('hidden'); // Hide error if passwords match
                }
            });

            // Email Validation
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            emailInput.addEventListener('input', function () {
                const email = emailInput.value;
                if (email === '') {
                    emailError.classList.add('hidden'); // Hide error if field is empty
                } else {
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format validation
                    if (!regex.test(email)) {
                        emailError.classList.remove('hidden'); // Show error if input is invalid
                    } else {
                        emailError.classList.add('hidden'); // Hide error if input is valid
                    }
                }
            });

            // Phone Validation
            const phoneInput = document.getElementById('phone');
            const phoneError = document.getElementById('phone-error');
            phoneInput.addEventListener('input', function () {
                const phone = phoneInput.value;
                if (phone === '') {
                    phoneError.classList.add('hidden'); // Hide error if field is empty
                } else {
                    const regex = /^\d{10}$/; // Exactly 10 digits
                    if (!regex.test(phone)) {
                        phoneError.classList.remove('hidden'); // Show error if input is invalid
                    } else {
                        phoneError.classList.add('hidden'); // Hide error if input is valid
                    }
                }
            });
        });
    </script>
</body>
</html>
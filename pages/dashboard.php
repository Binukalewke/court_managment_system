<?php
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /KMSC/pages/login.php');
    exit;
}

// Check for success/error messages from previous operations
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;

// Fetch user details from the database if not already in the session
if (!isset($_SESSION['fullName']) || !isset($_SESSION['email']) || !isset($_SESSION['phone'])) {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '12345678', 'sportscomplexdb');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Fetch user details
    $stmt = $conn->prepare("SELECT fullName, email, phone FROM users WHERE userID = ?");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Store user details in the session
    $_SESSION['fullName'] = $user['fullName'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone'] = $user['phone'];

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">

        
            <!-- Greeting Card with Red Gradient -->
            <div class="bg-red-600 text-white p-8 rounded-xl shadow-lg mb-8">
                <div class="flex md:flex-row md:items-center md:justify-between">
                    <div class="mt-4 md:mt-0 text-left">
                        <div class="text-xl font-bold" id="current-date"></div>
                        <div class="text-2xl font-bold" id="current-time"></div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <h2 class="text-3xl font-bold text-left mb-8">Dashboard</h2>

            <!-- User Information Section -->
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <div class="space-y-4">

                    <!-- Full Name -->
                    <div class="flex items-center justify-between py-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['fullName']); ?></p>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-between py-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Username</p>
                                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            </div>
                        </div>
                        <button onclick="openModal('usernameModal')" class="text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center justify-between py-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-500">Email Address</p>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                        </div>
                    </div>

                    <!-- Contact Number -->
                    <div class="flex items-center justify-between">
                        <div class=" py-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Contact</p>
                                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['phone']); ?></p>
                            </div>
                        </div>
                        <button onclick="openModal('phoneModal')" class="text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Change Password Button -->
            <div class="text-center mb-8">

            </div>

            <!-- Logout Button -->
            <div class="flex justify-end text-center space-x-4 sm:justify-center">
                <button onclick="openModal('passwordModal')" class="bg-black text-white px-6 py-2 rounded-md hover:bg-gray-700 transition duration-300">
                    Change Password
                </button>
                <button onclick="openModal('logoutModal')" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-500 transition duration-300">
                    Logout
                </button>
            </div>
        </div>
    </main>

    <!-- Username Modal -->
    <div id="usernameModal" class="fixed inset-0 custom-modal-blur flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg w-1/2 shadow-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Edit Username</h3>
                <button onclick="closeModal('usernameModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal Form -->
            <form action="/KMSC/pages/update_username.php" method="POST" id="usernameForm">
                <div class="mb-6">
                    <label for="newUsername" class="block text-sm font-medium text-gray-700 mb-2">New Username</label>
                    <input type="text" id="newUsername" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           required
                           oninput="validateUsername(this)">
                    <span id="username-error" class="text-sm text-red-600 hidden"></span>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500 transition duration-300">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Phone Modal -->
    <div id="phoneModal" class="fixed inset-0 custom-modal-blur flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg w-1/2 shadow-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Edit Contact Number</h3>
                <button onclick="closeModal('phoneModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal Form -->
            <form action="/KMSC/pages/update_phone.php" method="POST" id="phoneForm">
                <div class="mb-6">
                    <label for="newPhone" class="block text-sm font-medium text-gray-700 mb-2">New Contact Number</label>
                    <input type="tel" id="newPhone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           required
                           oninput="validatePhone(this)">
                    <span id="phone-error" class="text-sm text-red-600 hidden"></span>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500 transition duration-300">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Modal -->
    <div id="passwordModal" class="fixed inset-0 custom-modal-blur flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg w-1/2 shadow-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Change Password</h3>
                <button onclick="closeModal('passwordModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal Form -->
            <form action="/KMSC/pages/change_password.php" method="POST" id="passwordForm">
                <div class="mb-6">
                    <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           required>
                </div>
                <div class="mb-6">
                    <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           required
                           oninput="validatePassword(this)">
                    <span id="password-error" class="text-sm text-red-600 hidden"></span>
                </div>
                <div class="mb-6">
                    <label for="confirmNewPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" id="confirmNewPassword" name="confirmNewPassword" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           required
                           oninput="validateConfirmPassword(this)">
                    <span id="confirm-password-error" class="text-sm text-red-600 hidden"></span>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500 transition duration-300">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 custom-modal-blur flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg w-1/2 shadow-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Confirm Logout</h3>
                <button onclick="closeModal('logoutModal')" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal Message -->
            <p class="text-gray-600 mb-6">Are you sure you want to logout?</p>
            <!-- Modal Form & Button -->
            <div class="flex justify-end space-x-4">
                <button onclick="closeModal('logoutModal')" class="border-2 border-black text-black bg-white hover:text-red-600 hover:border-red-600 px-4 py-2 rounded-md transition duration-300">
                    Cancel
                </button>
                <form action="/KMSC/pages/logout.php" method="POST">
                    <button type="submit" class="text-white bg-red-600 px-4 py-2 rounded-md hover:bg-red-500 transition duration-300">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Function to open a modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        // Function to close a modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Username Validation
        function validateUsername(input) {
            const username = input.value.trim();
            const errorElement = document.getElementById('username-error');
            
            if (username === '') {
                showError(input, errorElement, 'Username is required.');
                return false;
            }
            
            if (!/^[a-zA-Z0-9_-]+$/.test(username)) {
                showError(input, errorElement, 'Username can only contain letters, numbers, hyphens, and underscores.');
                return false;
            }
            
            clearError(input, errorElement);
            return true;
        }

        // Phone Validation
        function validatePhone(input) {
            const phone = input.value.trim();
            const errorElement = document.getElementById('phone-error');
            
            if (phone === '') {
                showError(input, errorElement, 'Phone number is required.');
                return false;
            }
            
            if (!/^\d{10}$/.test(phone)) {
                showError(input, errorElement, 'Phone number must contain exactly 10 digits.');
                return false;
            }
            
            clearError(input, errorElement);
            return true;
        }

        // Password Validation
        function validatePassword(input) {
            const password = input.value;
            const errorElement = document.getElementById('password-error');
            
            if (password === '') {
                showError(input, errorElement, 'Password is required.');
                return false;
            }
            
            if (password.length < 8) {
                showError(input, errorElement, 'Password must be at least 8 characters long.');
                return false;
            }
            
            if (!/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
                showError(input, errorElement, 'Password must contain at least one uppercase letter, one lowercase letter, and one number.');
                return false;
            }
            
            if (/\s/.test(password)) {
                showError(input, errorElement, 'Password cannot contain spaces.');
                return false;
            }
            
            clearError(input, errorElement);
            return true;
        }

        // Confirm Password Validation
        function validateConfirmPassword(input) {
            const confirmPassword = input.value;
            const password = document.getElementById('newPassword').value;
            const errorElement = document.getElementById('confirm-password-error');
            
            if (confirmPassword === '') {
                showError(input, errorElement, 'Please confirm your password.');
                return false;
            }
            
            if (confirmPassword !== password) {
                showError(input, errorElement, 'Passwords do not match.');
                return false;
            }
            
            clearError(input, errorElement);
            return true;
        }

        // Show error message and style the input
        function showError(input, errorElement, message) {
            input.classList.add('border-red-600');
            input.classList.remove('border-gray-300');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
        
        // Clear error message and reset input style
        function clearError(input, errorElement) {
            input.classList.remove('border-red-600');
            input.classList.add('border-gray-300');
            errorElement.textContent = '';
            errorElement.classList.add('hidden');
        }

        // Form submission validation
        document.getElementById('usernameForm')?.addEventListener('submit', function(event) {
            const isValid = validateUsername(document.getElementById('newUsername'));
            if (!isValid) {
                event.preventDefault();
            }
        });

        document.getElementById('phoneForm')?.addEventListener('submit', function(event) {
            const isValid = validatePhone(document.getElementById('newPhone'));
            if (!isValid) {
                event.preventDefault();
            }
        });

        document.getElementById('passwordForm')?.addEventListener('submit', function(event) {
            const isPasswordValid = validatePassword(document.getElementById('newPassword'));
            const isConfirmValid = validateConfirmPassword(document.getElementById('confirmNewPassword'));
            
            if (!isPasswordValid || !isConfirmValid) {
                event.preventDefault();
            }
        });

        // Check for username availability
        document.getElementById('newUsername')?.addEventListener('blur', function() {
            const username = this.value.trim();
            const currentUsername = '<?php echo $_SESSION['username']; ?>';
            
            if (username !== '' && username !== currentUsername && /^[a-zA-Z0-9_-]+$/.test(username)) {
                checkUsernameAvailability(username);
            }
        });

        function checkUsernameAvailability(username) {
            fetch('/KMSC/pages/check_username.php?username=' + encodeURIComponent(username))
                .then(response => response.json())
                .then(data => {
                    const errorElement = document.getElementById('username-error');
                    if (data.available) {
                        clearError(document.getElementById('newUsername'), errorElement);
                    } else {
                        showError(document.getElementById('newUsername'), errorElement, 'Username is already taken. Please try another.');
                    }
                });
        }

            // Function to update date and time
        function updateDateTime() {
            const now = new Date();
            
            // Format date (e.g., "Monday, January 1, 2023")
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
            
            // Format time (e.g., "11:45:23 PM")
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US');
        }
        
        // Update immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>
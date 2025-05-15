<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Complex Management System</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
    <script>
        // JavaScript to toggle the off-canvas menu
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            const body = document.body;

            // Toggle menu visibility
            menu.classList.toggle('translate-x-full');
            menu.classList.toggle('translate-x-0');

            // Toggle body scroll
            body.classList.toggle('menu-open');
        }
    </script>
</head>
<body>
    <!-- Header -->
    <header class="bg-white backdrop-blur-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 py-5 flex items-center justify-between">
            <!-- Logo (Left) -->
            <div class="flex items-center flex-shrink-0">
                <a href="/KMSC"><span class="text-xl font-bold text-black">KMSC</span></a>
            </div>

            <!-- Navigation Links (Center) -->
            <nav class="hidden md:flex space-x-8 mx-auto">
                <a href="/KMSC/pages/about.php" class="underline-effect ml-35 text-black hover:text-red-600 transition duration-300">About</a>
                <a href="/KMSC/pages/services.php" class="underline-effect text-black hover:text-red-600 transition duration-300">Services</a>
                <a href="/KMSC/pages/booking-portal.php" class="underline-effect text-black hover:text-red-600 transition duration-300">Booking Portal</a>
            </nav>

            <!-- Register and Login Buttons (Right) -->
            <div class="hidden md:flex items-center space-x-4 flex-shrink-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="font-medium">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <!-- My Account Icon (Logged In) -->
                    <a href="/KMSC/pages/dashboard.php" class="text-black hover:text-red-600 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <!-- Register and Login Buttons (Logged Out) -->
                    <a href="/KMSC/pages/register.php" class="border-2 border-black text-black bg-white hover:text-red-600 hover:border-red-600 px-4 py-2 rounded-md transition duration-300">Register</a>
                    <a href="/KMSC/pages/login.php" class="border-2 border-black text-white bg-black px-4 py-2 rounded-md hover:bg-gray-700 hover:border-gray-700 transition duration-300">Login</a>
                <?php endif; ?>
            </div>

            <!-- Hamburger Menu Toggle (Right - Mobile Only) -->
            <button class="md:hidden p-2 focus:outline-none" onclick="toggleMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Off-Canvas Menu (Mobile Only) -->
        <div id="mobile-menu" class="md:hidden fixed inset-y-0 right-0 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out translate-x-full z-50">
            <!-- Close Button -->
            <div class="flex justify-end p-4">
                <button class="p-2 focus:outline-none" onclick="toggleMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu Links -->
            <div class="flex flex-col space-y-4 p-4">
                <a href="/KMSC/pages/about.php" class="text-black hover:text-red-600 transition duration-300 text-center">About</a>
                <a href="/KMSC/pages/services.php" class="text-black hover:text-red-600 transition duration-300 text-center">Services</a>
                <a href="/KMSC/pages/booking-portal.php" class="text-black hover:text-red-600 transition duration-300 text-center">Booking Portal</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- My Account Link (Logged In) -->
                    <a href="/KMSC/pages/dashboard.php" class="text-black hover:text-red-600 transition duration-300 text-center">My Account</a>
                <?php else: ?>
                    <!-- Register and Login Links (Logged Out) -->
                    <a href="/KMSC/pages/register.php" class="border border-black text-black bg-white hover:text-red-600 hover:border-red-600 px-4 py-2 rounded-md transition duration-300 text-center">Register</a>
                    <a href="/KMSC/pages/login.php" class="border border-black text-white bg-black px-4 py-2 rounded-md transition duration-300 text-center">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Background Blur Overlay -->
        <div id="mobile-menu-overlay" onclick="toggleMenu()"></div>
    </header>
</body>
</html>
<?php
session_start(); // Start the session

// Redirect to dashboard if the user is already logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header('Location: /KMSC/pages/admin/login.php');
    exit;
}

// Get Database Status
function getDatabaseStatus() {
    $servername = "localhost";
    $username = "root"; 
    $password = "12345678"; 
    $dbname = "sportscomplexdb"; 

    $conn = @new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return "<span class='text-red-500'>Offline</span>";
    } else {
        return "<span class='text-green-500'>Online</span>";
    }
}

$lastBackup = "2025/03/01";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Portal</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="../../assets/css/output.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar styles matching your header's mobile menu */
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 50;
        }
        .sidebar-open {
            transform: translateX(0);
        }
        .menu-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            backdrop-filter: blur(5px); /* Blur effect */
            z-index: 30; /* Below the off-canvas menu */
        }
        .menu-overlay-active {
            display: block;
        }
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }
            .menu-overlay {
                display: none !important;
            }
        }
        .active-menu {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #ef4444;
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen">
    <!-- Mobile Overlay -->
    <div class="menu-overlay" id="menuOverlay" onclick="toggleMenu()"></div>

    <?php include '../../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button class="text-black md:hidden" onclick="toggleSidebar()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold">Dashboard Overview</h2>
            </div>
            <div class="flex items-center space-x-4">
                <!-- <div class="relative">
                    <i class="fas fa-bell text-gray-500"></i>
                    <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                </div> -->
                <div class="flex items-center">
                    <!-- <img src="" alt="Profile" class="rounded-full mr-2"> -->
                    <span class="font-medium">Hello, Hirusha</span>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500">Total Users</p>
                            <h3 class="text-2xl font-bold">1,248</h3>
                        </div>
                        <i class="fas fa-users text-red-500 text-2xl"></i>
                    </div>
                    <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 12% from last month</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500">Active Bookings</p>
                            <h3 class="text-2xl font-bold">86</h3>
                        </div>
                        <i class="fas fa-calendar-check text-blue-500 text-2xl"></i>
                    </div>
                    <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i> 5% from last week</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500">Revenue</p>
                            <h3 class="text-2xl font-bold">$24,580</h3>
                        </div>
                        <i class="fas fa-dollar-sign text-green-500 text-2xl"></i>
                    </div>
                    <p class="text-red-500 text-sm mt-2"><i class="fas fa-arrow-down mr-1"></i> 3% from last month</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500">Pending Requests</p>
                            <h3 class="text-2xl font-bold">12</h3>
                        </div>
                        <i class="fas fa-exclamation-circle text-yellow-500 text-2xl"></i>
                    </div>
                    <p class="text-green-500 text-sm mt-2"><i class="fas fa-arrow-down mr-1"></i> 2 from yesterday</p>
                </div>
            </div>

            <!-- Recent Activity and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Bookings -->
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold">Recent Bookings</h3>
                        <a href="#" class="text-sm text-red-600 hover:underline">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">John Doe</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Tennis Court</td>
                                    <td class="px-6 py-4 whitespace-nowrap">May 15, 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmed</span>
                                    </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-user-plus text-red-500 mr-3"></i>
                            <span>Add New User</span>
                        </button>
                        <button class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-calendar-plus text-blue-500 mr-3"></i>
                            <span>Create Booking</span>
                        </button>
                        <button class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-file-invoice-dollar text-green-500 mr-3"></i>
                            <span>Generate Report</span>
                        </button>
                        <button class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-bell text-yellow-500 mr-3"></i>
                            <span>Send Notification</span>
                        </button>
                    </div>
                    <!-- System Status -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium mb-3">System Status</h4>
                        <div class="space-y-2">
                            <!-- Server Load -->
                            <div class="flex justify-between text-sm">
                                <span>Server Load</span>
                                <span class="font-medium">30%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                            </div>

                            <!-- Memory Usage -->
                            <div class="flex justify-between text-sm mt-4">
                                <span>Memory Usage</span>
                                <span class="font-medium">1430MB</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 14%"></div>
                            </div>

                            <!-- Disk Usage -->
                            <div class="flex justify-between text-sm mt-4">
                                <span>Disk Usage</span>
                                <span class="font-medium">5%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 5%"></div>
                            </div>

                            <!-- Database Status -->
                            <div class="flex justify-between text-sm mt-4">
                                <span>Database</span>
                                <span class="font-medium"><?= getDatabaseStatus(); ?></span>
                            </div>

                            <!-- Last Backup -->
                            <div class="flex justify-between text-sm">
                                <span>Last Backup</span>
                                <span><?= $lastBackup; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle sidebar function - matches your header logic
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('menuOverlay');
            const body = document.body;

            // Toggle menu visibility
            sidebar.classList.toggle('sidebar-open');
            
            // Toggle overlay
            overlay.classList.toggle('menu-overlay-active');
            
            // Toggle body scroll
            body.classList.toggle('menu-open');
        }

        // Close sidebar when clicking overlay
        document.getElementById('menuOverlay').addEventListener('click', toggleSidebar);

        // Close sidebar when clicking a nav item on mobile
        document.querySelectorAll('nav a').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });
    </script>
</body>
</html>
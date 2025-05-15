<!-- Sidebar Navigation -->
<div class="sidebar fixed md:relative w-64 bg-white shadow-md flex-shrink-0 h-full" id="sidebar">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
        <h1 class="text-xl font-bold text-red-600 flex items-center">
            KMSC Admin Portal
        </h1>
        <button class="md:hidden" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
        </button>
    </div>
    <nav class="p-4 space-y-1 flex flex-col h-[calc(100%-120px)]">
        <a href="#" class="block py-2 px-4 rounded-lg active-menu">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>
        <a href="#" class="block py-2 px-4 rounded-lg hover:bg-gray-100 transition">
            <i class="fas fa-users mr-3"></i> User Management
        </a>
        <a href="#" class="block py-2 px-4 rounded-lg hover:bg-gray-100 transition">
            <i class="fas fa-calendar-alt mr-3"></i> Bookings
        </a>
        <a href="#" class="block py-2 px-4 rounded-lg hover:bg-gray-100 transition">
            <i class="fas fa-money-bill-wave mr-3"></i> Payments
        </a>
        <a href="#" class="block py-2 px-4 rounded-lg hover:bg-gray-100 transition">
            <i class="fas fa-cog mr-3"></i> Settings
        </a>
        
        <!-- Logout Button at Bottom -->
        <div class="mt-auto pt-4 border-t border-gray-200">
            <form action="../logout.php" method="POST">
                <button type="submit" class="w-full text-left py-2 px-4 rounded-lg hover:bg-gray-100 transition text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>
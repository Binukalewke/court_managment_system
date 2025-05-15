<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMSC</title>
    <link rel="icon" href="http://localhost/KMSC/favicon.ico ">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link href="assets/css/output.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <?php include 'includes/header.php'; ?>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto px-4 py-12">
        <!-- Hero Section -->
        <section class="relative rounded-xl p-8 md:p-12 mb-12 h-150 flex items-center justify-center md:justify-start" style="background-image: url('assets/images/homepage-image.png'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black opacity-50 rounded-xl"></div>
        <div class="relative z-10 text-white max-w-2xl text-center md:text-left">
            <h1 class="text-4xl mb-2">YOUR GATEWAY TO FITNESS AND FUN!</h1>
            <p class="text-red-600 mb-8">Book Your Spot at Our Sports Complex Today.</p>
            <a href="/KMSC/pages/booking-portal.php" class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-white hover:text-red-600 transition duration-300">Reserve Now</a>
        </div>
        </section>

        <!-- Gallery Section -->
        <section class="mb-12">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-4xl">EXPLORE OUR CAPTURES</h2>
            <!-- Navigation Arrows -->
            <div class="flex space-x-4">
                <button id="scrollLeft" class="border-2 text-black p-2 rounded-full hover:text-red-600 transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button id="scrollRight" class="border-2 text-black p-2 rounded-full hover:text-red-600 transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <p class="text-red-600 mb-8">A Glimpse of Our Facility</p>
        
        <div id="scrollContainer" class="overflow-x-auto whitespace-nowrap scroll-smooth relative scroll-snap-x mandatory">
            <div class="inline-flex space-x-6 mb-8 scroll-snap-align-start">
                <div class="relative w-80 flex-shrink-0">
                    <img src="assets/images/capture-1.png" alt="Facility Image 1" class="w-full h-64 object-cover rounded-lg">
                </div>
                <div class="relative w-80 flex-shrink-0">
                    <img src="assets/images/capture-2.png" alt="Facility Image 2" class="w-full h-64 object-cover rounded-lg">
                </div>
                <div class="relative w-80 flex-shrink-0">
                    <img src="assets/images/capture-3.png" alt="Facility Image 3" class="w-full h-64 object-cover rounded-lg">
                </div>
                <div class="relative w-80 flex-shrink-0">
                    <img src="assets/images/capture-4.png" alt="Facility Image 1" class="w-full h-64 object-cover rounded-lg">
                </div>
                <div class="relative w-80 flex-shrink-0">
                    <img src="assets/images/capture-5.png" alt="Facility Image 2" class="w-full h-64 object-cover rounded-lg">
                </div>
                <div class="relative w-80 flex-shrink-0">
                    <img src="assets/images/capture-6.png" alt="Facility Image 3" class="w-full h-64 object-cover rounded-lg">
                </div>
            </div>
        </div>
    </section>

    <script>
        // JavaScript for scroll functionality
        const scrollContainer = document.getElementById('scrollContainer');
        const scrollLeftButton = document.getElementById('scrollLeft');
        const scrollRightButton = document.getElementById('scrollRight');

        // Scroll left
        scrollLeftButton.addEventListener('click', () => {
            scrollContainer.scrollBy({
                left: -300, // Adjust scroll distance as needed
                behavior: 'smooth'
            });
        });

        // Scroll right
        scrollRightButton.addEventListener('click', () => {
            scrollContainer.scrollBy({
                left: 300, // Adjust scroll distance as needed
                behavior: 'smooth'
            });
        });
    </script>
    </main>
</body>
</html>
<?php include 'includes/footer.php'; ?>
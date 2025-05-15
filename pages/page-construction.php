<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Construction - KMSC</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <style>
        /* Custom CSS for the hover underline animation */
        .underline-effect {
            position: relative;
            display: inline-block;
        }

        .underline-effect::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px; /* Adjust this value to control the distance of the underline */
            width: 0;
            height: 2px; /* Thickness of the underline */
            background-color: #dc2626; /* Red color for the underline */
            transition: width 0.3s ease-in-out; /* Smooth transition */
        }

        .underline-effect:hover::after {
            width: 100%; /* Full width on hover */
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Under Construction Section -->
    <section class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <!-- Construction Icon -->
            <div class="mb-8">
                <svg class="w-24 h-24 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Under Construction</h1>

            <!-- Message -->
            <p class="text-lg text-gray-600 mb-8">We're working hard to bring you something amazing. Please check back soon!</p>

            <!-- Homepage Link -->
            <a href="/KMSC" class="underline-effect text-red-600 text-lg transition duration-300">Go Back to Homepage</a>
        </div>
    </section>
</body>
</html>
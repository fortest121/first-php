<?php
session_start();
// NOTE: Assuming this file is in /admin, config is at project root /config/config.php
require_once __DIR__ . "/../config/config.php";

// --- Security Check & Logout Handler ---
if (!isset($_SESSION['admin_logged_in'])) {
    // Assuming admin_auth.php is in ../auth/
    header('Location: ../auth/admin_auth.php'); // Redirect to your login script
    exit();
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../auth/admin_auth.php'); // Redirect to your login script after logout
    exit();
}

// Determine which page to load
$page = $_GET['page'] ?? 'dashboard';

// Map page keys to file paths for inclusion
$pages = [
    'dashboard'       => 'dashboard_content.php', 
    'service_update'  => 'service_page_update.php', // New content page
    'faq_update'      => 'faq_update.php',
    'sidebar_images'  => 'upload_sidebar_image.php', // Corrected file name
    // Add other admin pages here
];

// Determine the file to include
$file_to_include = $pages[$page] ?? null;

// The default dashboard content (if dashboard_content.php doesn't exist)
$default_dashboard_content = "
    <div class='p-6 bg-gray-800 rounded-xl shadow-2xl'>
        <h1 class='text-4xl font-extrabold text-indigo-400'>Welcome, Admin! 👋</h1>
        <p class='mt-3 text-gray-400 text-xl'>Manage and update your website content using the menu.</p>
        <div class='mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'>
            <div class='bg-gray-700 p-5 rounded-lg shadow-md border-l-4 border-indigo-500'>
                <h3 class='text-sm font-medium text-gray-400 uppercase'>Service Pages</h3>
                <p class='text-3xl text-white mt-1'>50+</p>
            </div>
            <div class='bg-gray-700 p-5 rounded-lg shadow-md border-l-4 border-green-500'>
                <h3 class='text-sm font-medium text-gray-400 uppercase'>Pending FAQs</h3>
                <p class='text-3xl text-white mt-1'>0</p>
            </div>
             <div class='bg-gray-700 p-5 rounded-lg shadow-md border-l-4 border-yellow-500'>
                <h3 class='text-sm font-medium text-gray-400 uppercase'>Current Images</h3>
                <p class='text-3xl text-white mt-1'>12</p>
            </div>
        </div>
    </div>
";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Admin Dashboard - <?= ucwords(str_replace('_', ' ', $page)) ?></title>
<style>
    /* * FIX 1: Custom Scrollbar Styling (Modern Look)
     */
    /* Target WebKit browsers (Chrome, Safari) */
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #1f2937; /* Dark gray for track */
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #4f46e5; /* Indigo for thumb */
        border-radius: 3px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #6366f1; /* Lighter indigo on hover */
    }

    /* * FIX 2: Mobile Sidebar Visibility
     */
    .sidebar-closed {
        transform: translateX(-100%);
        /* Ensure no overlay when closed */
        box-shadow: none; 
    }
    .sidebar-open {
        transform: translateX(0);
        /* Overlay effect for mobile */
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); 
    }
</style>
</head>

<body class="bg-gray-900 text-white h-screen flex overflow-hidden">

<button id="menu-btn" class="fixed top-4 left-4 z-40 p-3 bg-indigo-600 rounded-lg lg:hidden transition hover:bg-indigo-700">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
</button>

<aside id="sidebar" class="fixed top-0 left-0 w-64 bg-gray-800 h-screen p-6 space-y-6 z-30 overflow-y-auto custom-scroll
                             lg:relative lg:translate-x-0 lg:shadow-none transition-transform duration-300 ease-in-out sidebar-closed">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-extrabold text-indigo-400">ADMIN PANEL</h2>
        <button id="close-btn" class="text-gray-400 hover:text-white lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="space-y-2 font-medium">
        <?php 
        $nav_links = [
            'dashboard'      => '🏠 Dashboard',
            'service_update' => '✍️ Service Content', // NEW LINK
            'faq_update'     => '❓ Update FAQs',
            'sidebar_images' => '🖼️ Sidebar Images',
        ];

        foreach ($nav_links as $key => $label): 
        ?>
            <a href="dashboard2.php?page=<?= $key ?>" 
               class="block px-4 py-3 rounded-xl transition duration-200 ease-in-out font-semibold
               <?= $page == $key ? 'bg-indigo-600 shadow-xl text-white' : 'hover:bg-gray-700/70 text-gray-300' ?>">
               <?= $label ?>
            </a>
        <?php endforeach; ?>
        
        <div class="pt-6 border-t border-gray-700 mt-6">
            <a href="?logout=1" class="block px-4 py-3 rounded-xl hover:bg-red-700/50 text-red-400 transition duration-200 ease-in-out font-semibold">
                🚨 Logout
            </a>
        </div>
    </nav>
</aside>

<main class="flex-1 p-4 md:p-8 overflow-y-auto custom-scroll">
    <div class="max-w-7xl mx-auto">
        <?php
        if ($file_to_include && file_exists($file_to_include)) {
            // Load the corresponding content page
            include $file_to_include;
        } else {
            // Display the default dashboard content
            echo $default_dashboard_content;
        }
        ?>
    </div>
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const lgBreakpoint = 1024; // Tailwind's 'lg' breakpoint

    // --- Sidebar Toggle Logic (Handles Menu Button Visibility) ---

    menuBtn.addEventListener('click', () => {
        // Open the sidebar
        sidebar.classList.remove('sidebar-closed');
        sidebar.classList.add('sidebar-open');
        
        // Hide the menu button when sidebar is open on small screens (FIX)
        if (window.innerWidth < lgBreakpoint) {
             menuBtn.style.display = 'none';
        }
    });

    closeBtn.addEventListener('click', () => {
        // Close the sidebar
        sidebar.classList.remove('sidebar-open');
        sidebar.classList.add('sidebar-closed');
        
        // Show the menu button when sidebar is closed on small screens (FIX)
        if (window.innerWidth < lgBreakpoint) {
             menuBtn.style.display = 'block'; // Show the menu button
        }
    });

    // --- Automatic Closing on Mobile Navigation ---

    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < lgBreakpoint) { 
                sidebar.classList.remove('sidebar-open');
                sidebar.classList.add('sidebar-closed');
                
                // Show the menu button again after navigation on mobile
                menuBtn.style.display = 'block'; 
            }
        });
    });

    // --- Initial State Management ---

    function setInitialState() {
        if (window.innerWidth < lgBreakpoint) {
            // Mobile: Ensure sidebar starts hidden and menu button is visible
            sidebar.classList.add('sidebar-closed');
            sidebar.classList.remove('sidebar-open');
            menuBtn.style.display = 'block';
        } else {
            // Desktop: Ensure sidebar is open and menu button is hidden
            sidebar.classList.remove('sidebar-closed');
            menuBtn.style.display = 'none';
        }
    }

    // Run on load
    window.onload = setInitialState;
    // Run on resize
    window.onresize = setInitialState;
</script>

</body>
</html>
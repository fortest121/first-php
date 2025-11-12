<?php
session_start();
require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit();
}

// Determine which page to load
$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Admin Dashboard</title>
</head>
<body class="bg-gray-900 text-white flex">

<!-- Sidebar -->
<aside class="w-64 bg-gray-800 min-h-screen p-6 space-y-6">
    <h2 class="text-2xl font-bold text-indigo-500 mb-6">Admin Panel</h2>
    <nav class="space-y-2">
        <a href="admin.php?page=dashboard" class="block px-4 py-2 rounded hover:bg-indigo-600 <?= $page=='dashboard'?'bg-indigo-700':'' ?>">Dashboard</a>
        <a href="admin.php?page=faq_update" class="block px-4 py-2 rounded hover:bg-indigo-600 <?= $page=='faq_update'?'bg-indigo-700':'' ?>">Update FAQs</a>
        <a href="admin.php?page=page_update" class="block px-4 py-2 rounded hover:bg-indigo-600 <?= $page=='page_update'?'bg-indigo-700':'' ?>">Update Pages</a>
        <a href="admin.php?page=sidebar_images" class="block px-4 py-2 rounded hover:bg-indigo-600 <?= $page=='sidebar_images'?'bg-indigo-700':'' ?>">Sidebar Images</a>
        <a href="admin.php?logout=1" class="block px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>
</aside>

<!-- Main Content -->
<main class="flex-1 p-6 overflow-auto">
    <?php
    switch($page) {
        case 'faq_update':
            include __DIR__ . '/faq_update.php';
            break;
        case 'page_update':
            include __DIR__ . '/page_update.php';
            break;
        case 'sidebar_images':
            include __DIR__ . '/upload_sidebar_image.php';
            break;
        default:
            echo "<h1 class='text-3xl font-bold text-indigo-500'>Welcome to Admin Dashboard</h1>";
            echo "<p class='mt-4 text-gray-300'>Select an option from the left menu to manage the website.</p>";
            break;
    }
    ?>
</main>

</body>
</html>

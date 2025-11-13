<?php
/**
 * ClientFilingIndia Front Controller Router
 * Handles all clean URLs and directs traffic to the correct page content.
 */

// --- 1. CONFIGURATION AND SETUP ---
// NOTE: Make sure the .htaccess file in the root uses this BASE_URL.
// define('BASE_URL', '/clientfilingindiademo/'); 

// Capture the URL segment passed by the .htaccess rewrite rule
$request = $_GET['url'] ?? '';
$request = trim($request, '/');

// Split the path into the primary slug and any potential dynamic parameters
// Example: "details/service-slug" -> $page_slug="details", $dynamic_param="service-slug"
$parts = explode('/', $request, 2);
$page_slug = $parts[0] ?? '';
$dynamic_param = $parts[1] ?? null; 

// --- 2. ROUTE MAP ---
// Maps the clean URL slug to the corresponding file path.
// Use 'home' as a flag for the multi-component homepage.
$routes = [
    // Primary Site Pages
    ''              => 'home',          // Root URL (e.g., /clientfilingindiademo/)
    'home'          => 'home',          // Clean URL /home
    'about'         => 'pages/about.php',
    'contact'       => 'pages/contact.php',
    'services'      => 'pages/all_services.php', 
    
    // Dynamic Pages
    // For URL /clientfilingindiademo/details/some-slug
    'details'       => 'details.php',
    
    // Authentication Pages
    // For clean URL /clientfilingindiademo/admin_auth
    'admin_auth'    => 'auth/admin_auth.php',
];

$content_file = $routes[$page_slug] ?? null;

// --- 3. EXECUTION FLOW ---

// 3a. Load Header (required for all pages)
require_once "common/header.php";

// 3b. Determine Content to Load

if ($page_slug === '' || $content_file === 'home') {
    // --- HOMEPAGE ROUTE ---
    // This section replicates your original index.php behavior
    require_once "pages/hero.php";
    require_once "pages/services.php";
    require_once "pages/plans.php";
    require_once "pages/slider.php";
    require_once "pages/popular_search.php";

} elseif ($content_file && file_exists(__DIR__ . '/' . $content_file)) {
    // --- STANDARD ROUTE (e.g., /contact, /admin_auth) ---
    
    // If we hit a dynamic page like 'details', make the parameter available 
    // to the included script (details.php) via $_GET['slug'].
    if ($page_slug === 'details' && $dynamic_param !== null) {
        $_GET['slug'] = $dynamic_param;
    }

    // Include the content page
    require_once __DIR__ . '/' . $content_file;

} else {
    // --- 404 NOT FOUND ---
    http_response_code(404);
    require_once __DIR__ . '/pages/404.php'; // Ensure you have a 404.php file!
}

// 3c. Load Footer (required for all pages)
require_once "common/footer.php";
?>



<!-- <?php include "common/header.php" ?>
<?php include "pages/hero.php" ?>
<?php include "pages/services.php" ?>
<?php include "pages/plans.php" ?>
<?php include "pages/slider.php" ?>
<?php include "pages/popular_search.php" ?>
<?php include "common/footer.php" ?> -->

<?php
require_once "config/config.php";

define('BASE_URL', '/clientfilingindiademo/'); 

// --- GLOBAL SITE SEO DEFAULTS ---
$site_name = "Client Filing India";
$site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$global_description = "Leading provider of business registration, tax filing, and compliance services in India. Secure your private limited company, OPC, or GST filing quickly and easily.";

// --- PAGE-SPECIFIC SEO (Conditional Check) ---
$page_title = $page_title ?? "$site_name | Official Website";
$page_description = $page_description ?? $global_description;
$current_url = $current_url ?? $site_url . $_SERVER['REQUEST_URI'];
$page_keywords = $page_keywords ?? "company registration, gst filing, income tax, compliance services, india";

$main_categories = [
    ['id' => 1, 'name' => 'Business Registration', 'slug' => 'business-registration'],
    ['id' => 2, 'name' => 'Taxation', 'slug' => 'taxation'],
    ['id' => 3, 'name' => 'Annual Compliance & Audit', 'slug' => 'annual-compliance-audit'],
    ['id' => 4, 'name' => 'Import-Export & IPR', 'slug' => 'import-export-ipr'],
    ['id' => 5, 'name' => 'Payroll & Corporate Compliances', 'slug' => 'payroll-corporate-compliances'],
];

// Helper function to fetch sub-services (DRY principle)
function fetch_services($conn, $cat_id) {
    // Check if PDO connection exists before querying
    if (!isset($conn)) { return []; } 
    try {
        $srv_stmt = $conn->prepare("SELECT name, slug FROM service_list WHERE category_id = :cat_id AND status = 1 ORDER BY order_no ASC");
        $srv_stmt->execute(['cat_id' => $cat_id]);
        return $srv_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error fetching services for category $cat_id: " . $e->getMessage());
        return [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($current_url) ?>" />
    
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>" />
    <meta property="og:url" content="<?= htmlspecialchars($current_url) ?>" />
    <meta property="og:site_name" content="<?= htmlspecialchars($site_name) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?= htmlspecialchars($site_url) ?>/default-social-image.jpg" />
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($site_url) ?>/default-social-image.jpg">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/global.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.ripples@0.6.3/dist/jquery.ripples.min.js"></script>

</head>

<body> <!-- <body class="bg-gray-50"> for white background -->
    <canvas id="myCanvas"></canvas>
    <header class="bg-blue-700 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            <a href="<?= BASE_URL ?>" class="flex items-center gap-2 text-white font-bold tracking-wide" title="<?= htmlspecialchars($site_name) ?>">
                <img src="assets/images/site_logo.webp" alt="<?= htmlspecialchars($site_name) ?> Logo" class="h-12 w-12 rounded-full">
            </a>

            <nav id="desktop-nav" class="hidden lg:flex" aria-label="Main Services Navigation">
                <ul class="flex space-x-6 text-left">
                    <?php foreach ($main_categories as $cat): ?>
                        <li class="relative group" role="menuitem">
                            <a href="#" class="block text-white font-semibold hover:text-yellow-200 transition" aria-haspopup="true">
                                <?= htmlspecialchars($cat['name']) ?>
                                <svg class="ml-1 w-4 h-4 text-yellow-200 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                            <?php $services = fetch_services($conn, $cat['id']); ?>

                            <?php if ($services): ?>
                                <div class="dropdown-area absolute left-0 top-full pt-3" role="menu">
                                   <ul class="desktop-dropdown-list bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100">
                                       
                                        <?php foreach ($services as $srv): ?>
                                            <li role="none">
                                                <a href="details?slug=<?= urlencode($srv['slug']) ?>"
                                                    class="block px-4 py-2 text-gray-700 transition" role="menuitem">
                                                    <?= htmlspecialchars($srv['name']) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <button id="menu-toggle" class="text-white lg:hidden focus:outline-none p-1 -m-1" aria-label="Toggle Navigation Menu" aria-expanded="false">
                <svg id="menu-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-opacity duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg id="menu-icon-close" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 hidden transition-opacity duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav id="mobile-nav-panel" class="mobile-nav-panel bg-blue-700 lg:hidden" aria-label="Mobile Services Navigation">
            <ul id="mobile-nav-menu" class="flex flex-col text-left p-0">
                <?php foreach ($main_categories as $cat): ?>
                    <li class="w-full border-t border-blue-600" role="menuitem">
                        <button class="category-toggle w-full text-left text-white font-semibold hover:bg-blue-600 transition p-3 flex items-center justify-between" aria-expanded="false" aria-controls="submenu-<?= $cat['id'] ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                            <svg class="ml-auto w-4 h-4 text-yellow-200 transition-transform duration-300 mobile-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <?php $services = fetch_services($conn, $cat['id']); ?>

                        <?php if ($services): ?>
                            <div class="mobile-submenu w-full bg-blue-800" id="submenu-<?= $cat['id'] ?>">
                                <ul>
                                    <?php foreach ($services as $srv): ?>
                                        <li>
                                            <a href="details?slug=<?= urlencode($srv['slug']) ?>"
                                                class="block pl-8 pr-4 py-2 text-white/90 hover:bg-blue-700 hover:text-white transition text-sm font-light">
                                                <i class="fas fa-angle-right mr-2 text-xs" aria-hidden="true"></i>
                                                <?= htmlspecialchars($srv['name']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>
    	
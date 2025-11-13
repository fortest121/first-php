<?php
require_once "common/header.php";
require_once "config/config.php";

// 🟦 Step 1: Get slug
$slug = $_GET['slug'] ?? '';
if (!$slug) {
    die("❌ No slug provided in URL.");
}

// 🟦 Step 2: Fetch service info
$stmt = $conn->prepare("
    SELECT id, name, long_desc 
    FROM service_list 
    WHERE slug = :slug 
    LIMIT 1
");
$stmt->execute(['slug' => $slug]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

// 🟦 Step 3: Validate
if (!$service) {
    die("❌ Service not found for slug: " . htmlspecialchars($slug));
}

// echo "";
?>

<div class="container mx-auto mt-12 mb-12 p-8 rounded-xl shadow-2xl border border-gray-200 bg-white">   
    <h1 class="text-4xl font-extrabold text-white mb-6 border-l-4 border-yellow-500 pl-4 hover:text-yellow-500 transition-colors duration-300">
        <?= htmlspecialchars($service['name']) ?>
    </h1>

    <div class="prose prose-invert max-w-full text-gray-300">
        <?= $service['long_desc'] ?>
    </div>
</div>

<hr class="my-10 border-gray-700">

<?php
$faqPath = __DIR__ . "/pages/faq2.php";
if (file_exists($faqPath)) {
    echo "";
    // NOTE: Ensure faq2.php content also uses the dark theme styling.
    include $faqPath;
} else {
    echo "";
}
?>

<?php include "common/footer.php"; ?>
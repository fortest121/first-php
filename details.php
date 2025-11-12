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

// echo "<!-- ✅ Debug: Service slug = {$slug}, Service ID = {$service['id']}, Service name = {$service['name']} -->";
?>

<!-- 🔹 Service Details Section -->
<div class="container mx-auto mt-10 p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 hover:text-indigo-600 transition-all duration-300 ease-in-out">
        <?= htmlspecialchars($service['name']) ?>
    </h1>

    <div class="prose max-w-full">
        <?= $service['long_desc'] ?>
    </div>
</div>

<hr class="my-8 border-gray-300">

<!-- 🔹 Include Dynamic FAQ Section -->
<?php
$faqPath = __DIR__ . "/pages/faq2.php";
if (file_exists($faqPath)) {
    echo "<!-- ✅ Including FAQ file from: {$faqPath} -->";
    include $faqPath;
} else {
    echo "<!-- ❌ FAQ file not found at {$faqPath} -->";
}
?>

<!-- 🔹 Footer -->
<?php include "common/footer.php"; ?>

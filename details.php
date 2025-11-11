
<?php
require_once "common/header.php";

$slug = $_GET['slug'] ?? '';
if (!$slug) die("Service not found.");

$stmt = $conn->prepare("SELECT * FROM service_list WHERE slug = :slug");
$stmt->execute(['slug' => $slug]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) die("Service not found.");
?>

<div class="container mx-auto mt-10 p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 hover:text-indigo-600 transition-all duration-300 ease-in-out">
    	<?= htmlspecialchars($service['name']) ?>
	</h1>

    <div class="prose max-w-full">
        <?= $service['long_desc'] ?>
    </div>
</div>

<?php require_once "pages/faq2.php"; ?>
<?php require_once "common/footer.php"; ?>

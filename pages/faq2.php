<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 🟦 Check if service data exists
if (!isset($service) || empty($service['id'])) {
    echo "<p style='color:red;'>❌ Service variable not found or empty in faq2.php</p>";
    return;
}

// 🟦 Check DB connection
if (!isset($conn)) {
    echo "<p style='color:red;'>❌ Database connection not found!</p>";
    return;
}

// 🟩 Fetch FAQs
$faqStmt = $conn->prepare("
    SELECT id, question, answer 
    FROM faq_list 
    WHERE service_id = :sid AND status = 1
    ORDER BY id ASC
");
$faqStmt->execute(['sid' => $service['id']]);
$faqs = $faqStmt->fetchAll(PDO::FETCH_ASSOC);

// 🟦 Fetch Sidebar Cards (Dynamic)
$sidebarStmt = $conn->prepare("
    SELECT title, subtitle, image_url, link_url
    FROM service_sidebar_images
    WHERE service_id = :sid
    ORDER BY id DESC
");
$sidebarStmt->execute(['sid' => $service['id']]);
$sidebarCards = $sidebarStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-12 items-start">

            <!-- 🟩 FAQ Column -->
            <div class="md:col-span-2 space-y-6">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-8 text-center md:text-left">
                    FAQ’s on <?= htmlspecialchars($service['name']) ?>
                </h2>

                <div id="faq-list" class="bg-white rounded-lg shadow-md p-6">
                    <?php if (!empty($faqs)): ?>
                        <?php foreach ($faqs as $index => $faq): ?>
                            <details 
                                class="group py-4 <?= ($index !== count($faqs) - 1) ? 'border-b border-gray-200' : '' ?> cursor-pointer"
                            >
                                <summary class="flex justify-between items-center text-lg font-semibold text-gray-700 hover:text-blue-600 transition">
                                    <span class="flex-1 pr-4"><?= htmlspecialchars($faq['question']) ?></span>
                                    <span class="group-open:rotate-45 transition-transform text-2xl text-gray-500 flex-shrink-0">+</span>
                                </summary>
                                <p class="mt-4 text-gray-600 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                                </p>
                            </details>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-600 italic">No FAQs available for this service.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 🟦 Sidebar Column -->
            <div class="md:col-span-1 mt-12 md:mt-0 space-y-8">
                <?php if (!empty($sidebarCards)): ?>
                    <?php foreach ($sidebarCards as $card): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                            <a href="<?= htmlspecialchars($card['link_url']) ?>" target="_blank">
                                <div class="p-5 bg-blue-700 text-white">
                                    <h3 class="text-xl font-bold mb-1"><?= htmlspecialchars($card['title']) ?></h3>
                                    <p class="text-sm opacity-90"><?= htmlspecialchars($card['subtitle']) ?></p>
                                </div>
                                <!-- ✅ FIXED IMAGE SIZE -->
                                <div class="relative w-full h-48 overflow-hidden bg-gray-100">
                                    <img src="<?= htmlspecialchars($card['image_url']) ?>" 
                                         alt="<?= htmlspecialchars($card['title']) ?>" 
                                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600 italic">No sidebar images available for this service.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

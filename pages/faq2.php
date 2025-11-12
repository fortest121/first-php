<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 🟦 DEBUG: Check if service data exists
if (!isset($service) || empty($service['id'])) {
    echo "<p style='color:red;'>❌ Service variable not found or empty in faq2.php</p>";
    return;
}

// 🟦 DEBUG: Check DB connection
if (!isset($conn)) {
    echo "<p style='color:red;'>❌ Database connection not found!</p>";
    return;
}

// 🟦 Fetch FAQ data
$faqStmt = $conn->prepare("
    SELECT id, question, answer, service_id
    FROM faq_list 
    WHERE service_id = :sid AND status = 1
    ORDER BY id ASC
");
$faqStmt->execute(['sid' => $service['id']]);
$faqs = $faqStmt->fetchAll(PDO::FETCH_ASSOC);

// echo "<p style='color:blue;'>ℹ️ " . count($faqs) . " FAQs fetched for service_id = {$service['id']}</p>";
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
                <!-- Sidebar Card 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-5 bg-blue-700 text-white">
                        <h3 class="text-xl font-bold mb-1">GST LUT FORM</h3>
                        <p class="text-sm opacity-90">Filing of GST LUT Form for Exporters - clientfilingindia</p>
                    </div>
                    <div class="relative h-40 overflow-hidden">
                        <img src="assets/images/container3.jpg" alt="Shipping port with containers" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <p class="text-lg font-semibold text-gray-800">GST LUT Form</p>
                    </div>
                </div>

                <!-- Sidebar Card 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-5 bg-blue-700 text-white">
                        <h3 class="text-xl font-bold mb-1">GST ANNUAL RETURN FILING (GSTR-9)</h3>
                        <p class="text-sm opacity-90">GST annual return filing for registered taxpayers</p>
                    </div>
                    <div class="relative h-40 overflow-hidden">
                        <img src="assets/images/tax2.jpg" alt="Tax documents and calculator" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <p class="text-lg font-semibold text-gray-800">GST Annual Return (GSTR-9)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 🟨 Optional JS: -->

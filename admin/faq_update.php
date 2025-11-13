<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
// NOTE: Session and admin checks are correctly handled in the parent file (dashboard2.php)
require_once __DIR__ . "/../config/config.php";

// Session check (assuming session_start() is in dashboard2.php)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/dashboard2.php");
    exit();
}

// Fetch all services
$servicesStmt = $conn->prepare("SELECT id, name FROM service_list WHERE status = 1 ORDER BY name ASC");
$servicesStmt->execute();
$services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables
$service_id = isset($_GET['service_id']) ? $_GET['service_id'] : null;
$selectedServiceName = '';

// If a service is selected
if ($service_id) {
    // Get service name for display
    foreach ($services as $service) {
        if ($service['id'] == $service_id) {
            $selectedServiceName = $service['name'];
            break;
        }
    }

    // Fetch existing FAQs
    $faqStmt = $conn->prepare("SELECT * FROM faq_list WHERE service_id = :service_id ORDER BY id ASC");
    $faqStmt->execute(['service_id' => $service_id]);
    $faqs = $faqStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle form submission (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // We need to know which page we're on for the redirect after POST
    $current_page_name = 'faq_update'; 

    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $faq_id = isset($_POST['faq_id']) ? $_POST['faq_id'] : 0;
    $service_id = $_POST['service_id'];

    if ($faq_id > 0) {
        $stmt = $conn->prepare("UPDATE faq_list SET question = :question, answer = :answer WHERE id = :faq_id");
        $stmt->execute(['question' => $question, 'answer' => $answer, 'faq_id' => $faq_id]);
        $success_message = "FAQ updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO faq_list (service_id, question, answer, status) VALUES (:service_id, :question, :answer, 1)");
        $stmt->execute(['service_id' => $service_id, 'question' => $question, 'answer' => $answer]);
        $success_message = "FAQ added successfully!";
    }

    // Redirect back to the dashboard's FAQ page with the selected service ID
    // We use relative path to dashboard2.php which is the parent file
    header("Location: dashboard2.php?page=$current_page_name&service_id=$service_id");
    exit();
}

// If editing an FAQ (GET request)
$editFAQ = null;
if (isset($_GET['edit'])) {
    $faq_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM faq_list WHERE id = :faq_id");
    $stmt->execute(['faq_id' => $faq_id]);
    $editFAQ = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="container mx-auto p-0 sm:p-6">
    <h2 class="text-3xl font-bold text-indigo-500 mb-6">Update FAQs</h2>

    <form method="GET" class="bg-gray-800 p-6 rounded-lg shadow-lg mb-6">
        
        <input type="hidden" name="page" value="faq_update"> 
        
        <label for="service_id" class="block font-medium mb-2 text-gray-300">Select Service:</label>
        <select id="service_id" name="service_id" class="w-full p-2 mb-4 border rounded bg-gray-700 text-white focus:ring-indigo-500 focus:border-indigo-500" required>
            <option value="">Select Service</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= $service['id'] ?>" <?= ($service['id'] == $service_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($service['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition">Load FAQs</button>
    </form>

    <?php if ($service_id): ?>
        <h3 class="text-2xl font-semibold text-indigo-400 mb-4">FAQs for: <?= htmlspecialchars($selectedServiceName) ?></h3>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-600 text-white p-4 mb-4 rounded-lg shadow-md"><?= $success_message ?></div>
        <?php endif; ?>

        <form method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg mb-6">
            <div>
                <label for="question" class="block font-medium mb-2 text-gray-300">Question:</label>
                <input type="text" id="question" name="question" class="w-full p-2 mb-4 border rounded bg-gray-700 text-white focus:ring-indigo-500 focus:border-indigo-500" 
                       value="<?= isset($editFAQ) ? htmlspecialchars($editFAQ['question']) : '' ?>" required>
            </div>

            <div>
                <label for="answer" class="block font-medium mb-2 text-gray-300">Answer:</label>
                <textarea id="answer" name="answer" class="w-full p-2 mb-4 border rounded bg-gray-700 text-white focus:ring-indigo-500 focus:border-indigo-500" rows="4" required><?= isset($editFAQ) ? htmlspecialchars($editFAQ['answer']) : '' ?></textarea>
            </div>

            <input type="hidden" name="service_id" value="<?= $service_id ?>">
            <?php if ($editFAQ): ?>
                <input type="hidden" name="faq_id" value="<?= $editFAQ['id'] ?>">
            <?php endif; ?>

            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition">Save FAQ</button>
        </form>

        <h3 class="text-2xl font-semibold text-indigo-400 mb-4">Existing FAQs</h3>
        
        <div class="overflow-x-auto shadow-lg rounded-lg"> 
            <table class="min-w-full table-auto bg-gray-800 text-white divide-y divide-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Question</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Answer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($faqs as $faq): ?>
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-4 py-3 font-medium text-sm"><?= htmlspecialchars($faq['question']) ?></td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell"><?= htmlspecialchars(substr($faq['answer'], 0, 50)) . (strlen($faq['answer']) > 50 ? '...' : '') ?></td>
                            <td class="px-4 py-3">
                                <a href="dashboard2.php?page=faq_update&service_id=<?= $service_id ?>&edit=<?= $faq['id'] ?>" class="text-indigo-400 hover:text-indigo-300 font-medium">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($faqs)): ?>
                         <tr><td colspan="3" class="text-center py-4 text-gray-400">No FAQs found for this service.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    <?php endif; ?>
</div>
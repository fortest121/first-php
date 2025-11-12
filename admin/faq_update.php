<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once __DIR__ . "/../config/config.php";

// Session and admin login checks
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Refresh FAQs after update
    header("Location: ?service_id=$service_id");
    exit();
}

// If editing an FAQ
$editFAQ = null;
if (isset($_GET['edit'])) {
    $faq_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM faq_list WHERE id = :faq_id");
    $stmt->execute(['faq_id' => $faq_id]);
    $editFAQ = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Admin Panel - FAQ Update</title>
</head>
<body class="bg-gray-900 text-white">

<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-indigo-500 mb-6">Update FAQs</h2>

    <!-- Service Dropdown -->
    <form method="GET" class="bg-gray-800 p-6 rounded-lg shadow-lg mb-6">
        <label for="service_id" class="block font-medium mb-2">Select Service:</label>
        <select id="service_id" name="service_id" class="w-full p-2 mb-4 border rounded bg-gray-700 text-white" required>
            <option value="">Select Service</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= $service['id'] ?>" <?= ($service['id'] == $service_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($service['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Load FAQs</button>
    </form>

    <?php if ($service_id): ?>
        <h3 class="text-2xl font-semibold text-indigo-500 mb-4">FAQs for: <?= htmlspecialchars($selectedServiceName) ?></h3>

        <?php if (isset($success_message)): ?>
            <div class="bg-green-600 text-white p-4 mb-4 rounded-lg"><?= $success_message ?></div>
        <?php endif; ?>

        <!-- FAQ Form -->
        <form method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg mb-6">
            <div>
                <label for="question" class="block font-medium mb-2">Question:</label>
                <input type="text" id="question" name="question" class="w-full p-2 mb-4 border rounded bg-gray-700 text-white" 
                       value="<?= isset($editFAQ) ? htmlspecialchars($editFAQ['question']) : '' ?>" required>
            </div>

            <div>
                <label for="answer" class="block font-medium mb-2">Answer:</label>
                <textarea id="answer" name="answer" class="w-full p-2 mb-4 border rounded bg-gray-700 text-white" rows="4" required><?= isset($editFAQ) ? htmlspecialchars($editFAQ['answer']) : '' ?></textarea>
            </div>

            <input type="hidden" name="service_id" value="<?= $service_id ?>">
            <?php if ($editFAQ): ?>
                <input type="hidden" name="faq_id" value="<?= $editFAQ['id'] ?>">
            <?php endif; ?>

            <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Save FAQ</button>
        </form>

        <!-- Existing FAQs -->
        <h3 class="text-2xl font-semibold text-indigo-500 mb-4">Existing FAQs</h3>
        <table class="min-w-full table-auto bg-gray-800 text-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">Question</th>
                    <th class="px-4 py-2">Answer</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td class="px-4 py-2"><?= htmlspecialchars($faq['question']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($faq['answer']) ?></td>
                        <td class="px-4 py-2">
                            <a href="?service_id=<?= $service_id ?>&edit=<?= $faq['id'] ?>" class="text-indigo-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="mt-4"><a href="admin.php" class="text-indigo-600 hover:underline">← Back to Services List</a></p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
/**
 * Service Page Content Management Script (service_page_upload.php)
 * Allows administrators to edit the 'long_desc' (HTML content) for individual services.
 */
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// NOTE: Assuming this file is in /admin, config is at project root /config/config.php
require_once __DIR__ . "/../config/config.php";

// Session check (assuming session_start() is in dashboard2.php)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/dashboard2.php");
    exit();
}

// Initialize messages
$success = null;
$error = null;
$current_page_name = 'service_update'; // Used for redirects

// --- HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'], $_POST['service_id'])) {
    $long_desc = $_POST['long_desc'] ?? '';
    $service_id = $_POST['service_id'];

    if (empty($long_desc)) {
        $error = "Content cannot be empty.";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE service_list SET long_desc = :long_desc WHERE id = :id");
            $stmt->execute([
                'long_desc' => $long_desc,
                'id' => $service_id
            ]);
            $success = "Service content updated successfully! 🚀";
            
            // FIX: Redirect back to the dashboard context after POST
            header("Location: dashboard2.php?page=$current_page_name&edit=$service_id");
            exit();

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// --- DATA FETCHING ---

// Fetch all services for the list
try {
    $services = $conn->query("SELECT id, name, slug FROM service_list")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Could not load service list: " . $e->getMessage();
    $services = [];
}


// If editing a specific service
$editService = null;
if (isset($_GET['edit'])) {
    $serviceId = intval($_GET['edit']);
    try {
        $stmt = $conn->prepare("SELECT * FROM service_list WHERE id = :id");
        $stmt->execute(['id' => $serviceId]);
        $editService = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$editService) {
            $error = "Service not found.";
        }
    } catch (PDOException $e) {
        $error = "Error fetching service details: " . $e->getMessage();
    }
}
?>

<div class="container mx-auto p-0 sm:p-6">
    <h2 class="text-3xl font-bold text-indigo-500 mb-6">Service Page Content Editor</h2>
    
    <?php if ($success): ?>
        <div id="successMessage" class="bg-green-700 p-3 rounded-lg mb-6 text-green-100 font-semibold shadow-md">
            <?= $success ?>
        </div>
    <?php elseif ($error): ?>
        <div class="bg-red-700 p-3 rounded-lg mb-6 text-red-100 font-semibold shadow-md">
            Error: <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if ($editService): ?>
    <div class="bg-gray-800 shadow-xl rounded-lg p-8 mb-8">
        <h3 class="text-2xl font-semibold text-gray-100 mb-6">Editing Content for: <span class="text-yellow-400"><?= htmlspecialchars($editService['name']) ?></span></h3>
        
        <form method="POST">
            <input type="hidden" name="service_id" value="<?= $editService['id'] ?>">
            
            <label class="block font-medium mb-2 text-gray-300">
                Long Description Content (HTML/Tailwind)
            </label>
            <textarea id="code_editor" name="long_desc" class="w-full h-96 border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-700 text-white font-mono text-sm"><?= htmlspecialchars($editService['long_desc'] ?? '') ?></textarea>
            
            <div class="mt-6 space-x-4">
                <button type="button" id="copyButton" class="bg-green-600 text-white py-2 px-6 rounded-lg hover:bg-green-700 transition shadow-md">
                    📋 Copy Code
                </button>
                
                <button type="submit" name="update" class="bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700 transition shadow-md">
                    💾 Save & Update
                </button>
            </div>
        </form>
    </div>
    <p class="mt-4"><a href="dashboard2.php?page=<?= $current_page_name ?>" class="text-indigo-400 hover:text-indigo-300 hover:underline">← Back to Services List</a></p>

    <?php else: ?>
    <div class="bg-gray-800 shadow-xl rounded-lg p-8">
        <h3 class="text-2xl font-semibold text-gray-100 mb-4">Select a Service to Edit</h3>
        <ul class="space-y-4">
            <?php foreach ($services as $service): ?>
                <li class="flex justify-between items-center border-b border-gray-700 py-3">
                    <span class="text-lg text-gray-300"><?= htmlspecialchars($service['name']) ?></span>
                    <a href="dashboard2.php?page=<?= $current_page_name ?>&edit=<?= $service['id'] ?>" class="text-indigo-500 hover:text-indigo-400 font-medium transition duration-150 py-1 px-4 border border-indigo-500 rounded-md hover:bg-indigo-500 hover:text-white">
                        ✏️ Edit Content
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</div>

<script>
    // Initialize the CodeMirror editor... (etc)
    const codeEditor = CodeMirror.fromTextArea(document.getElementById('code_editor'), {
        mode: 'htmlmixed', // Handles HTML, CSS, JS, and PHP
        lineNumbers: true,
        theme: 'dracula', // Dark theme
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        lineWrapping: true,
        smartIndent: true,
        tabMode: 'indent'
    });

    // Copy All functionality... (etc)
    document.getElementById('copyButton')?.addEventListener('click', function() {
        const code = codeEditor.getValue();
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code).then(() => {
                this.textContent = 'Copied! ✅';
                setTimeout(() => { this.textContent = '📋 Copy Code'; }, 2000);
            }).catch(err => {
                console.error('Could not copy text: ', err);
                alert('Could not copy text. Fallback required.');
            });
        } else {
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = code;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            document.execCommand('copy');
            document.body.removeChild(tempTextArea);
            // alert('Code copied to clipboard!');
            
        }
    });
    
    // Auto-hide success message
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 5000);
    }
</script>
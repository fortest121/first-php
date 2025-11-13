<?php
declare(strict_types=1);

// --- Configuration and Initialization ---
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Base page name for redirects inside the dashboard
$current_page_name = 'update_sidebar_image'; 
$redirect_base = "dashboard2.php?page=$current_page_name";

require_once __DIR__ . '/../config/config.php'; 
require_once __DIR__ . '/../vendor/autoload.php';

// Session check (assuming session_start() is in dashboard2.php)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/dashboard2.php");
    exit();
}

use Cloudinary\Cloudinary;

// Initialize Cloudinary SDK
$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'dys8on0yh',
        'api_key'    => '957361161539531',
        'api_secret' => 'y-Mkkvmzq3qnEL2LmEHyEsWVCok',
    ],
    'url' => ['secure' => true],
]);

// Initialize message and error variables
$msg   = '';
$error = '';

// --- Data Fetching ---

try {
    // Fetch active services for the dropdown
    $service_query = $conn->prepare("SELECT id, name FROM service_list WHERE status = 1 ORDER BY name ASC");
    $service_query->execute();
    $services = $service_query->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all existing sidebar images for display
    $stmt = $conn->query("
        SELECT ssi.id, ssi.service_id, ssi.title, ssi.subtitle, ssi.image_url, sl.name AS service_name
        FROM service_sidebar_images ssi
        JOIN service_list sl ON ssi.service_id = sl.id
        ORDER BY ssi.id DESC
    ");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $services = [];
    $images = [];
}

// --- Request Handlers (URL Messages, Delete, and Form Submission) ---

// Handle URL messages and errors
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
} elseif (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $delStmt = $conn->prepare("DELETE FROM service_sidebar_images WHERE id = :id");
        $delStmt->execute([':id' => $id]);
        
        // FIX: Use dashboard context for redirect
        header("Location: " . $redirect_base . "&msg=" . urlencode("Image deleted successfully."));
    } catch (PDOException $e) {
        // FIX: Use dashboard context for redirect
        header("Location: " . $redirect_base . "&error=" . urlencode("Deletion failed: " . $e->getMessage()));
    }
    exit;
}

// Handle form submission (insert or update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id  = $_POST['service_id'] ?? null;
    $title       = $_POST['title'] ?? '';
    $subtitle    = $_POST['subtitle'] ?? '';
    $id          = $_POST['id'] ?? null;
    $image       = $_FILES['image'] ?? null;

    if (!$service_id) {
        $error = "Please select a service.";
    } else {
        try {
            $secure_url = null;
            $new_image_uploaded = ($image && $image['error'] === 0);

            if ($new_image_uploaded) {
                // Upload image to Cloudinary
                $upload_result = $cloudinary->uploadApi()->upload($image['tmp_name']);
                $secure_url    = $upload_result['secure_url'];
            }

            if ($id) {
                // Update existing record
                $query = "
                    UPDATE service_sidebar_images 
                    SET service_id = :sid, title = :title, subtitle = :subtitle" .
                    ($secure_url ? ", image_url = :img, link_url = :img" : "") .
                    " WHERE id = :id";
                
                $stmt = $conn->prepare($query);
                $params = [
                    ':sid'       => $service_id,
                    ':title'     => $title,
                    ':subtitle'  => $subtitle,
                    ':id'        => $id,
                ];
                if ($secure_url) {
                    $params[':img'] = $secure_url;
                }

                $stmt->execute($params);
                $msg = "Sidebar image updated successfully!";

            } else {
                // Insert new record

                // Limit check (max 2 per service)
                $count_stmt = $conn->prepare("SELECT COUNT(*) FROM service_sidebar_images WHERE service_id = :sid");
                $count_stmt->execute([':sid' => $service_id]);
                if ($count_stmt->fetchColumn() >= 2) {
                    throw new Exception("You can upload only 2 images per service.");
                }

                if (!$new_image_uploaded) {
                     throw new Exception("An image file is required for new uploads.");
                }

                $stmt = $conn->prepare("
                    INSERT INTO service_sidebar_images (service_id, title, subtitle, image_url, link_url)
                    VALUES (:sid, :title, :subtitle, :img, :img)
                ");
                $stmt->execute([
                    ':sid'       => $service_id,
                    ':title'     => $title,
                    ':subtitle'  => $subtitle,
                    ':img'       => $secure_url,
                ]);
                $msg = "Sidebar image uploaded successfully!";
            }

            // FIX: Use dashboard context for redirect
            header("Location: " . $redirect_base . "&msg=" . urlencode($msg));
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<div class="max-w-7xl mx-auto p-0 sm:p-4 md:p-8">

    <header class="mb-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 border-b border-gray-700 pb-2">
            🖼️ Sidebar Image Management
        </h1>
    </header>

    <?php if (!empty($msg)): ?>
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-6 rounded-lg" role="alert">
            <p class="font-bold">Success!</p>
            <p><?= $msg ?></p>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-6 rounded-lg" role="alert">
            <p class="font-bold">Error!</p>
            <p><?= $error ?></p>
        </div>
    <?php endif; ?>

    <section class="mb-10 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg transition-shadow duration-300 hover:shadow-2xl">
        <h2 class="text-2xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400" id="form-title">
            Upload New Sidebar Image
        </h2>
        
        <form id="imageForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id" id="image_id">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service <span class="text-red-500">*</span></label>
                    <select name="service_id" id="service_id" required 
                        class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select Service --</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                    <input type="text" name="title" id="title" placeholder="E.g., Special Offer"
                        class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label for="subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" placeholder="E.g., Book now and save 10%"
                        class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Image (New/Update)</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-600 dark:file:text-gray-50 dark:hover:file:bg-gray-500">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="image-note">Image is required for new uploads, optional for updates.</p>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" id="submitBtn"
                    class="w-full md:w-auto px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    💾 Upload Image
                </button>
                <button type="button" id="cancelBtn" onclick="resetForm()"
                    class="w-full md:w-auto mt-2 md:mt-0 md:ml-4 px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition duration-150 ease-in-out" style="display:none;">
                    ↩️ Cancel Edit
                </button>
            </div>
        </form>
    </section>
    
    <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <h3 class="text-2xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">
            Current Sidebar Images
        </h3>
        
        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">#</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Service</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Title</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Subtitle</th>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Image</th>
                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($images as $i => $img): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150" data-id="<?= $img['id'] ?>">
                            <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"><?= $i + 1 ?></td>
                            <td class="px-2 py-4 text-sm text-gray-500 dark:text-gray-300 truncate max-w-[100px]"><?= htmlspecialchars($img['service_name']) ?></td>
                            <td class="px-2 py-4 text-sm text-gray-500 dark:text-gray-300 truncate max-w-[100px]"><?= htmlspecialchars($img['title']) ?></td>
                            <td class="px-2 py-4 text-sm text-gray-500 dark:text-gray-300 truncate max-w-[100px]"><?= htmlspecialchars($img['subtitle']) ?></td>
                            <td class="px-2 py-4 text-sm text-gray-500 dark:text-gray-300">
                                <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="Sidebar Image" class="w-16 h-12 object-cover rounded-md shadow">
                            </td>
                            <td class="px-2 py-4 text-center text-sm font-medium">
                                <div class="flex flex-col items-center space-y-2">
                                    <button
                                        onclick="editImage(<?= htmlspecialchars(json_encode($img)) ?>)"
                                        class="w-full text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 inline-flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm bg-indigo-50 hover:bg-indigo-100 transition duration-150 ease-in-out"
                                    >
                                        <span class="mr-1">✏️</span> Edit
                                    </button>
                                    <a href="<?= $redirect_base ?>&delete=<?= $img['id'] ?>"
                                        onclick="return confirm('Are you sure you want to delete this image? This action is permanent.');"
                                        class="w-full text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 inline-flex justify-center items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm bg-red-50 hover:bg-red-100 transition duration-150 ease-in-out"
                                    >
                                        <span class="mr-1">🗑️</span> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($images)): ?>
                        <tr><td colspan="6" class="px-2 py-4 text-center text-gray-500 dark:text-gray-400">No images uploaded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    function editImage(img) {
        document.getElementById('image_id').value = img.id;
        document.getElementById('service_id').value = img.service_id;
        document.getElementById('title').value = img.title;
        document.getElementById('subtitle').value = img.subtitle;
        
        // Update UI for Edit Mode
        document.getElementById('form-title').textContent = "✏️ Edit Sidebar Image (ID: " + img.id + ")";
        document.getElementById('submitBtn').textContent = "🔄 Update Image";
        
        // Change button color to green for update
        document.getElementById('submitBtn').classList.remove('bg-indigo-600', 'hover:bg-indigo-700', 'focus:ring-indigo-500');
        document.getElementById('submitBtn').classList.add('bg-green-600', 'hover:bg-green-700', 'focus:ring-green-500');
        document.getElementById('image-note').textContent = "Upload a file to replace the existing image, otherwise leave blank.";
        document.getElementById('cancelBtn').style.display = 'inline-flex';

        // Scroll to the form
        document.getElementById('imageForm').scrollIntoView({ behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('imageForm').reset();
        document.getElementById('image_id').value = '';
        
        // Reset UI to Upload Mode
        document.getElementById('form-title').textContent = "Upload New Sidebar Image";
        document.getElementById('submitBtn').textContent = "💾 Upload Image";
        
        // Change button color back to indigo
        document.getElementById('submitBtn').classList.remove('bg-green-600', 'hover:bg-green-700', 'focus:ring-green-500');
        document.getElementById('submitBtn').classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'focus:ring-indigo-500');
        document.getElementById('image-note').textContent = "Image is required for new uploads, optional for updates.";
        document.getElementById('cancelBtn').style.display = 'none';
    }
</script>
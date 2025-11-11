<?php
    
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); // Also show startup errors
    

session_start();
require_once __DIR__ . "/../config/config.php";

$site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
// echo $site_url;
// Handle login via database
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from DB
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        // echo $site_url;
        header("Location: " . $site_url . "/india/admin/admin.php");
        exit(); 
    } else {
        $error = "Incorrect username or password!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    // echo $site_url;
    header("Location: " . $site_url . "/india/admin/admin.php");
    exit(); 
}

// Show login form if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Admin Login</title>
    </head>
    <body class="bg-gray-900 flex items-center justify-center min-h-screen text-white">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-2xl font-semibold mb-4 text-center text-indigo-500">Admin Login</h2>
            <?php if (isset($error)) echo "<p class='text-red-400 mb-4'>$error</p>"; ?>
            <form method="POST" class="space-y-4">
                <input type="text" name="username" placeholder="Username" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-700 text-white" required>
                <input type="password" name="password" placeholder="Password" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-700 text-white" required>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Handle update
if (isset($_POST['update']) && isset($_POST['service_id'])) {
    $long_desc = $_POST['long_desc'];
    $stmt = $conn->prepare("UPDATE service_list SET long_desc = :long_desc WHERE id = :id");
    $stmt->execute([
        'long_desc' => $long_desc,
        'id' => $_POST['service_id']
    ]);
    $success = "Service updated successfully!";
}

// Fetch services
$services = $conn->query("SELECT id, name, slug FROM service_list")->fetchAll(PDO::FETCH_ASSOC);

// If editing a specific service
$editService = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM service_list WHERE id = :id");
    $stmt->execute(['id' => $_GET['edit']]);
    $editService = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/lib/codemirror.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/htmlmixed/htmlmixed.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/css/css.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/javascript/javascript.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/mode/php/php.js"></script>
<link href="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/lib/codemirror.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/codemirror@5.62.0/theme/dracula.css" rel="stylesheet">
<title>Admin Panel</title>
</head>
<body class="bg-gray-900 min-h-screen text-white">

<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-indigo-500">Admin Panel</h2>
        <a href="admin.php?logout=1" class="text-red-600 hover:underline">Logout</a>
    </div>

<?php if ($editService): ?>
    <div class="bg-gray-800 shadow-lg rounded-lg p-8 mb-8">
        <h3 class="text-2xl font-semibold text-gray-100 mb-4">Edit Service: <?= htmlspecialchars($editService['name']) ?></h3>
        <?php if (isset($success)): ?>
            <p id="successMessage" class="text-green-400 mb-4"><?= $success ?></p>
            <script>
                setTimeout(function() {
                    // Hide the success message after 5 seconds
                    document.getElementById('successMessage').style.display = 'none';
                }, 5000);
            </script>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="service_id" value="<?= $editService['id'] ?>">
            <label class="block font-medium mb-2">Long Description (HTML, CSS, JS, PHP)</label>
            <textarea id="code_editor" name="long_desc" class="w-full h-60 border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-700 text-white"><?= htmlspecialchars($editService['long_desc']) ?></textarea>
            
            <!-- Copy All Button -->
            <button type="button" id="copyButton" class="mt-4 bg-green-600 text-white py-2 px-6 rounded hover:bg-green-700 transition">
                Copy All
            </button>
            
            <button type="submit" name="update" class="mt-4 bg-indigo-600 text-white py-2 px-6 rounded hover:bg-indigo-700 transition">Update</button>
        </form>
    </div>
    <p class="mt-4"><a href="admin.php" class="text-indigo-600 hover:underline">← Back to services list</a></p>

<?php else: ?>
    <div class="bg-gray-800 shadow-lg rounded-lg p-8">
        <h3 class="text-2xl font-semibold text-gray-100 mb-4">Services List</h3>
        <ul class="space-y-4">
            <?php foreach ($services as $service): ?>
                <li class="flex justify-between items-center border-b py-4">
                    <span class="text-lg text-gray-300"><?= htmlspecialchars($service['name']) ?></span>
                    <a href="admin.php?edit=<?= $service['id'] ?>" class="text-indigo-500 hover:underline">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

</div>

<script>
// Initialize the CodeMirror editor with a dark theme
const codeEditor = CodeMirror.fromTextArea(document.getElementById('code_editor'), {
    mode: 'htmlmixed', // This will handle HTML, CSS, JS, and PHP
    lineNumbers: true,
    theme: 'dracula', // A popular dark theme for CodeMirror
    matchBrackets: true,
    autoCloseBrackets: true,
    extraKeys: { "Ctrl-Space": "autocomplete" },
    indentUnit: 4,
    lineWrapping: true,
    smartIndent: true,
    tabMode: 'indent'
});

// Copy All functionality
document.getElementById('copyButton').addEventListener('click', function() {
    // Get the code from the CodeMirror editor
    const code = codeEditor.getValue();

    // Create a temporary textarea to use for copying
    const tempTextArea = document.createElement('textarea');
    tempTextArea.value = code;
    document.body.appendChild(tempTextArea);
    
    // Select the content of the textarea
    tempTextArea.select();
    tempTextArea.setSelectionRange(0, 99999); // For mobile devices

    // Copy the content to the clipboard
    document.execCommand('copy');
    
    // Remove the temporary textarea
    document.body.removeChild(tempTextArea);

    // Optionally, show a confirmation message or feedback
    alert('Code copied to clipboard!');
});
</script>

</body>
</html>

<?php
session_start();
require_once "config/config.php";

// 🔹 Check if admin logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin/admin_auth.php");
    exit;
}

// 🔹 Handle logout link
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin/admin_auth.php");
    exit;
}

// 🔹 Handle update
if (isset($_POST['update']) && isset($_POST['service_id'])) {
    $stmt = $conn->prepare("UPDATE service_list SET long_desc = :long_desc WHERE id = :id");
    $stmt->execute([
        'long_desc' => $_POST['long_desc'],
        'id' => $_POST['service_id']
    ]);
    $success = "Service updated successfully!";
}

// 🔹 Fetch all services
$services = $conn->query("SELECT id, name, slug FROM service_list ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Fetch service for editing
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
<title>Admin Dashboard</title>
</head>
<body class="bg-gray-900 text-white min-h-screen">
<div class="container mx-auto p-6">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-indigo-500">Admin Dashboard</h2>
    <a href="dashboard.php?logout=1" class="text-red-600 hover:underline">Logout</a>
  </div>

  <?php if ($editService): ?>
  <div class="bg-gray-800 p-8 rounded-lg shadow-lg mb-8">
    <h3 class="text-2xl font-semibold mb-4">Editing: <?= htmlspecialchars($editService['name']) ?></h3>
    <?php if (isset($success)): ?>
      <p id="successMessage" class="text-green-400 mb-4"><?= $success ?></p>
      <script>
        setTimeout(()=>document.getElementById('successMessage').style.display='none',5000);
      </script>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="service_id" value="<?= $editService['id'] ?>">
      <label class="block mb-2 font-medium">Long Description</label>
      <textarea id="code_editor" name="long_desc" class="w-full h-60 border rounded-lg p-3 bg-gray-700 text-white"><?= htmlspecialchars($editService['long_desc']) ?></textarea>
      <div class="mt-4 flex gap-3">
        <button type="button" id="copyButton" class="bg-green-600 px-4 py-2 rounded hover:bg-green-700">Copy All</button>
        <button type="submit" name="update" class="bg-indigo-600 px-4 py-2 rounded hover:bg-indigo-700">Update</button>
      </div>
    </form>
    <p class="mt-4"><a href="dashboard.php" class="text-indigo-400 hover:underline">← Back to list</a></p>
  </div>

  <?php else: ?>
  <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
    <h3 class="text-2xl font-semibold mb-4">Services List</h3>
    <ul class="space-y-4">
      <?php foreach ($services as $s): ?>
      <li class="flex justify-between border-b py-3">
        <span><?= htmlspecialchars($s['name']) ?></span>
        <a href="dashboard.php?edit=<?= $s['id'] ?>" class="text-indigo-500 hover:underline">Edit</a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>
</div>

<script>
const editor = document.getElementById('code_editor');
if (editor) {
  const codeEditor = CodeMirror.fromTextArea(editor, {
    mode: 'htmlmixed',
    lineNumbers: true,
    theme: 'dracula',
    matchBrackets: true,
    autoCloseBrackets: true,
    lineWrapping: true
  });

  document.getElementById('copyButton').addEventListener('click', () => {
    const temp = document.createElement('textarea');
    temp.value = codeEditor.getValue();
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);
    alert('Copied to clipboard!');
  });
}
</script>
</body>
</html>

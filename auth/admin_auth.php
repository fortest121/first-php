<?php
session_start();
// NOTE: Assuming config.php is located outside the 'auth' folder, 
// the path 'config/config.php' might need adjustment based on your exact structure.
require_once "../config/config.php";

// 🔹 Handle login
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        
        // 🌟 CHANGE IS HERE 🌟
        // Redirect to dashboard2.php inside the 'admin' folder
        // The path 'Location: ../admin/dashboard2.php' is relative to the current script's location (auth/admin_auth.php)
        header("Location: ../admin/dashboard2.php"); 
        exit;
    } else {
        $error = "Incorrect username or password!";
    }
}

// 🔹 Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    // Redirect back to the login script itself
    header("Location: admin_auth.php"); 
    exit;
}

// 🔹 Show login form if not logged in
if (!isset($_SESSION['admin_logged_in'])):
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
      <input type="text" name="username" placeholder="Username" class="w-full border rounded px-3 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-indigo-500" required>
      <input type="password" name="password" placeholder="Password" class="w-full border rounded px-3 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-indigo-500" required>
      <button type="submit" class="w-full bg-indigo-600 py-2 rounded hover:bg-indigo-700 transition">Login</button>
    </form>
  </div>
</body>
</html>
<?php
exit;
endif;
?>
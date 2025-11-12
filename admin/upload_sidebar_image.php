<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug: Echo the path to ensure the correct file is being included
$autoload_path = __DIR__ . '/../vendor/autoload.php'; 
echo "Autoloader Path: " . $autoload_path . "<br>";  // Debug the autoloader path

// Include Composer's autoloader
require_once $autoload_path;  // This is the correct path

// Cloudinary configuration
\Cloudinary::config(array(
    "cloud_name" => "dys8on0yh",
    "api_key" => "957361161539531",
    "api_secret" => "y-Mkkvmzq3qnEL2LmEHyEsWVCok"
));


// echo "i am raja";

// Debug: Check Cloudinary connection status
echo "<pre>";
echo "Cloudinary Config: ";
var_dump(\Cloudinary::config()); // Debug Cloudinary config
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];
    
    // Debugging the file upload array to see the image details
    echo "<pre>";
    echo "Image Details ($_FILES['image']): ";
    var_dump($image);
    echo "</pre>";
    
    // Check for any errors with the uploaded file
    if ($image['error'] != 0) {
        echo "<p style='color:red;'>Error uploading the image. Error code: " . $image['error'] . "</p>";
        return; // Exit if file upload failed
    }
    
    // Upload the image to Cloudinary
    $upload_result = \Cloudinary\Uploader::upload($image['tmp_name']);
    
    // Debug the upload result
    echo "<pre>";
    echo "Upload Result: ";
    var_dump($upload_result); // Check what we get back from Cloudinary
    echo "</pre>";
    
    // Display the uploaded image URL
    echo "Image uploaded successfully: <a href='" . $upload_result['secure_url'] . "' target='_blank'>" . $upload_result['secure_url'] . "</a>";
}
?>

<form method="POST" enctype="multipart/form-data">
    <label for="image">Upload Image:</label>
    <input type="file" name="image" id="image" required><br>
    <button type="submit">Upload</button>
</form>

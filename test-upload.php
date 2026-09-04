<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

echo "<h1>Test Photo Upload</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Upload Results:</h2>";
    
    // Check if file was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        echo "File name: " . $_FILES['photo']['name'] . "<br>";
        echo "File size: " . $_FILES['photo']['size'] . " bytes<br>";
        echo "File tmp path: " . $_FILES['photo']['tmp_name'] . "<br>";
        
        // Check directory
        echo "PLAYER_IMG_PATH: " . PLAYER_IMG_PATH . "<br>";
        echo "Directory exists: " . (is_dir(PLAYER_IMG_PATH) ? 'YES' : 'NO') . "<br>";
        
        if (!is_dir(PLAYER_IMG_PATH)) {
            echo "Creating directory...<br>";
            mkdir(PLAYER_IMG_PATH, 0777, true);
            echo "Directory created!<br>";
        }
        
        // Try to upload
        $result = uploadFile($_FILES['photo'], PLAYER_IMG_PATH);
        
        if ($result['success']) {
            echo "<h3 style='color: green;'>✅ Upload successful!</h3>";
            echo "Filename: " . $result['filename'] . "<br>";
            echo "Full path: " . PLAYER_IMG_PATH . $result['filename'] . "<br>";
            echo "File exists: " . (file_exists(PLAYER_IMG_PATH . $result['filename']) ? 'YES' : 'NO') . "<br>";
            echo "<img src='/cledan-fc/uploads/players/" . $result['filename'] . "' style='max-width: 200px; border: 1px solid #ccc; padding: 5px;'>";
        } else {
            echo "<h3 style='color: red;'>❌ Upload failed!</h3>";
            echo "Errors: " . implode(', ', $result['errors']) . "<br>";
        }
    } else {
        echo "No file uploaded or upload error occurred.<br>";
        echo "Error code: " . ($_FILES['photo']['error'] ?? 'No file') . "<br>";
    }
}

?>

<h2>Test Upload Form</h2>
<form method="POST" enctype="multipart/form-data">
    <div style="margin-bottom: 10px;">
        <label>Select Image: </label>
        <input type="file" name="photo" accept="image/*" required>
    </div>
    <button type="submit" style="padding: 10px 20px; background: #c9a84c; color: white; border: none; border-radius: 5px; cursor: pointer;">Upload</button>
</form>

<h2>Existing Player Photos</h2>
<?php
if (is_dir(PLAYER_IMG_PATH)) {
    $files = scandir(PLAYER_IMG_PATH);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<div style='display: inline-block; margin: 10px;'>";
            echo "<img src='/cledan-fc/uploads/players/$file' style='max-width: 150px; border: 1px solid #ccc; padding: 5px;'><br>";
            echo "<span style='font-size: 12px;'>$file</span>";
            echo "</div>";
        }
    }
} else {
    echo "No uploads directory found.";
}
?>
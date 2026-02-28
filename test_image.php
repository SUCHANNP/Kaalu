<?php
// test_image.php
echo "<h1>Checking uploads folder</h1>";
echo "Uploads folder exists: " . (is_dir('uploads') ? 'YES' : 'NO') . "<br>";
echo "Uploads folder is writable: " . (is_writable('uploads') ? 'YES' : 'NO') . "<br>";

// List files in uploads
if (is_dir('uploads')) {
    $files = scandir('uploads');
    echo "<h2>Files in uploads folder:</h2>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file - <img src='uploads/$file' style='width:100px;'></li>";
        }
    }
    echo "</ul>";
}
?>
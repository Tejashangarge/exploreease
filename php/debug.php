<?php
echo "<h2>Debug Info</h2>";
echo "POST Data: ";
print_r($_POST);
echo "<br>Files in folder:<br>";
$files = scandir('.');
foreach($files as $file) {
    if($file != '.' && $file != '..') echo "$file<br>";
}
?>

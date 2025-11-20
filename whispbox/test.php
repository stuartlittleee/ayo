<?php
// test.php - quick test file for debugging
// TODO: delete this file before going live

// this is just for testing stuff

echo "WhispBox+ Test Page<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";

// test if files exist
if(file_exists('data/messages.json')){
    echo "messages.json exists<br>";
}else{
    echo "messages.json NOT FOUND<br>";
}

if(file_exists('data/rate_limits.json')){
    echo "rate_limits.json exists<br>";
}else{
    echo "rate_limits.json NOT FOUND<br>";
}

// test password hash
echo "<br>Testing password hash:<br>";
$test_password = 'admin123';
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
if(password_verify($test_password, $hash)){
    echo "Password verify works!<br>";
}else{
    echo "Password verify failed<br>";
}

// test reading messages
echo "<br>Reading messages:<br>";
include 'config.php';
include 'functions.php';
$msgs = get_all_messages();
echo "Total messages: " . count($msgs) . "<br>";

echo "<br>If everything above works, the app should work!";

?>

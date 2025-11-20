<?php
// config.php - main config file for whispbox

// database settings (not used yet but maybe later)
$db_host = 'localhost';
$db_name = 'whispbox';
$db_user = 'root';
$db_pass = '';

// admin password hash (generated with password_hash)
// default password is: admin123
$admin_password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

// message settings
$max_message_length = 500;
$messages_per_page = 25;

// rate limiting settings
$rate_limit_seconds = 10;

// file paths
$messages_file = 'data/messages.json';
$rate_limits_file = 'data/rate_limits.json';

// bad words list
$bad_words = array(
    'badword1',
    'badword2',
    'badword3',
    'fuck',
    'shit',
    'damn'
);

?>

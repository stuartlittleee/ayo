<?php
// config.php - main configuraton file for whispbox

// TODO: add more bad words to the list
// TODO: maybe increase rate limit to 15 seconds?

// database settings (not used yet but maybe later idk)
$db_host = 'localhost';
$db_name = 'whispbox';
$db_user = 'root';
$db_pass = '';

// admin password hash (generated with password_hash function)
// default password is: admin123
// FIXME: change this password before deploying to live server
$admin_password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

// message settings for the app
$max_message_length = 500; // max chars allowed
$messages_per_page = 25; // how many to show per page

// rate limiting settings (prevent spam)
$rate_limit_seconds = 10; // seconds between posts

// file paths for json storage
$messages_file = 'data/messages.json';
$rate_limits_file = 'data/rate_limits.json';

// bad words list (will be censored with ***)
// TODO: add more bad words here
$bad_words = array(
    'badword1',
    'badword2',
    'badword3',
    'fuck',
    'shit',
    'damn'
);

// NOTE: found this password hash online, seems to work

?>

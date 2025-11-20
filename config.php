<?php
// config.php - basic configuration settings

// admin password hash - generated with password_hash()
// the password is "admin123" (just for testing)
$admin_password_hash = '$2y$10$CM91725jh7o5ocQG8tOeJOQ3Yrq41cH/cxOpWLmtc6Zrb3Bp8WmgO';

// max length for messages
$max_message_length = 500;

// how many seconds between posts from same ip
$rate_limit_seconds = 10;

// how many messages per page
$messages_per_page = 25;

// where to store messages
$messages_file = 'data/messages.json';

// where to store rate limits
$rate_limits_file = 'data/rate_limits.json';

// bad words to filter out
// beginner would make a simple array like this
$bad_words = array(
    'badword1',
    'badword2',
    'badword3',
    'spam',
    'scam'
);

?>

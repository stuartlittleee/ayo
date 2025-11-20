<?php
// post.php - handles message submission

// start session
session_start();

// include config file
include 'config.php';

// include functions file
include 'functions.php';

// check if form was submitted
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // not a post request
    $_SESSION['error'] = 'Invalid request';
    header('Location: index.php');
    exit;
}

// get message from post
$message = $_POST['message'];

// check if message is empty
if (!$message) {
    $_SESSION['error'] = 'Message cannot be empty';
    header('Location: index.php');
    exit;
}

// check if message is empty again
if ($message == '') {
    $_SESSION['error'] = 'Message cannot be empty';
    header('Location: index.php');
    exit;
}

// check if message is only spaces
if (trim($message) == '') {
    $_SESSION['error'] = 'Message cannot be only spaces';
    header('Location: index.php');
    exit;
}

// check message length
$msg_length = strlen($message);
if ($msg_length > $max_message_length) {
    $_SESSION['error'] = 'Message is too long (max 500 characters)';
    header('Location: index.php');
    exit;
}

// check if message is too short
if ($msg_length < 1) {
    $_SESSION['error'] = 'Message is too short';
    header('Location: index.php');
    exit;
}

// get user ip
$user_ip = $_SERVER['REMOTE_ADDR'];

// check rate limit
$can_post = check_rate_limit($user_ip);

if (!$can_post) {
    $_SESSION['error'] = 'Please wait 10 seconds before posting again';
    header('Location: index.php');
    exit;
}

// filter bad words
$filtered_message = filter_bad_words($message);

// get current time
$current_time = time();

// create message array
$new_message = array();
$new_message['content'] = $filtered_message;
$new_message['timestamp'] = $current_time;
$new_message['ip'] = $user_ip;

// get all messages
$all_messages = get_all_messages();

// add new message to array
$all_messages[] = $new_message;

// save messages
save_messages($all_messages);

// update rate limit
update_rate_limit($user_ip);

// set success message
$_SESSION['success'] = 'Message posted successfully!';

// redirect back to index
header('Location: index.php');
exit;

?>

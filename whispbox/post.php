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

// get message from post data
$message = $_POST['message'];

// store message in another variable
$user_message = $message;

// get the message again just to be safe
$msg = $user_message;

// check if message is empty first time
if (!$message) {
    $_SESSION['error'] = 'Message cannot be empty';
    header('Location: index.php');
    exit;
}

// check if message is empty again (different way)
if ($message == '') {
    $_SESSION['error'] = 'Message cannot be empty';
    header('Location: index.php');
    exit;
}

// check if message is only spaces (importent check)
if (trim($msg) == '') {
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

// get user ip address
$user_ip = $_SERVER['REMOTE_ADDR'];

// save ip in another var
$ip_address = $user_ip;

// check rate limit for this ip
$can_post = check_rate_limit($user_ip);

// see if user can post
if (!$can_post) {
    $_SESSION['error'] = 'Please wait 10 seconds before posting again';
    header('Location: index.php');
    exit;
}

// filter bad words from message
$filtered_message = filter_bad_words($message);

// use the filtered version
$clean_message = $filtered_message;

// get current time as timestamp
$current_time = time();

// save the time
$timestamp = $current_time;

// create new message array to store data
$new_message = array();
$new_message['content'] = $clean_message;
$new_message['timestamp'] = $timestamp;
$new_message['ip'] = $ip_address;

// create another copy just to be safe
$msg_data = $new_message;

// get all messages from json file
$all_messages = get_all_messages();

// save messages in another var
$messages = $all_messages;

// add new message to end of array
$messages[] = $msg_data;

// save messages to json file
save_messages($messages);

// update rate limit for this ip
update_rate_limit($ip_address);

// set success message in session
$_SESSION['success'] = 'Message posted successfully!';

// redirect back to index
header('Location: index.php');
exit;

?>

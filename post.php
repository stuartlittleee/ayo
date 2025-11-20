<?php
// post.php - handles message submission

// start session
session_start();

// include config and functions
include 'config.php';
include 'functions.php';

// check if form was submitted
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // not a post request
    $_SESSION['error'] = 'Invalid request';
    header('Location: index.php');
    exit;
}

// get message from form
$message = $_POST['message'];

// check if message is empty
if (!$message) {
    $_SESSION['error'] = 'Message cannot be empty';
    header('Location: index.php');
    exit;
}

// check if message is empty string
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
$message_length = strlen($message);

// check if too long
if ($message_length > $max_message_length) {
    $_SESSION['error'] = 'Message is too long (max 500 characters)';
    header('Location: index.php');
    exit;
}

// get user ip address
$user_ip = $_SERVER['REMOTE_ADDR'];

// check rate limit
$can_post = check_rate_limit($user_ip);

// check if rate limited
if (!$can_post) {
    $_SESSION['error'] = 'Please wait ' . $rate_limit_seconds . ' seconds before posting again';
    header('Location: index.php');
    exit;
}

// filter bad words from message
$message = filter_bad_words($message);

// get all current messages
$all_messages = get_all_messages();

// create new message array
$new_message = array();
$new_message['content'] = $message;
$new_message['timestamp'] = time();
$new_message['ip'] = $user_ip;

// add new message to array
$all_messages[] = $new_message;

// save messages back to file
$result = save_messages($all_messages);

// check if save worked
if (!$result) {
    $_SESSION['error'] = 'Could not save message. Please try again.';
    header('Location: index.php');
    exit;
}

// update rate limit for this ip
update_rate_limit($user_ip);

// set success message
$_SESSION['success'] = 'Message posted successfully!';

// redirect back to main page
header('Location: index.php');
exit;

?>

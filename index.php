<?php
// index.php - main page to show messages

// start session
session_start();

// include config and functions
include 'config.php';
include 'functions.php';

// get all messages
$messages = get_all_messages();

// sort messages by timestamp - newest first
// beginner would look this up online
usort($messages, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});

// pagination stuff
// get page number from url
$page = $_GET['page'];

// check if page is set
if (!$page) {
    $page = 1;
}

// convert to number
$page = intval($page);

// check if page is less than 1
if ($page < 1) {
    $page = 1;
}

// calculate how many messages to skip
$start = ($page - 1) * $messages_per_page;

// get only the messages for this page
$page_messages = array_slice($messages, $start, $messages_per_page);

// calculate total pages
$total_messages = count($messages);
$total_pages = ceil($total_messages / $messages_per_page);

// check if we have a success message
$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    // clear it
    unset($_SESSION['success']);
}

// check if we have an error message
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    // clear it
    unset($_SESSION['error']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WhispBox+ - Anonymous Message Board</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>WhispBox+</h1>
        <p class="subtitle">Share your thoughts anonymously</p>
        
        <!-- show success message if exists -->
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- show error message if exists -->
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- form to post new message -->
        <div class="post-form">
            <h2>Post a Message</h2>
            <form method="POST" action="post.php">
                <textarea name="message" placeholder="Write your anonymous message here... (max 500 characters)" maxlength="500"></textarea>
                <button type="submit">Post Message</button>
            </form>
        </div>
        
        <!-- show total messages -->
        <div class="message-count">
            <p>Total Messages: <?php echo $total_messages; ?></p>
        </div>
        
        <!-- display all messages -->
        <div class="messages">
            <?php
            // loop through each message
            foreach ($page_messages as $msg) {
                // get message content
                $content = $msg['content'];
                
                // get timestamp
                $timestamp = $msg['timestamp'];
                
                // format time
                $time_display = time_ago($timestamp);
                
                // show the message
                echo '<div class="message">';
                echo '<p class="message-text">' . htmlspecialchars($content) . '</p>';
                echo '<p class="message-time">' . htmlspecialchars($time_display) . '</p>';
                echo '</div>';
            }
            ?>
            
            <!-- if no messages -->
            <?php if (count($page_messages) == 0): ?>
                <div class="no-messages">
                    <p>No messages yet. Be the first to post!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- pagination links -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                // loop through all pages
                for ($i = 1; $i <= $total_pages; $i++) {
                    // check if current page
                    if ($i == $page) {
                        echo '<span class="current-page">' . $i . '</span> ';
                    } else {
                        echo '<a href="?page=' . $i . '">' . $i . '</a> ';
                    }
                }
                ?>
            </div>
        <?php endif; ?>
        
        <!-- footer links -->
        <div class="footer">
            <a href="wordcloud.php">Word Cloud</a> | 
            <a href="admin.php">Admin</a>
        </div>
    </div>
</body>
</html>

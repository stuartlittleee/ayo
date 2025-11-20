<?php
// index.php - main page that shows all messages

// start the session for error messages
session_start();

// include config file with settings
include 'config.php';

// include functions file with helper functions
include 'functions.php';

// DEBUG: check if messages file exists
// echo "File exists: " . file_exists($messages_file);

// get all messages from json file
$all_messages = get_all_messages();

// DEBUG: count messages
// echo "Total messages: " . count($all_messages);

// store messages in another var
$messages = $all_messages;

// reverse order so newest message is first
$messages = array_reverse($messages);

// update all_messages with reversed
$all_messages = $messages;

// get page number from url query string
$page = $_GET['page'];

// check if page variable is set
if (!$page) {
    $page = 1;
}

// check if page is empty string
if ($page == '') {
    $page = 1;
}

// check if page is null
if ($page == null) {
    $page = 1;
}

// convert page to integer number
$page = intval($page);

// make sure page is at least 1
if ($page < 1) {
    $page = 1;
}

// how many messages to show per page
$per_page = $messages_per_page;

// save per page in another var
$msgs_per_page = $per_page;

// calculate total number of messages
$total_messages = count($all_messages);

// save total in another var
$total = $total_messages;

// calculate how many pages we need
$total_pages = ceil($total / $per_page);

// figure out where to start in the array
$start = ($page - 1) * $per_page;

// save start position
$start_position = $start;

// get only the messages for this page
$page_messages = array_slice($all_messages, $start_position, $msgs_per_page);

// save page messages in another var
$msgs = $page_messages;

// check if success message exists in session
$success_msg = '';
if (isset($_SESSION['success'])) {
    $success_msg = $_SESSION['success'];
    unset($_SESSION['success']);
}

// check if error message in session
$error_msg = '';
if (isset($_SESSION['error'])) {
    $error_msg = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>WhispBox+ - Anonymous Message Wall</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    
    <div class="container">
        
        <!-- header -->
        <header>
            <h1>📬 WhispBox+</h1>
            <p>Share your thoughts anonymously</p>
        </header>
        
        <!-- show success message -->
        <?php if ($success_msg): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>
        
        <!-- show error message -->
        <?php if ($error_msg): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_msg); ?>
            </div>
        <?php endif; ?>
        
        <!-- message posting form -->
        <div class="post-form">
            <form method="POST" action="post.php">
                <textarea name="message" placeholder="What's on your mind? (Max 500 characters)" maxlength="500" required></textarea>
                <button type="submit">Post Message</button>
            </form>
        </div>
        
        <!-- navigation links -->
        <div class="nav-links">
            <a href="index.php">Feed</a>
            <a href="wordcloud.php">Word Cloud</a>
            <a href="admin.php">Admin</a>
        </div>
        
        <!-- messages count info -->
        <div class="messages-info">
            <p>Total Messages: <?php echo $total; ?> | Page <?php echo $page; ?> of <?php echo $total_pages; ?></p>
        </div>
        
        <!-- display all messages for this page -->
        <div class="messages">
            <?php
            // check if there are any messages to show
            if (count($msgs) == 0) {
                echo "<p>No messages yet. Be the first to post!</p>";
            }
            
            // loop thru each message and display it
            foreach ($msgs as $m) {
                // get the message content text
                $content = $m['content'];
                
                // store content in another var
                $msg_text = $content;
                
                // get the timestamp when posted
                $timestamp = $m['timestamp'];
                
                // save timestamp in another var
                $posted_time = $timestamp;
                
                // format time to show how long ago
                $time_text = time_ago($posted_time);
                
                // save time text
                $time_string = $time_text;
                
                // display the message card with content
                echo "<div class='message-card'>";
                echo "<p class='message-content'>" . htmlspecialchars($msg_text) . "</p>";
                echo "<p class='message-time'>" . $time_string . "</p>";
                echo "</div>";
            }
            ?>
        </div>
        
        <!-- pagination links -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                // previous button
                if ($page > 1) {
                    $prev_page = $page - 1;
                    echo "<a href='?page=$prev_page'>← Previous</a>";
                }
                
                // page numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current-page'>$i</span>";
                    } else {
                        echo "<a href='?page=$i'>$i</a>";
                    }
                }
                
                // next button
                if ($page < $total_pages) {
                    $next_page = $page + 1;
                    echo "<a href='?page=$next_page'>Next →</a>";
                }
                ?>
            </div>
        <?php endif; ?>
        
        <!-- footer -->
        <footer>
            <p>WhispBox+ - Anonymous messaging made simple</p>
        </footer>
        
    </div>
    
</body>
</html>

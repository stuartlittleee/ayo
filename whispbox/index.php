<?php
// index.php - main page that shows all messages

// start session
session_start();

// include config file
include 'config.php';

// include functions file
include 'functions.php';

// get all messages
$all_messages = get_all_messages();

// reverse order so newest is first
$all_messages = array_reverse($all_messages);

// get page number from url
$page = $_GET['page'];

// check if page is set
if (!$page) {
    $page = 1;
}

// check if page is empty
if ($page == '') {
    $page = 1;
}

// convert to number
$page = intval($page);

// check if page is less than 1
if ($page < 1) {
    $page = 1;
}

// how many messages per page
$per_page = $messages_per_page;

// calculate total pages
$total_messages = count($all_messages);
$total_pages = ceil($total_messages / $per_page);

// calculate where to start
$start = ($page - 1) * $per_page;

// get messages for this page
$page_messages = array_slice($all_messages, $start, $per_page);

// check if success message in session
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
        
        <!-- messages count -->
        <div class="messages-info">
            <p>Total Messages: <?php echo $total_messages; ?> | Page <?php echo $page; ?> of <?php echo $total_pages; ?></p>
        </div>
        
        <!-- display messages -->
        <div class="messages">
            <?php
            // check if there are messages
            if (count($page_messages) == 0) {
                echo "<p>No messages yet. Be the first to post!</p>";
            }
            
            // loop through each message
            foreach ($page_messages as $msg) {
                // get message content
                $content = $msg['content'];
                
                // get timestamp
                $timestamp = $msg['timestamp'];
                
                // format time
                $time_text = time_ago($timestamp);
                
                // display message card
                echo "<div class='message-card'>";
                echo "<p class='message-content'>" . htmlspecialchars($content) . "</p>";
                echo "<p class='message-time'>" . $time_text . "</p>";
                echo "</div>";
            }
            ?>
        </div>
        
        <!-- pagination -->
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

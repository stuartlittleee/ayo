<?php
// admin.php - admin login and moderation panel

// start session
session_start();

// include config file
include 'config.php';

// include functions file
include 'functions.php';

// check if logout
if (isset($_GET['logout'])) {
    // logout admin
    $_SESSION['is_admin'] = false;
    unset($_SESSION['is_admin']);
    session_destroy();
    header('Location: admin.php');
    exit;
}

// check if delete message
if (isset($_GET['delete'])) {
    // check if admin
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true) {
        echo "Access denied";
        exit;
    }
    
    // get message id
    $msg_id = $_GET['delete'];
    
    // get all messages
    $all_messages = get_all_messages();
    
    // remove message at index
    if (isset($all_messages[$msg_id])) {
        unset($all_messages[$msg_id]);
        
        // re-index array
        $all_messages = array_values($all_messages);
        
        // save messages
        save_messages($all_messages);
    }
    
    // redirect back
    header('Location: admin.php');
    exit;
}

// check if login form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get password from post
    $password = $_POST['password'];
    
    // check if password is set
    if (!$password) {
        $error = 'Password is required';
    } else {
        // verify password
        if (password_verify($password, $admin_password_hash)) {
            // password correct
            $_SESSION['is_admin'] = true;
            header('Location: admin.php');
            exit;
        } else {
            // password wrong
            $error = 'Incorrect password';
        }
    }
}

// check if admin is logged in
$is_admin = false;
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
    $is_admin = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - WhispBox+</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
        
        <!-- header -->
        <header>
            <h1>🔐 Admin Panel</h1>
        </header>
        
        <!-- navigation links -->
        <div class="nav-links">
            <a href="index.php">← Back to Feed</a>
            <?php if ($is_admin): ?>
                <a href="admin.php?logout=1">Logout</a>
            <?php endif; ?>
        </div>
        
        <?php if (!$is_admin): ?>
            
            <!-- login form -->
            <div class="admin-login">
                <h2>Login</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="admin.php">
                    <input type="password" name="password" placeholder="Admin Password" required>
                    <button type="submit">Login</button>
                </form>
                
                <p class="hint">Default password: admin123</p>
            </div>
            
        <?php else: ?>
            
            <!-- moderation panel -->
            <div class="admin-panel">
                <h2>Message Moderation</h2>
                
                <?php
                // get all messages
                $all_msgs = get_all_messages();
                
                // reverse so newest first
                $all_msgs = array_reverse($all_msgs, true);
                
                // count messages
                $total = count($all_msgs);
                
                echo "<p>Total Messages: $total</p>";
                
                // check if messages exist
                if ($total == 0) {
                    echo "<p>No messages to moderate</p>";
                } else {
                    // display all messages
                    echo "<div class='admin-messages'>";
                    
                    foreach ($all_msgs as $index => $msg) {
                        // get content
                        $content = $msg['content'];
                        
                        // get timestamp
                        $timestamp = $msg['timestamp'];
                        
                        // get ip
                        $ip = $msg['ip'];
                        
                        // format time
                        $time_text = time_ago($timestamp);
                        
                        // calculate actual index (reversed)
                        $original_index = count($all_msgs) - 1 - ($index - min(array_keys($all_msgs)));
                        
                        // display message
                        echo "<div class='admin-message-card'>";
                        echo "<p class='message-content'>" . htmlspecialchars($content) . "</p>";
                        echo "<p class='message-meta'>Posted: $time_text | IP: $ip</p>";
                        echo "<a href='admin.php?delete=$original_index' class='delete-btn' onclick='return confirm(\"Delete this message?\")'>Delete</a>";
                        echo "</div>";
                    }
                    
                    echo "</div>";
                }
                ?>
                
            </div>
            
        <?php endif; ?>
        
    </div>
    
</body>
</html>

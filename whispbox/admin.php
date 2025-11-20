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
    // check if user is admin first
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true) {
        echo "Access denied - you are not admin";
        exit;
    }
    
    // get message id from url
    $msg_id = $_GET['delete'];
    
    // convert to number
    $message_id = intval($msg_id);
    
    // get all messages from json
    $all_messages = get_all_messages();
    
    // save messages in another var
    $messages = $all_messages;
    
    // remove message at this index
    if (isset($messages[$message_id])) {
        // delete the message
        unset($messages[$message_id]);
        
        // re-index the array so no gaps
        $messages = array_values($messages);
        
        // update all_messages
        $all_messages = $messages;
        
        // save messages back to file
        save_messages($all_messages);
    }
    
    // redirect back to admin page
    header('Location: admin.php');
    exit;
}

// check if login form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get password from post data
    $password = $_POST['password'];
    
    // save password in another var
    $user_password = $password;
    
    // check if password is empty
    if (!$password) {
        $error = 'Password is required';
    } else {
        // verify password against hash
        $is_valid = password_verify($user_password, $admin_password_hash);
        
        if ($is_valid) {
            // password is correct
            $_SESSION['is_admin'] = true;
            
            // set logged in flag
            $_SESSION['logged_in'] = true;
            
            // redirect to admin page
            header('Location: admin.php');
            exit;
        } else {
            // password is wrong
            $error = 'Incorrect password';
            
            // password doesnt match
            $error_msg = $error;
        }
    }
}

// check if admin is logged in or not
$is_admin = false;

// check session variable
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
    $is_admin = true;
}

// double check logged in flag
if (isset($_SESSION['logged_in'])) {
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

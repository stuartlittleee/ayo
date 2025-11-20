<?php
// admin.php - admin panel for moderation

// start session
session_start();

// include config and functions
include 'config.php';
include 'functions.php';

// check if logging in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    // get password from form
    $password = $_POST['password'];
    
    // check if password is correct
    if (password_verify($password, $admin_password_hash)) {
        // password is correct
        $_SESSION['is_admin'] = true;
        $_SESSION['success'] = 'Logged in successfully';
    } else {
        // wrong password
        $_SESSION['error'] = 'Wrong password';
    }
}

// check if logging out
if (isset($_GET['logout'])) {
    // clear admin session
    $_SESSION['is_admin'] = false;
    unset($_SESSION['is_admin']);
    $_SESSION['success'] = 'Logged out';
}

// check if deleting a message
if (isset($_POST['delete']) && isset($_POST['message_index'])) {
    // check if admin
    if (!$_SESSION['is_admin']) {
        $_SESSION['error'] = 'Access denied';
    } else {
        // get message index to delete
        $index = intval($_POST['message_index']);
        
        // get all messages
        $messages = get_all_messages();
        
        // check if index exists
        if (isset($messages[$index])) {
            // remove the message
            unset($messages[$index]);
            
            // reindex array
            $messages = array_values($messages);
            
            // save back
            save_messages($messages);
            
            $_SESSION['success'] = 'Message deleted';
        } else {
            $_SESSION['error'] = 'Message not found';
        }
    }
}

// check if admin is logged in
$is_admin = false;
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
    $is_admin = true;
}

// get success message
$success = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// get error message
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - WhispBox+</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        
        <p><a href="index.php">← Back to Main Page</a></p>
        
        <!-- show success message -->
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- show error message -->
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <!-- check if admin is logged in -->
        <?php if (!$is_admin): ?>
            <!-- show login form -->
            <div class="login-form">
                <h2>Login</h2>
                <form method="POST" action="admin.php">
                    <input type="password" name="password" placeholder="Admin Password" required>
                    <button type="submit">Login</button>
                </form>
                <p class="hint">Hint: Default password is "admin123"</p>
            </div>
        <?php else: ?>
            <!-- admin is logged in -->
            <p><a href="admin.php?logout=1">Logout</a></p>
            
            <h2>All Messages</h2>
            
            <!-- get all messages -->
            <?php
            $all_messages = get_all_messages();
            
            // sort by newest first
            usort($all_messages, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });
            
            // count messages
            $message_count = count($all_messages);
            ?>
            
            <p>Total: <?php echo $message_count; ?> messages</p>
            
            <!-- display all messages with delete buttons -->
            <div class="admin-messages">
                <?php
                // loop through each message
                $index = 0;
                foreach ($all_messages as $msg) {
                    // get message content
                    $content = $msg['content'];
                    
                    // get timestamp
                    $timestamp = $msg['timestamp'];
                    
                    // format time
                    $time_display = time_ago($timestamp);
                    
                    // get ip
                    $ip = $msg['ip'];
                    
                    // show message with delete button
                    echo '<div class="admin-message">';
                    echo '<p class="message-text">' . htmlspecialchars($content) . '</p>';
                    echo '<p class="message-info">Posted ' . htmlspecialchars($time_display) . ' from IP: ' . htmlspecialchars($ip) . '</p>';
                    echo '<form method="POST" action="admin.php" style="display:inline;">';
                    echo '<input type="hidden" name="message_index" value="' . $index . '">';
                    echo '<button type="submit" name="delete" value="1" class="delete-btn">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                    
                    $index++;
                }
                ?>
                
                <!-- if no messages -->
                <?php if ($message_count == 0): ?>
                    <p>No messages to moderate.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

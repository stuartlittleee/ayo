# WhispBox+ - Anonymous Message Wall

A simple anonymous message board built with beginner-friendly PHP code.

## Features

- Post anonymous messages (max 500 characters)
- View messages with pagination (25 per page)
- Profanity filter
- Rate limiting (10 seconds between posts)
- Admin panel for moderation
- Word cloud visualization
- Responsive design

## Installation

1. Copy the `whispbox` folder to your web server
2. Make sure the `data` folder is writable:
   ```
   chmod 777 whispbox/data
   chmod 666 whispbox/data/messages.json
   chmod 666 whispbox/data/rate_limits.json
   ```
3. Open `index.php` in your browser

## Admin Access

- Go to `admin.php`
- Default password: `admin123`
- Change password by generating new hash in `config.php`

To generate new password hash:
```php
<?php
echo password_hash('your_new_password', PASSWORD_BCRYPT);
?>
```

## File Structure

```
whispbox/
├── index.php          - Main feed page
├── post.php           - Handle message submission
├── admin.php          - Admin login and moderation
├── wordcloud.php      - Word cloud visualization
├── config.php         - Configuration settings
├── functions.php      - Helper functions
├── style.css          - Styling
├── script.js          - JavaScript for auto-refresh
└── data/
    ├── messages.json      - Stored messages
    ├── rate_limits.json   - Rate limiting data
    └── .htaccess          - Security protection
```

## Configuration

Edit `config.php` to customize:

- Admin password hash
- Max message length (default: 500)
- Messages per page (default: 25)
- Rate limit seconds (default: 10)
- Bad words list

## Security Features

- XSS protection with htmlspecialchars()
- Rate limiting by IP address
- Admin password hashing
- .htaccess protection for data folder
- Input validation

## Requirements

- PHP 7.0 or higher
- Apache web server (for .htaccess)
- Write permissions on data folder

## Usage

### Posting Messages
1. Go to index.php
2. Type your message in the text box
3. Click "Post Message"
4. Wait 10 seconds before posting again

### Admin Moderation
1. Go to admin.php
2. Login with password
3. View all messages with IP addresses
4. Delete inappropriate messages

### Word Cloud
1. Go to wordcloud.php
2. View most used words from last 100 messages
3. Word size shows frequency

## Notes

- This is beginner-level PHP code for learning purposes
- Messages are stored in JSON files (not database)
- For production use, consider adding more security features
- Page auto-refreshes every 30 seconds

## License

Free to use and modify.

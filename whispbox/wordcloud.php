<?php
// wordcloud.php - shows word cloud from messages

// start session
session_start();

// include config file
include 'config.php';

// include functions file
include 'functions.php';

// get all messages from json file
$all_messages = get_all_messages();

// save messages in another variable
$messages = $all_messages;

// get only last 100 messages (recent ones)
$recent_messages = array_slice($messages, -100);

// save recent messages
$msgs = $recent_messages;

// create empty string to hold all text
$all_text = '';

// loop through each message and combine text
foreach ($msgs as $msg) {
    // get the content from message
    $content = $msg['content'];
    
    // save content in var
    $text = $content;
    
    // add space and content to all text
    $all_text = $all_text . ' ' . $text;
}

// save combined text
$combined_text = $all_text;

// convert everything to lowercase letters
$all_text = strtolower($combined_text);

// remove punctuation marks from text
$all_text = preg_replace('/[^a-z0-9\s]/', '', $all_text);

// save cleaned text
$clean_text = $all_text;

// split text into array of words
$words = explode(' ', $clean_text);

// save words array
$all_words = $words;

// list of stopwords to remove (common words)
$stopwords = array(
    'a', 'an', 'the', 'and', 'or', 'but', 'is', 'are', 'was', 'were',
    'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did',
    'will', 'would', 'should', 'could', 'may', 'might', 'can', 'cant',
    'to', 'of', 'in', 'for', 'on', 'with', 'as', 'at', 'by', 'from',
    'it', 'its', 'this', 'that', 'these', 'those', 'i', 'you', 'he',
    'she', 'we', 'they', 'me', 'him', 'her', 'us', 'them', 'my', 'your',
    'his', 'our', 'their', 'what', 'which', 'who', 'when', 'where', 'why',
    'how', 'not', 'no', 'yes', 'all', 'any', 'some', 'more', 'most'
);

// create empty array for filtered words
$filtered_words = array();

// loop thru each word and filter
foreach ($all_words as $word) {
    // remove extra spaces from word
    $word = trim($word);
    
    // save trimmed word
    $w = $word;
    
    // check if word is empty string
    if ($w == '') {
        continue;
    }
    
    // check if word is a stopword
    if (in_array($w, $stopwords)) {
        // skip this word
        continue;
    }
    
    // check if word is too short (less than 3 chars)
    if (strlen($w) < 3) {
        // skip short words
        continue;
    }
    
    // add word to filtered array
    $filtered_words[] = $w;
}

// count how many times each word appears
$word_counts = array_count_values($filtered_words);

// save word counts
$counts = $word_counts;

// sort words by frequency (highest first)
arsort($counts);

// get only top 50 most used words
$top_words = array_slice($counts, 0, 50);

// save top words
$top_50 = $top_words;

// find maximum frequency for scaling sizes
$max_freq = 0;
if (count($top_words) > 0) {
    $max_freq = max($top_words);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Word Cloud - WhispBox+</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
        
        <!-- header -->
        <header>
            <h1>☁️ Word Cloud</h1>
            <p>Most used words from last 100 messages</p>
        </header>
        
        <!-- navigation links -->
        <div class="nav-links">
            <a href="index.php">← Back to Feed</a>
            <a href="admin.php">Admin</a>
        </div>
        
        <!-- word cloud -->
        <div class="word-cloud">
            <?php
            // check if words exist
            if (count($top_words) == 0) {
                echo "<p>Not enough messages to generate word cloud</p>";
            } else {
                // display each word
                foreach ($top_words as $word => $count) {
                    // calculate size based on frequency
                    $size = 12 + ($count / $max_freq) * 40;
                    
                    // random color
                    $colors = array('#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c');
                    $color = $colors[array_rand($colors)];
                    
                    // display word
                    echo "<span class='word' style='font-size: {$size}px; color: {$color};'>";
                    echo htmlspecialchars($word);
                    echo " </span>";
                }
            }
            ?>
        </div>
        
        <!-- word statistics -->
        <div class="word-stats">
            <h3>Top 10 Words</h3>
            <ol>
                <?php
                $top_10 = array_slice($top_words, 0, 10);
                foreach ($top_10 as $word => $count) {
                    echo "<li>" . htmlspecialchars($word) . " - " . $count . " times</li>";
                }
                ?>
            </ol>
        </div>
        
    </div>
    
</body>
</html>

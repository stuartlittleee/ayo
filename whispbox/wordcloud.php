<?php
// wordcloud.php - shows word cloud from messages

// start session
session_start();

// include config file
include 'config.php';

// include functions file
include 'functions.php';

// get all messages
$all_messages = get_all_messages();

// limit to last 100 messages
$recent_messages = array_slice($all_messages, -100);

// combine all text
$all_text = '';
foreach ($recent_messages as $msg) {
    // get content
    $content = $msg['content'];
    
    // add to text
    $all_text = $all_text . ' ' . $content;
}

// convert to lowercase
$all_text = strtolower($all_text);

// remove punctuation
$all_text = preg_replace('/[^a-z0-9\s]/', '', $all_text);

// split into words
$words = explode(' ', $all_text);

// stopwords to remove
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

// filter out stopwords and empty
$filtered_words = array();
foreach ($words as $word) {
    // remove spaces
    $word = trim($word);
    
    // check if empty
    if ($word == '') {
        continue;
    }
    
    // check if stopword
    if (in_array($word, $stopwords)) {
        continue;
    }
    
    // check if too short
    if (strlen($word) < 3) {
        continue;
    }
    
    // add to filtered
    $filtered_words[] = $word;
}

// count word frequencies
$word_counts = array_count_values($filtered_words);

// sort by frequency
arsort($word_counts);

// get top 50 words
$top_words = array_slice($word_counts, 0, 50);

// find max frequency for scaling
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

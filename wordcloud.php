<?php
// wordcloud.php - shows word frequencies from messages

// start session
session_start();

// include config and functions
include 'config.php';
include 'functions.php';

// get all messages
$all_messages = get_all_messages();

// combine all message text
$all_text = '';

// loop through messages
foreach ($all_messages as $msg) {
    // get content
    $content = $msg['content'];
    
    // add to all text with space
    $all_text = $all_text . ' ' . $content;
}

// convert to lowercase
$all_text = strtolower($all_text);

// remove punctuation - beginner way
$all_text = str_replace('.', '', $all_text);
$all_text = str_replace(',', '', $all_text);
$all_text = str_replace('!', '', $all_text);
$all_text = str_replace('?', '', $all_text);
$all_text = str_replace(';', '', $all_text);
$all_text = str_replace(':', '', $all_text);
$all_text = str_replace('"', '', $all_text);
$all_text = str_replace("'", '', $all_text);

// split into words
$words = explode(' ', $all_text);

// stopwords to remove - common words
$stopwords = array(
    'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
    'of', 'with', 'by', 'from', 'up', 'about', 'into', 'through', 'during',
    'before', 'after', 'above', 'below', 'between', 'under', 'again', 'further',
    'then', 'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'both',
    'each', 'few', 'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not',
    'only', 'own', 'same', 'so', 'than', 'too', 'very', 'can', 'will', 'just',
    'should', 'now', 'i', 'you', 'he', 'she', 'it', 'they', 'them', 'their',
    'what', 'which', 'who', 'this', 'that', 'these', 'those', 'am', 'is', 'are',
    'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does',
    'did', 'doing', 'would', 'could', 'ought', 'im', 'ive', 'dont', 'doesnt'
);

// filter out stopwords and short words
$filtered_words = array();

// loop through each word
foreach ($words as $word) {
    // trim spaces
    $word = trim($word);
    
    // skip if empty
    if ($word == '') {
        continue;
    }
    
    // skip if too short
    if (strlen($word) < 3) {
        continue;
    }
    
    // skip if stopword
    if (in_array($word, $stopwords)) {
        continue;
    }
    
    // add to filtered array
    $filtered_words[] = $word;
}

// count word frequencies
$word_counts = array_count_values($filtered_words);

// sort by count descending
arsort($word_counts);

// get top 50 words
$top_words = array_slice($word_counts, 0, 50);

// find max count for sizing
$max_count = 0;
foreach ($top_words as $word => $count) {
    if ($count > $max_count) {
        $max_count = $count;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Word Cloud - WhispBox+</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Word Cloud</h1>
        
        <p><a href="index.php">← Back to Main Page</a></p>
        
        <p>Most frequently used words from the last <?php echo count($all_messages); ?> messages:</p>
        
        <!-- display word cloud -->
        <div class="word-cloud">
            <?php
            // loop through top words
            foreach ($top_words as $word => $count) {
                // calculate font size
                // bigger count = bigger font
                $min_size = 14;
                $max_size = 48;
                
                // calculate size based on count
                if ($max_count > 0) {
                    $size = $min_size + (($max_size - $min_size) * ($count / $max_count));
                } else {
                    $size = $min_size;
                }
                
                // round size
                $size = round($size);
                
                // display word with inline style
                echo '<span style="font-size: ' . $size . 'px; margin: 5px;">';
                echo htmlspecialchars($word) . ' (' . $count . ')';
                echo '</span> ';
            }
            ?>
            
            <!-- if no words -->
            <?php if (count($top_words) == 0): ?>
                <p>No words to display yet. Post some messages!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

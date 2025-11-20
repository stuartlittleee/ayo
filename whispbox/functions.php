<?php
// functions.php - helper functions for the message board

// function to get all mesages from json file
function get_all_messages() {
    global $messages_file;
    
    // check if file exists first
    if (!file_exists($messages_file)) {
        // create empty file if it doesnt exist
        file_put_contents($messages_file, json_encode(array()));
        return array();
    }
    
    // read the file content
    $json_data = file_get_contents($messages_file);
    
    // decode json to array
    $messages = json_decode($json_data, true);
    
    // check if its empty or null
    if (!$messages) {
        $messages = array();
    }
    
    // retrun all messages
    return $messages;
}

// function to save messages to json file
function save_messages($messages) {
    global $messages_file;
    
    // convert to json
    $json_string = json_encode($messages);
    
    // open file for writing
    $file = fopen($messages_file, 'w');
    
    // lock file
    flock($file, LOCK_EX);
    
    // write to file
    fwrite($file, $json_string);
    
    // unlock file
    flock($file, LOCK_UN);
    
    // close file
    fclose($file);
}

// function to filter bad wrods from message
function filter_bad_words($text) {
    global $bad_words;
    
    // loop thru each bad word in the list
    foreach ($bad_words as $word) {
        // replace bad word with stars (censored)
        $text = str_ireplace($word, '***', $text);
    }
    
    // retrun the filtered text
    return $text;
}

// function to get rate limit data
function get_rate_limits() {
    global $rate_limits_file;
    
    // check if file exists
    if (!file_exists($rate_limits_file)) {
        // create empty file
        file_put_contents($rate_limits_file, json_encode(array()));
        return array();
    }
    
    // read file
    $json_data = file_get_contents($rate_limits_file);
    
    // decode json
    $limits = json_decode($json_data, true);
    
    // check if empty
    if (!$limits) {
        $limits = array();
    }
    
    // return limits
    return $limits;
}

// function to save rate limits
function save_rate_limits($limits) {
    global $rate_limits_file;
    
    // convert to json
    $json_string = json_encode($limits);
    
    // write to file
    file_put_contents($rate_limits_file, $json_string);
}

// function to check rate limit
function check_rate_limit($ip) {
    global $rate_limit_seconds;
    
    // get all rate limits
    $limits = get_rate_limits();
    
    // get current time
    $current_time = time();
    
    // check if ip exists in limits
    if (isset($limits[$ip])) {
        // get last time
        $last_time = $limits[$ip];
        
        // calculate difference
        $time_diff = $current_time - $last_time;
        
        // check if less than rate limit
        if ($time_diff < $rate_limit_seconds) {
            // rate limited
            return false;
        }
    }
    
    // not rate limited
    return true;
}

// function to update rate limit
function update_rate_limit($ip) {
    // get all rate limits
    $limits = get_rate_limits();
    
    // get current time
    $current_time = time();
    
    // update ip time
    $limits[$ip] = $current_time;
    
    // save limits
    save_rate_limits($limits);
}

// function to format time ago
function time_ago($timestamp) {
    // get current time
    $current_time = time();
    
    // calculate difference
    $time_diff = $current_time - $timestamp;
    
    // check minutes
    if ($time_diff < 60) {
        return $time_diff . ' seconds ago';
    }
    
    // check hours
    $minutes = floor($time_diff / 60);
    if ($minutes < 60) {
        return $minutes . ' minutes ago';
    }
    
    // check days
    $hours = floor($minutes / 60);
    if ($hours < 24) {
        return $hours . ' hours ago';
    }
    
    // show days
    $days = floor($hours / 24);
    return $days . ' days ago';
}

?>

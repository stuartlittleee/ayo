<?php
// functions.php - helper functions for the site

// function to get all messages from json file
function get_all_messages() {
    global $messages_file;
    
    // check if file exists
    if (!file_exists($messages_file)) {
        // if not, return empty array
        return array();
    }
    
    // read the file
    $json_data = file_get_contents($messages_file);
    
    // decode json to array
    $messages = json_decode($json_data, true);
    
    // check if it worked
    if (!$messages) {
        // return empty array if failed
        return array();
    }
    
    // return the messages
    return $messages;
}

// function to save messages to json file
function save_messages($messages) {
    global $messages_file;
    
    // convert array to json
    $json_data = json_encode($messages);
    
    // try to save to file
    $result = file_put_contents($messages_file, $json_data);
    
    // check if it worked
    if ($result === false) {
        return false;
    }
    
    return true;
}

// function to filter bad words from text
function filter_bad_words($text) {
    global $bad_words;
    
    // loop through each bad word
    foreach ($bad_words as $word) {
        // replace it with stars
        $text = str_ireplace($word, '***', $text);
    }
    
    // return cleaned text
    return $text;
}

// function to check rate limit for an ip
function check_rate_limit($ip) {
    global $rate_limits_file;
    global $rate_limit_seconds;
    
    // check if file exists
    if (!file_exists($rate_limits_file)) {
        // create empty file
        file_put_contents($rate_limits_file, json_encode(array()));
        return true; // allow post
    }
    
    // read rate limits
    $json_data = file_get_contents($rate_limits_file);
    $limits = json_decode($json_data, true);
    
    // check if limits is empty
    if (!$limits) {
        $limits = array();
    }
    
    // get current time
    $current_time = time();
    
    // check if this ip has posted before
    if (isset($limits[$ip])) {
        // get last post time
        $last_post_time = $limits[$ip];
        
        // calculate time difference
        $time_diff = $current_time - $last_post_time;
        
        // check if too soon
        if ($time_diff < $rate_limit_seconds) {
            // too soon!
            return false;
        }
    }
    
    // allow the post
    return true;
}

// function to update rate limit for an ip
function update_rate_limit($ip) {
    global $rate_limits_file;
    
    // read current limits
    $json_data = file_get_contents($rate_limits_file);
    $limits = json_decode($json_data, true);
    
    // check if empty
    if (!$limits) {
        $limits = array();
    }
    
    // update this ip to current time
    $limits[$ip] = time();
    
    // save back to file
    $json_string = json_encode($limits);
    file_put_contents($rate_limits_file, $json_string);
}

// function to format timestamp as "X minutes ago"
function time_ago($timestamp) {
    // get current time
    $current_time = time();
    
    // calculate difference
    $diff = $current_time - $timestamp;
    
    // check different time periods
    if ($diff < 60) {
        // less than a minute
        return $diff . ' seconds ago';
    } else if ($diff < 3600) {
        // less than an hour
        $minutes = floor($diff / 60);
        return $minutes . ' minutes ago';
    } else if ($diff < 86400) {
        // less than a day
        $hours = floor($diff / 3600);
        return $hours . ' hours ago';
    } else {
        // more than a day
        $days = floor($diff / 86400);
        return $days . ' days ago';
    }
}

?>

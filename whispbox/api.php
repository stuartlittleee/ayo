<?php
// api.php - simple api to get messages as json
// not sure if this is useful but made it anyway

// set header to json
header('Content-Type: application/json');

// include files
include 'config.php';
include 'functions.php';

// get action from url
$action = $_GET['action'];

// check what to do
if($action == 'get_messages'){
    // get all messages
    $messages = get_all_messages();
    
    // reverse order
    $messages = array_reverse($messages);
    
    // get page
    $page = $_GET['page'];
    if(!$page){
        $page = 1;
    }
    
    // calculate start
    $per_page = 25;
    $start = ($page - 1) * $per_page;
    
    // slice array
    $page_msgs = array_slice($messages, $start, $per_page);
    
    // return json
    echo json_encode($page_msgs);
    
}elseif($action == 'count'){
    // count total messages
    $messages = get_all_messages();
    $total = count($messages);
    
    // return count
    echo json_encode(array('count' => $total));
    
}else{
    // unknown action
    echo json_encode(array('error' => 'Unknown action'));
}

// TODO: add post message via api
// TODO: add delete message via api
// TODO: add authentication for api

?>

<?php
// db.php - database connection file
// not using database yet but this is here for future

// database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'whispbox';

// function to connect to database (not used yet)
function connect_db() {
    global $db_host, $db_user, $db_pass, $db_name;
    
    // try to connect
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    
    // check if connection works
    if (!$conn) {
        // connection failed
        die('Database connection failed: ' . mysqli_connect_error());
    }
    
    // return connection
    return $conn;
}

// function to close database connection
function close_db($conn) {
    // close the connection
    mysqli_close($conn);
}

// NOTE: we are using JSON files instead of database for now
// this file is here in case we want to switch to mysql later
// to use database uncomment these lines and change functions.php

/*
// example usage:
$connection = connect_db();

// do queries here
$result = mysqli_query($connection, "SELECT * FROM messages");

// close connection
close_db($connection);
*/

?>

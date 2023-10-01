<?php

session_start();

$name="assignments";
$username="root";
$password="";
$server="localhost";
$db = mysqli_connect($server, $username, $password, $name);


if (mysqli_connect_errno()) {
    die("error connection");
    // echo "error connection"
    // exit;
}
else {
    // echo "db is connected";
}

// if(!$db){
//     echo 'connection error: ' . mysqli_connect_errno();
// }

global $db;














?>
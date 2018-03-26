<?php
$servername = "localhost";
$username = "chiptune";
$password = "pass";
$base = "chiptune";

// Create connection
$con = mysqli_connect($servername, $username, $password, $base);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";

$test =  mysqli_query($con, 'show tables;');
?>

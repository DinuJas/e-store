<?php

$servername = "localhost";
$username = "root";
$password = "root";
$db = "e-store";


// Connect to DB
$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error)
{
    die("Connection Failed: " . $conn->connect_error);
}

?>
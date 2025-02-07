<?php

session_start();

$host = 'localhost';
$dbname = 'moviesrch';
$user = 'ethan';
$pass = '#######';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?> 

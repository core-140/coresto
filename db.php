<?php
// db.php — Database connection file

$servername = "localhost";   // usually localhost
$username   = "root";        // change if you set a MySQL user
$password   = "";            // put your MySQL password here
$dbname     = "food_db";     // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: set charset to avoid weird encoding issues
$conn->set_charset("utf8mb4");
?>

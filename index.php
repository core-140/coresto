<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CORESTO</title>
    <link rel="stylesheet" href="style.css?v=2.1">
</head>
<body>
    <div style="position: absolute; top: 20px; right: 20px;">
    <a href="admin_login.php" 
       style="background: limegreen; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Admin Login</a>
</div>


    <?php include 'navbar.html'; ?>

    <div class="container">
        <h1>Welcome to CORESTO</h1>
        <p>Select a category to start ordering</p>

        <div class="categories">
            <a href="starter.php" class="category starter"><span>Starters</span></a>
            <a href="maincourse.php" class="category main-course"><span>Main Course</span></a>
            <a href="dessert.php" class="category dessert"><span>Desserts</span></a>
            <a href="drinks.php" class="category drinks"><span>Drinks</span></a>
        </div>
    </div>

    <!-- Cart sidebar included globally -->
    <?php include 'cartsidebar.php'; ?>
     <a href="status.php" class="status-btn">See Status</a>
</body>
</html>

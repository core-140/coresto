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
    <title>Main Course - CORESTO</title>
    <link rel="stylesheet" href="style.css?v=2.1">
</head>
<body>
    <?php include 'navbar.html'; ?>

    <div class="menu-section">
        <h2>Main Course</h2>
        <?php
        $result = $conn->query("SELECT * FROM food WHERE category='main course'");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="menu-item">
                <img src="img/<?= $row['image'] ?>" alt="<?= $row['foodname'] ?>">
                <div class="item-info">
                    <h3><?= $row['foodname'] ?></h3>
                    <p><?= $row['description'] ?></p>
                    <div class="item-bottom">
                        <span>₹<?= $row['price'] ?></span>
                        <button class="btn btn-comments" onclick="openReviewModal(<?= $row['foodid'] ?>)">Comments</button>
                        
                        <!-- ✅ AJAX Add to Cart -->
                        <button class="btn addToCart" data-id="<?= $row['foodid'] ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Cart Sidebar -->
    <?php include 'cartsidebar.php'; ?>

    <!-- Review Modal -->
    <?php include 'review_modal.php'; ?>

    <!-- Review JS -->
    <script src="review.js"></script>
</body>
</html>

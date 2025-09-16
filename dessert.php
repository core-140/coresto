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
    <title>Desserts - CORESTO</title>
    <link rel="stylesheet" href="style.css?v=2.1">
</head>
<body>
    <?php include 'navbar.html'; ?>

    <div class="menu-section">
        <h2>Desserts</h2>
        <?php
        $result = $conn->query("SELECT * FROM food WHERE category='dessert'");
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
                        <!-- AJAX Add to Cart Button -->
                        <button class="btn addToCart" data-id="<?= $row['foodid'] ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php include 'cartsidebar.php'; ?>
    <?php include 'review_modal.php'; ?>

    <script src="review.js"></script>

    <!-- AJAX Add to Cart Script -->
    <script>
    document.querySelectorAll('.addToCart').forEach(button => {
        button.addEventListener('click', () => {
            const foodid = button.getAttribute('data-id');

            fetch('add_to_cart_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'foodid=' + foodid + '&quantity=1'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Item added to cart!');
                    // Auto-open cart sidebar
                    document.querySelector('#cartSidebar').classList.add('open');
                    updateCartSidebar();
                } else {
                    alert('Something went wrong!');
                }
            });
        });
    });

    function updateCartSidebar() {
        fetch('cartsidebar_ajax.php')
        .then(res => res.text())
        .then(html => document.querySelector('#cartSidebar').innerHTML = html);
    }
    </script>
</body>
</html>

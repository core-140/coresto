<?php
session_start();
include 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - CORESTO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'search_bar.php'; ?>

    <h2>Search Results for "<?php echo htmlspecialchars($q); ?>"</h2>

    <div class="menu-items">
        <?php
        if ($q != '') {
            $stmt = $conn->prepare("SELECT * FROM food WHERE foodname LIKE ?");
            $searchTerm = "%" . $q . "%";
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="menu-item">';
                    echo '<img src="img/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['foodname']) . '" style="width:100%; height:150px; object-fit:cover; border-radius:10px;">';
                    echo '<h3>' . htmlspecialchars($row['foodname']) . '</h3>';
                    echo '<p>₹' . $row['price'] . '</p>';
                    echo '<form action="cart.php" method="post">';
                    echo '<input type="hidden" name="food_id" value="' . $row['foodid'] . '">';
                    echo '<button type="submit">Add to Cart</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>No food items found.</p>";
            }
        } else {
            echo "<p>Please enter a search term.</p>";
        }
        ?>
    </div>
</body>
</html>
